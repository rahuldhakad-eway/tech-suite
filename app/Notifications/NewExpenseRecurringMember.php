<?php

namespace App\Notifications;

use App\Models\EmailNotificationSetting;
use App\Models\ExpenseRecurring;
use Illuminate\Notifications\Messages\SlackMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class NewExpenseRecurringMember extends BaseNotification
{


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $expense;
    private $emailSetting;

    public function __construct(ExpenseRecurring $expense)
    {
        $this->expense = $expense;
        $this->company = $this->expense->company;
        $this->emailSetting = EmailNotificationSetting::where('company_id', $this->company->id)->where('slug', 'new-expenseadded-by-member')->first();

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $via = ['database'];

        if ($this->emailSetting->send_email == 'yes' && $notifiable->email_notifications && $notifiable->email != '') {
            array_push($via, 'mail');
        }

        if ($this->emailSetting->send_slack == 'yes' && $this->company->slackSetting->status == 'active') {
            array_push($via, 'slack');
        }

        if ($this->emailSetting->send_push == 'yes') {
            array_push($via, OneSignalChannel::class);
        }

        return $via;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    //phpcs:ignore
    public function toMail($notifiable)
    {
        $url = route('recurring-expenses.show', $this->expense->id);
        $url = getDomainSpecificUrl($url, $this->company);

        $content = __('email.newExpenseRecurring.subject') . '.' . '<br>' . __('app.employee') . ': ' . mb_ucwords($this->expense->user->name) . '<br>' . __('modules.expenses.itemName') . ': ' . $this->expense->item_name . '<br>' . __('app.price') . ': ' . $this->expense->currency->currency_symbol . $this->expense->price;

        return parent::build()
            ->subject(__('email.newExpenseRecurring.subject') . ' - ' . config('app.name'))
            ->markdown('mail.email', [
                'url' => $url,
                'content' => $content,
                'themeColor' => $this->company->header_color,
                'actionText' => __('email.newExpenseRecurring.action')
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
//phpcs:ignore
    public function toArray($notifiable)
    {
        return $this->expense->toArray();
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param mixed $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $slack = $notifiable->company->slackSetting;

        if (count($notifiable->employee) > 0 && (!is_null($notifiable->employee[0]->slack_username) && ($notifiable->employee[0]->slack_username != ''))) {
            return (new SlackMessage())
                ->from(config('app.name'))
                ->image($slack->slack_logo_url)
                ->to('@' . $notifiable->employee[0]->slack_username)
                ->content(__('email.newExpenseRecurring.subject') . ' - ' . $this->expense->item_name . ' - ' . $this->expense->currency->currency_symbol . $this->expense->price . "\n" . url('/login'));
        }

        return (new SlackMessage())
            ->from(config('app.name'))
            ->image($slack->slack_logo_url)
            ->content('*' . __('email.newExpenseRecurring.subject') . '*' . "\n" .'This is a redirected notification. Add slack username for *' . $notifiable->name . '*');
    }

    // phpcs:ignore
    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->setSubject(__('email.newExpenseRecurring.subject'))
            ->setBody($this->expense->item_name . ' by ' . mb_ucwords($this->expense->user->name));
    }

}
