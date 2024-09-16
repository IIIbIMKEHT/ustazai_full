<?php

namespace App\Livewire;

use App\Models\Material;
use App\Models\MaterialType;
use App\Models\Subject;
use Database\Seeders\SubjectSeeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use Livewire\Component;

class GenerateMaterial extends Component
{
    public $loading = false;
    public MaterialType $type;
    public $classes = [
        1 => '1 класс',
        2 => '2 класс',
        3 => '3 класс',
        4 => '4 класс',
        5 => '5 класс',
        6 => '6 класс',
        7 => '7 класс',
        8 => '8 класс',
        9 => '9 класс',
        10 => '10 класс',
        11 => '11 класс'
    ];
    #[Validate('required')]
    public $class_id;
    public $subjects = [];
    #[Validate('required')]
    public $subject_id;
    // #[Validate('required', message: 'Тему урока обязательно надо указать')]
    public $topic;
    public $qty;
    public $term;
    public $lang = true;
    public $lang_id;
    public $content;
    public $wordLink;
    public $pdfLink;
    public function mount(MaterialType $type): void
    {
        $this->type = $type;
        $this->qty = 5;
        $this->term = "1";
    }

    public function updatedLangId()
    {
        if ($this->lang_id == 1) {
            $this->lang = true;
        } else {
            $this->lang = false;
        }
    }

    public function updatedClassId()
    {
        // Обновляем список предметов в зависимости от выбранного класса
        if (in_array($this->class_id, [1, 2, 3, 4])) {
            // Предметы для 1-4 классов
            $this->subjects = Subject::whereIn('id', [
                1, 2, 3, 4, 5, 6, 7, 8, 9, 10
            ])->get();
        } elseif (in_array($this->class_id, [5, 6, 7, 8, 9, 10, 11])) {
            // Предметы для 5-11 классов
            $this->subjects = Subject::whereIn('id', [11,12,13,14,15,16,17,18,19,20,21,22,23,24,25])->get();
        }

        $this->subject_id = null; // Сбросить выбранный предмет
    }

    public function startStream()
    {
        $this->validate();
        $this->dispatch('clear-content');
        if (auth()->user()->count == 0) {
            $this->dispatch('alert-message');
        } else {
           $this->dispatch('start-stream', detail: [
                'class_level' => strval($this->class_id),
                'subject' => intval($this->subject_id),
                'task_type' => intval($this->type->id),
                'topic' => $this->type->id == 9 ? "" : $this->topic,
                'is_kk' => $this->lang,
                'qty' => $this->qty,
                'term' => $this->term,
                'token' => $this->getEncryptedString()
            ]);
        }
    }


    public function getEncryptedString()
    {
        $encryptionKey = env('ENCRYPTION_KEY');
        $data = auth()->user()->count;
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $encryptionKey, OPENSSL_RAW_DATA, $iv);
        $combined = $iv . $encrypted;
        return rtrim(strtr(base64_encode($combined), '+/', '-_'), '=');
    }

    #[On('save-data')]
    public function saveData($content, $link)
    {
        Material::create([
            'user_id' => auth()->id(),
            'subject_id' => $this->subject_id,
            'type_id' => $this->type->id,
            'class_level' => $this->class_id,
            'title' => $this->type->id == 9 ? "СОЧ" : $this->topic,
            'content' => $content,
            'word_link' => $link
        ]);
        $user = Auth::user();
        $user->count--;
        $user->save();
        $this->dispatch('update-count');
        $this->dispatch('update-list');
    }
    public function send()
    {
        set_time_limit(300); // Устанавливает лимит в 60 секунд
        $this->loading = true;
        $this->dispatch('recreate');
        $this->wordLink = '';
        $this->pdfLink = '';

        $response = Http::timeout(300)->post('http://fastapi_app:5000/generate_material', [
            'class_level' => strval($this->class_id),
            'subject' => $this->subject_id,
            'task_type' => $this->type->id,
            'topic' => $this->type->id == 9 ? "" : $this->topic,
            'is_kk' => $this->lang,
            'qty' => $this->qty,
            'term' => $this->term
        ]);
        $result = json_decode($response->body(), 1);

        if ($result['valid']) {
            $this->content = $result['material'];
            $this->wordLink = $result['wordLink'];
            Material::create([
                'user_id' => auth()->id(),
                'subject_id' => $this->subject_id,
                'type_id' => $this->type->id,
                'class_level' => $this->class_id,
                'title' => $this->type->id == 9 ? "СОЧ" : $this->topic,
                'content' => $result['material'],
                'word_link' => $result['wordLink']
            ]);
        } else {
            $this->content = 'Выбранная тема не соответствует предмету и классу.';
        }
        $this->dispatch('run-formatter', content: $this->content, wordLink: $this->wordLink);
        $this->dispatch('update-list');
        $this->loading = false;
    }
    public function render()
    {
        return view('livewire.generate-material');
    }
}
