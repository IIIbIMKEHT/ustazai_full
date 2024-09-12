<?php

namespace App\Livewire;

use App\Models\Material;
use App\Models\MaterialType;
use App\Models\Subject;
use Database\Seeders\SubjectSeeder;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Validate;
use Livewire\Component;

class GenerateMaterial extends Component
{
    public $loading = false;
    public MaterialType $type;
    public $classes = [1,2,3,4,5,6,7,8,9,10,11];
    public $class_id;
    public $subjects;
    public $subject_id;
    // #[Validate('required', message: 'Тему урока обязательно надо указать')]
    public $topic;
    public $qty;
    public $term;
    public $lang;
    public $content;
    public $wordLink;
    public $pdfLink;
    public function mount(MaterialType $type): void
    {
        $this->type = $type;
        $this->class_id = 1;
        $this->subject_id = 1;
        $this->lang = 1;
        $this->qty = 5;
        $this->term = "1";
        $this->subjects = Subject::all();
    }

    public function send()
    {
        
        set_time_limit(300); // Устанавливает лимит в 60 секунд
        $this->loading = true;
        $this->content = '';
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
