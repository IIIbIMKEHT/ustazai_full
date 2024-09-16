<?php

namespace App\Livewire;

use App\Models\Attempt;
use Livewire\Component;
use Livewire\Attributes\On;

class AttemptCount extends Component
{
    public $count;

    public function mount()
    {
        $this->count = auth()->user()->count;
    }

    #[On('update-count')]
    public function updateCount()
    {
        $this->count = auth()->user()->count;
    }

    public function render()
    {
        return view('livewire.attempt-count');
    }
}
