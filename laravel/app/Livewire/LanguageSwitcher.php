<?php

namespace App\Livewire;

use Illuminate\Support\Facades\App;
use Livewire\Component;

class LanguageSwitcher extends Component
{
    public $is_open = false;
    public $languages = [
        'Русский' => 'ru',
        'Қазақ тілі' => 'kk'
    ];
    public $currentLocale;

    public function mount()
    {
        $this->currentLocale = App::getLocale();
    }

    public function toggle()
    {
        $this->is_open = !$this->is_open;
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}
