<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class LoadPdf extends Component
{
    use WithFileUploads;
    #[Validate('required|file|mimes:pdf')]
    public $file;
    public function save(): void
    {
        $this->validate();

        // Сохранение временного файла
        $path = $this->file->store('temp-files');
        set_time_limit(300);
        // Отправка файла на сервер
        $resp = Http::attach(
            'file',
            file_get_contents(storage_path('app/' . $path)),
            $this->file->getClientOriginalName()
        )->post('http://flask:5000/pdf');

        //$data = json_decode($resp->body(), true);
        flash()->success('Документ успешно загружен');

        // Удаление временного файла после отправки
        unlink(storage_path('app/' . $path));
    }
    public function render()
    {
        return view('livewire.load-pdf');
    }
}
