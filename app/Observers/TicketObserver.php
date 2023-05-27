<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Ticket;
use App\Events\TicketEvent;
use App\Models\Notification;
use App\Models\UniversalSearch;
use App\Models\TicketAgentGroups;
use App\Events\TicketRequesterEvent;

class TicketObserver
{

    public function saving(Ticket $ticket)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $userID = (!is_null(user())) ? user()->id : $ticket->user_id;
            $ticket->last_updated_by = $userID;
        }
    }

    public function creating(Ticket $model)
    {
        if (!isRunningInConsoleOrSeeding()) {
            $userID = (!is_null(user())) ? user()->id : $model->user_id;
            $model->added_by = $userID;

            if ($model->isDirty('status') && $model->status == 'closed') {
                $model->close_date = now(company()->timezone)->format('Y-m-d');
            }

        }

        if (company()) {
            $model->company_id = company()->id;
        }

        $model->ticket_number = (int)Ticket::max('ticket_number') + 1;

    }

    public function created(Ticket $model)
    {
        if (!isRunningInConsoleOrSeeding()) {
            // Send admin notification
            event(new TicketEvent($model, 'NewTicket'));

            if ($model->requester) {
                event(new TicketRequesterEvent($model, $model->requester));
            }

            if ($model->agent_id != '') {
                event(new TicketEvent($model, 'TicketAgent'));
            }

            request()->assign_group ? $group_id = request()->assign_group : $group_id = request()->group_id;

            $agentGroupData = TicketAgentGroups::where('company_id', company()->id)
                ->where('status', 'enabled')
                ->where('group_id', $group_id)
                ->pluck('agent_id')
                ->toArray();
            $ticketData = $model->where('company_id', company()->id)
                ->where('group_id', $group_id)
                ->whereIn('agent_id', $agentGroupData)
                ->whereIn('status', ['open', 'pending'])
                ->whereNotNull('agent_id')
                ->pluck('agent_id')
                ->toArray();

            $diffAgent = array_diff($agentGroupData, $ticketData);

            if(is_null(request()->agent_id)) {
                if(!empty($diffAgent)){
                    $model->agent_id = current($diffAgent);
                }
                else {
                    $agentDuplicateCount = array_count_values($ticketData);

                    if(!empty($agentDuplicateCount)) {
                        $minVal = min($agentDuplicateCount);
                        $agent_id = array_search($minVal, $agentDuplicateCount);
                        $model->agent_id = $agent_id;
                    }
                }
            }
            else {
                $model->agent_id = request()->agent_id;
            }

            $model->save();

        }
    }

    public function updating(Ticket $ticket)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if ($ticket->isDirty('status') && $ticket->status == 'closed') {
                $ticket->close_date = now(company()->timezone)->format('Y-m-d');
            }

        }
    }

    public function updated(Ticket $ticket)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if ($ticket->isDirty('agent_id') && $ticket->agent_id != '') {
                event(new TicketEvent($ticket, 'TicketAgent'));
            }
        }
    }

    public function deleting(Ticket $ticket)
    {
        $universalSearches = UniversalSearch::where('searchable_id', $ticket->id)->where('module_type', 'ticket')->get();

        if ($universalSearches) {
            foreach ($universalSearches as $universalSearch) {
                UniversalSearch::destroy($universalSearch->id);
            }
        }

        $notifyData = ['App\Notifications\NewTicket', 'App\Notifications\NewTicketReply', 'App\Notifications\NewTicketRequester', 'App\Notifications\TicketAgent'];

        \App\Models\Notification::deleteNotification($notifyData, $ticket->id);

    }

}
