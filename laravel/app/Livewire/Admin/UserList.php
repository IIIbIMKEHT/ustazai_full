<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public $query = '';
    public $count;

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function addCount($userID)
    {
        $user = User::find($userID);
        $user->count += intval($this->count);
        $user->save();
        $this->count = null;
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.user-list', [
            'users' => User::with('materials')->where('role_id', 2)->where('email', 'like', '%'.$this->query.'%')->latest()->paginate(20)
        ]);
    }
}
