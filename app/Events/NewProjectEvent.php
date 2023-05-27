<?php

namespace App\Events;

use App\Models\Project;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewProjectEvent
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $project;
    public $projectStatus;
    public $notifyUser;

    public function __construct(Project $project, $notifyUser, $projectStatus=null)
    {

        $this->project = $project;
        $this->notifyUser = $notifyUser;
        $this->projectStatus = $projectStatus;
    }

}
