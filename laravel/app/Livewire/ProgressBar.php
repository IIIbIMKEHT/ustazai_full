<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class ProgressBar extends Component
{
    public $progress = 0;
    #[On('update-progress')]
    public function updateProgress($content): void
    {
        $this->progress = $content;
    }
    public function render()
    {
        return view('livewire.progress-bar');
    }
}
