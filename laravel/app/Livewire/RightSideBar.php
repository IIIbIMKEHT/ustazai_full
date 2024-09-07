<?php

namespace App\Livewire;

use App\Models\Material;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class RightSideBar extends Component
{
    public $materials;
    #[On('update-list')]
    public function updateList(): void
    {
        $this->getLists();
    }
    public function mount(): void
    {
        $this->getLists();
    }

    public function getLists(): void
    {
        $this->materials= Material::select('subjects.title_ru as subject_name', 'subjects.id as subject_id', DB::raw('count(materials.id) as count'))
            ->join('subjects', 'materials.subject_id', '=', 'subjects.id')
            ->groupBy('subject_id', 'subjects.title_ru')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->subject_name => [
                        'id' => $item->subject_id,
                        'count' => $item->count
                    ]
                ];
            });
    }
    public function render()
    {
        return view('livewire.right-side-bar');
    }
}
