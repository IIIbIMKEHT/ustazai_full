<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Subject::count() == 0) {
            Subject::create([
                'title_kk' => 'Алгебра',
                'title_ru' => 'Алгебра'
            ]);
            Subject::create([
                'title_kk' => 'Ағылшын тілі',
                'title_ru' => 'Английский язык'
            ]);
            Subject::create([
                'title_kk' => 'Астрономия',
                'title_ru' => 'Астрономия'
            ]);
            Subject::create([
                'title_kk' => 'Биология',
                'title_ru' => 'Биология'
            ]);
            Subject::create([
                'title_kk' => 'География',
                'title_ru' => 'География'
            ]);
            Subject::create([
                'title_kk' => 'Геометрия',
                'title_ru' => 'Геометрия'
            ]);
            Subject::create([
                'title_kk' => 'Информатика',
                'title_ru' => 'Информатика'
            ]);
            Subject::create([
                'title_kk' => 'Қазақстан тарихы',
                'title_ru' => 'История Казахстана'
            ]);
            Subject::create([
                'title_kk' => 'Дүниежүзі тарихы',
                'title_ru' => 'Всемирная история'
            ]);
            Subject::create([
                'title_kk' => 'Музыка',
                'title_ru' => 'Музыка'
            ]);
            Subject::create([
                'title_kk' => 'Сурет',
                'title_ru' => 'Рисование'
            ]);
            Subject::create([
                'title_kk' => 'Орыс тілі',
                'title_ru' => 'Русский язык'
            ]);
            Subject::create([
                'title_kk' => 'Орыс әдебиеті',
                'title_ru' => 'Русская литература'
            ]);
            Subject::create([
                'title_kk' => 'Физика',
                'title_ru' => 'Физика'
            ]);
            Subject::create([
                'title_kk' => 'Химия',
                'title_ru' => 'Химия'
            ]);
            Subject::create([
                'title_kk' => 'Дене шынықтыру',
                'title_ru' => 'Физическая культура'
            ]);
        }
    }
}
