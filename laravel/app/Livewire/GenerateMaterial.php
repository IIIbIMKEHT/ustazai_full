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
    public $class_id;
    public $subjects = [];
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
        $this->lang = 1;
        $this->qty = 5;
        $this->term = "1";
    }

    public function updatedClassId()
    {
        // Обновляем список предметов в зависимости от выбранного класса
        if (in_array($this->class_id, [1, 2, 3, 4])) {
            // Предметы для 1-4 классов
            $this->subjects = Subject::whereIn('title_ru', [
                'Казахский язык', 'Русский язык', 'Литературное чтение', 'Математика', 'Окружающий мир', 
                'Изобразительное искусство', 'Музыка', 'Трудовое обучение', 'Физическая культура', 'Самопознание'
            ])->get();
        } elseif (in_array($this->class_id, [5, 6, 7, 8, 9, 10, 11])) {
            // Предметы для 5-11 классов
            $this->subjects = Subject::whereIn('title_ru', [
                'Казахский язык и литература', 'Русский язык и литература', 'Английский язык', 
                'История Казахстана', 'Всеобщая история', 'Алгебра', 'Геометрия', 'География', 'Биология', 
                'Физика', 'Химия', 'Информатика', 'Изобразительное искусство', 'Трудовое обучение', 
                'Физическая культура', 'Самопознание'
            ])->get();
        }

        $this->subject_id = null; // Сбросить выбранный предмет
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
