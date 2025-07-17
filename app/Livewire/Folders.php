<?php

namespace App\Livewire;

use App\Models\Project;

use Illuminate\Support\Facades\Log;

class Folders extends Window
{
    public $projects;

    public function mount(...$params)
    {
        parent::mount(...$params);
        $this->projects = Project::all();
    }

    public function openProjectFile($projectId)
    {
        $this->dispatch('openFile', [
            'appKey' => 'file',
            'fileType' => 'project',
            'fileId' => $projectId,
        ]);
        //Log this bitch
        Log::info("Folders - Opening project file for project ID: {$projectId}");
    }

    public function render()
    {
        return view('livewire.folders', [
            'projects' => $this->projects,
        ]);
    }
}
