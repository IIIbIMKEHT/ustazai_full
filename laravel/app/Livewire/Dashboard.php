<?php

namespace App\Livewire;

use App\Models\MaterialType;
use Livewire\Component;

class Dashboard extends Component
{
    public $types;
    public $type_id;

    public function mount(): void
    {
        $this->types = MaterialType::all();
    }

    public function goToGenerate($id): void
    {
        $this->redirect(route('generate-material', $id));
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
