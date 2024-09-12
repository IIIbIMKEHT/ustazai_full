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
            $subjects = [
                ['title_kk' => 'Қазақ тілі', 'title_ru' => 'Казахский язык'],
                ['title_kk' => 'Орыс тілі', 'title_ru' => 'Русский язык'],
                ['title_kk' => 'Әдебиеттік оқу', 'title_ru' => 'Литературное чтение'],
                ['title_kk' => 'Математика', 'title_ru' => 'Математика'],
                ['title_kk' => 'Дүниетану', 'title_ru' => 'Окружающий мир'],
                ['title_kk' => 'Бейнелеу өнері', 'title_ru' => 'Изобразительное искусство'],
                ['title_kk' => 'Музыка', 'title_ru' => 'Музыка'],
                ['title_kk' => 'Еңбекке баулу', 'title_ru' => 'Трудовое обучение'],
                ['title_kk' => 'Дене шынықтыру', 'title_ru' => 'Физическая культура'],
                ['title_kk' => 'Самопознание', 'title_ru' => 'Самопознание'],
    
                // Предметы 5-11 классы
                ['title_kk' => 'Қазақ тілі мен әдебиеті', 'title_ru' => 'Казахский язык и литература'],
                ['title_kk' => 'Орыс тілі мен әдебиеті', 'title_ru' => 'Русский язык и литература'],
                ['title_kk' => 'Ағылшын тілі', 'title_ru' => 'Английский язык'],
                ['title_kk' => 'Қазақстан тарихы', 'title_ru' => 'История Казахстана'],
                ['title_kk' => 'Дүниежүзі тарихы', 'title_ru' => 'Всеобщая история'],
                ['title_kk' => 'География', 'title_ru' => 'География'],
                ['title_kk' => 'Биология', 'title_ru' => 'Биология'],
                ['title_kk' => 'Физика', 'title_ru' => 'Физика'],
                ['title_kk' => 'Химия', 'title_ru' => 'Химия'],
                ['title_kk' => 'Информатика', 'title_ru' => 'Информатика'],
                ['title_kk' => 'Алгебра', 'title_ru' => 'Алгебра'],
                ['title_kk' => 'Геометрия', 'title_ru' => 'Геометрия'],
                ['title_kk' => 'Адам. Қоғам. Құқық', 'title_ru' => 'Человек. Общество. Право'],
                ['title_kk' => 'Физикалық мәдениет', 'title_ru' => 'Физическая культура'],
                ['title_kk' => 'Самопознание', 'title_ru' => 'Самопознание'],
            ];
    
            foreach ($subjects as $subject) {
                Subject::create($subject);
            }
        }
    }
}
