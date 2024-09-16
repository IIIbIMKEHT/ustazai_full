<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public $query = '';

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function mount()
    {

    }

    public function render()
    {
        return view('livewire.admin.user-list', [
            'users' => User::where('role_id', 2)->where('email', 'like', '%'.$this->query.'%')->latest()->paginate(20)
        ]);
    }
}
