<?php

namespace App\Livewire\Admin;

use App\Models\Material;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class RightSidebar extends Component
{

    public function mount(): void
    {

    }


    public function render()
    {
        return view('livewire.admin.right-sidebar');
    }
}
