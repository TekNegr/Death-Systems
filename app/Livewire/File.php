<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class File extends Window
{
    public $fileType;
    public $fileId;
    public $project;
    public $photoUrl;
    public $resumePath;

    public function mount($windowId, $appKey, $x, $y, $width, $height, $isOpen, $fileType = null, $fileId = null)
    {
        parent::mount($windowId, $appKey, $x, $y, $width, $height, $isOpen);
        $this->fileType = $fileType;
        $this->fileId = $fileId;
        Log::info("File - File component mounted with type: {$this->fileType} and ID: {$this->fileId}");
        if ($this->fileType === 'project' && $this->fileId) {
            $this->project = Project::find($this->fileId);
            if ($this->project) {
                Log::info("File - Project loaded: {$this->project->name}");
            } else {
                Log::warning("File - Project not found for ID: {$this->fileId}");
            }
        } elseif ($this->fileType === 'photo' && $this->fileId) {
            $this->photoUrl = asset('storage/photos/' . $this->fileId);
        } elseif ($this->fileType === 'resume') {
            $this->resumePath = asset('storage/resume.pdf');
        }
    }

    public function render()
    {
        return view('livewire.file');
    }
}
