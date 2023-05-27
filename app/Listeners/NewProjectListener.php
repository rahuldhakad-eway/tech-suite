<?php

namespace App\Listeners;

use App\Models\User;
use App\Scopes\ActiveScope;
use App\Events\NewProjectEvent;
use App\Notifications\NewProject;
use App\Notifications\NewProjectStatus;
use Illuminate\Support\Facades\Notification;

class NewProjectListener
{

    /**
     * @param NewProjectEvent $event
     */

    public function handle(NewProjectEvent $event)
    {
        if (($event->project->client_id != null)) {
            $clientId = $event->project->client_id;
            // Notify client
            $notifyUsers = User::withoutGlobalScope(ActiveScope::class)->findOrFail($clientId);

            if (!is_null($notifyUsers) && is_null($event->projectStatus)) {

                Notification::send($notifyUsers, new NewProject($event->project));
            }
        }

        $projectMembers = $event->project->projectMembers;

        if ($event->projectStatus == 'statusChange') {
            if (!is_null($event->notifyUser) && !($event->notifyUser instanceof \Illuminate\Database\Eloquent\Collection)) {
                $event->notifyUser->notify( new NewProjectStatus($event->project));
            }

            Notification::send($projectMembers, new NewProjectStatus($event->project));
        }

    }

}
