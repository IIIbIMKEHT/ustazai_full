from langchain_core.prompts import PromptTemplate, PipelinePromptTemplate


full_template = """{language_detect}
{content}
{html_parse}"""
full_prompt = PromptTemplate.from_template(full_template)
# Первый шаблон: Определение языка для генерации материала
language_determination_prompt = PromptTemplate.from_template("""
Если выбранный предмет является языком (например, "Английский", "Французский", "Немецкий" и т.д.), генерируйте материал на языке предмета. В противном случае, используйте выбранный язык для генерации материала.
Определенный язык: {language}. 
""")
# Второй шаблон: Определение языка для генерации материала
html_prompt = PromptTemplate.from_template("""
    Генерируй HTML-код для учебного материала на языке {language}, используя только теги `<h1>`, `<h2>`, `<p>`, `<ul>`, `<li>`, `<strong>`. Не включай такие теги, как `<html>`, `<head>`, `<meta>`, `<doctype>`, и избегай символов ```html или ``` в начале и конце.
    Если в материале есть математические формулы, они должны быть представлены в формате MathML. Пример формулы: <math xmlns="http://www.w3.org/1998/Math/MathML"><mn>1</mn> <mo>+</mo> <mn>1</mn></math>. Убедись, что все формулы правильно интегрированы в HTML-код.
    Контент должен быть на {language} языке и поддерживать шрифты, работающие с кириллицей. Структура и оформление должны быть чистыми и понятными, избегай ненужных комментариев и пояснений.
    """
)

# Определение шаблонов для каждого типа задачи
test_prompt = PromptTemplate.from_template("""
Вы — опытный помощник учителя с более чем 15-летним опытом разработки тестов по различным предметам. 
Ваша специализация — создание тестов, которые проверяют знания учащихся и способствуют их образовательному развитию. 
Вам необходимо разработать тест на следующую тему:
- Предмет: {subject}. 
- Класс: {class_level}. 
- Тема: {topic}. 
- Количество вопросов: {qty}. 
- Уровень сложности: {level_test}.
- Формат вопросов: открытые.

Требования:
1. Каждый вопрос должен содержать 4 варианта ответов.
2. Один из вариантов должен быть правильным, и правильный ответ должен быть явно отмечен для учителя.
3. Вопросы должны быть разнообразными, чтобы проверять разные аспекты знаний по теме.
4. Убедитесь, что тест соответствует уровню сложности для класса {class_level}.

Структура теста:
1. **Вопрос**: Текст вопроса.
2. **Варианты ответов**: Приведите 4 варианта ответа. 
3. **Правильный ответ**: Чётко отметьте правильный ответ (например, с пометкой "Правильный ответ: ...").
""")
lesson_plan_prompt = PromptTemplate.from_template("""
Вы — опытный помощник учителя. Создайте подробный и структурированный план урока на {language} языке.
План урока должен быть легко воспринимаем учащимися и включать следующие части:

1. **Введение** (не менее 200 слов): Опишите, как начать урок, представьте материал увлекательно, чтобы сразу заинтересовать учеников темой урока. Используйте вопросы или небольшие активности для вовлечения учащихся. Приведите примеры, как представить тему, чтобы ученики могли сразу погрузиться в изучение.
2. **Основная часть** (не менее 500 слов): Подробно раскройте основные идеи урока. Опишите пошаговые методы объяснения ключевых понятий, приведите несколько примеров и включите различные типы заданий (индивидуальные, групповые). Добавьте пояснения о том, как преподавать материал эффективно. Опишите промежуточные вопросы, которые помогут учащимся лучше усвоить материал. Включите подробное описание каждой планеты или важного аспекта Солнечной системы.
3. **Заключение** (не менее 200 слов): Подведите итог урока, обобщите основные моменты, дайте конкретные задания для закрепления материала (например, практические задания, домашние работы). Включите рекомендации по дополнительным ресурсам для дальнейшего изучения темы.
4. **Вопросы для обсуждения**: Включите минимум 8-10 вопросов, которые можно обсудить в классе для углубления понимания темы и стимулирования критического мышления.
5. **Дополнительно**: Предложите альтернативные подходы к преподаванию сложных моментов, а также интеграцию мультимедиа или интерактивных элементов в урок. Добавьте рекомендации по управлению временем в течение урока.

Данные для плана:
- Предмет: {subject}
- Тема урока: {topic}
- Класс: {class_level}
- Продолжительность: 50 минут
""")
quiz_prompt = PromptTemplate.from_template("""
Ты — умный помощник учителя, обладающий знаниями по разработке учебных материалов и викторин для учащихся разных классов.
Твоя задача — составить викторину по теме "{topic}" для {class_level} класса на {language} языке.

### Важные детали:
- Предмет: {subject}.
- Класс: {class_level}.
- Тема урока: {topic}.
- Длительность урока: 50 минут.
- Уровень сложности вопросов: {level_test}.
- Количество вопросов: {qty}.

### Требования к викторине:
1. **Типы вопросов**: Викторина должна включать вопросы различных типов, таких как вопросы с выбором ответа, открытые вопросы и задания на сопоставление.
2. **Структура вопроса**:
   - Вопрос: Текст вопроса должен быть понятным и соответствовать уровню знаний учащихся.
   - Варианты ответов (для вопросов с выбором ответа): Приведи не менее 4 вариантов ответов.
   - **Правильный ответ**: Укажи правильный вариант ответа и объясни, почему он верный.
3. **Чередование сложности**: Вопросы должны быть разного уровня сложности, начиная с более простых и заканчивая более сложными.

Убедись, что викторина соответствует возрастным особенностям учащихся и поддерживает интерес к изучаемому материалу. Материал должен быть адаптирован под выбранный уровень сложности.
"""
)
intellectual_game_prompt = PromptTemplate.from_template("""
Вы — помощник учителя, специализирующийся на создании образовательных игр для учеников. Ваша задача — создать увлекательную интеллектуальную игру для предмета {subject} для {class_level} класса на тему {topic}.

Игра должна:
1. Быть подходящей для учеников уровня {class_level}.
2. Интересной и вовлекающей учащихся в процесс.
3. Помогать проверять знания учащихся по теме.
4. Стимулировать критическое мышление и командную работу.

Предоставьте полное описание игры, включая:
1. **Описание игры** (не менее 300 слов): Как проходит игра? Какие задания должны выполнять учащиеся? 
2. **Цель игры** (не менее 200 слов): Какую цель должны достичь ученики, чтобы выиграть?
3. **Примеры вопросов или заданий**: Укажите 8-10 конкретных вопросов или заданий, которые ученики должны выполнить.
4. **Развитие сотрудничества** (не менее 300 слов): Как игра стимулирует учеников работать в команде?
5. **Система оценивания** (не менее 200 слов): Как будет оцениваться выполнение заданий (например, баллы, уровни или другой метод оценки)?

Описание игры должно быть полным и включать все детали, чтобы учитель мог легко адаптировать её для использования на уроке.
""")
exercises_prompt = PromptTemplate.from_template("""
Ты — опытный помощник учителя с большим опытом разработки обучающих упражнений для различных классов. Твоя основная цель — создавать структурированные, интерактивные упражнения, которые помогают учащимся усвоить материал на 50-минутном уроке.

**Информация о задании**:
- Предмет: {subject}
- Класс: {class_level}
- Тема упражнения: {topic}

Требования к упражнению:
1. Оно должно быть структурировано и адаптировано под уровень учащихся.
2. Включать ясные и пошаговые инструкции.
3. Быть интерактивным, с возможностью групповой работы, обсуждений или практических заданий.
4. Учащиеся должны выполнять задания, которые стимулируют их к критическому мышлению и активному вовлечению.

**Разделы упражнения**:
1. **Введение** (не менее 100 слов): Объясни цель упражнения и как оно связано с текущей темой урока. Предоставь контекст, чтобы ученики понимали, зачем они выполняют это задание.
2. **Инструкции** (не менее 200 слов): Подробно опиши шаги, которые нужно выполнить ученикам. Добавь примеры выполнения заданий, чтобы облегчить понимание.
3. **Практическая часть** (не менее 500 слов): Описание задач или вопросов, которые ученики должны выполнить, включая примеры и пояснения. Включи минимум 3 задачи или вопроса.
4. **Обсуждение и рефлексия** (не менее 300 слов): Предложи вопросы для обсуждения в классе, чтобы стимулировать критическое мышление и анализ выполненной работы. Включи задания для закрепления материала.

Обеспечь, чтобы упражнение было увлекательным, практическим и стимулирующим для учащихся.
""")
laboratory_prompt = PromptTemplate.from_template("""
Ты — умный помощник учителя с опытом разработки учебных материалов и лабораторных работ для различных предметов. 
Твоя задача — создать детализированную лабораторную работу по предмету "{subject}" на тему "{topic}" для {class_level} класса.

Лабораторная работа должна быть четко структурирована и включать следующие разделы:

1. **Цели лабораторной работы** (не менее 100 слов): Объясни, что студенты должны понять и чему научиться в ходе выполнения лабораторной работы. Укажи конкретные образовательные результаты, которые должны быть достигнуты.
2. **Материалы и оборудование** (не менее 100 слов): Перечисли все необходимые материалы и оборудование для выполнения работы. Укажи, если требуется специальная техника или реактивы.
3. **Критерии оценки** (не менее 200 слов): Опиши, как будет оцениваться выполнение лабораторной работы. Укажи, какие аспекты будут оцениваться: точность выполнения, правильность расчётов, соблюдение техники безопасности и т. д.
4. **Шаги выполнения** (не менее 500 слов): Подробно опиши пошаговый процесс выполнения лабораторной работы. Включи все действия, которые нужно выполнить, и объясни, как правильно проводить измерения, эксперименты и записывать результаты. Также приведи примеры расчётов или ожидаемых результатов, если они необходимы.

Убедись, что лабораторная работа подходит для уровня класса {class_level} и стимулирует критическое мышление.
""")
summary_prompt = PromptTemplate.from_template("""
Ты умный помощник учителя, обладающий обширными знаниями в области педагогики и создания учебных материалов.
Твоя задача — разработать подробный и исчерпывающий конспектный материал по предмету {subject} на тему {topic} для {class_level} класса.

### Важные детали:
- Предмет: {subject}.
- Класс: {class_level}.
- Тема урока: {topic}.
- Длительность урока: 50 минут.

### Требования к конспекту:
1. **Вводное слово** (не менее 200 слов): Объясни важность темы для данного предмета и её применение в реальной жизни. Убедись, что учащиеся понимают, почему эта тема важна для их дальнейшего обучения.

2. **Основные идеи** (не менее 4500 слов): Детализируй ключевые понятия, используя несколько примеров для их объяснения. Включи простые и сложные задачи, разъясни, как применить полученные знания. Важно: каждый этап должен быть представлен с подробным объяснением и пошаговыми инструкциями.

3. **Практическое задание** (не менее 400 слов): Приведи как минимум 5 сложные задачи, которые учащиеся должны решить. Включи пошаговое решение каждой задачи, с объяснением, как подойти к решению, и какие методы использовать.

4. **Заключение** (не менее 200 слов): Подведи итоги урока, обобщи ключевые моменты. Предложи дальнейшие направления для углубления знаний. Укажи, какие дополнительные ресурсы можно использовать для изучения темы.

Учти, что конспект должен быть адаптирован для уровня знаний учащихся данного класса, поэтому избегай сложных терминов, если это не требуется, и обеспечь подробные разъяснения на каждом этапе.
""")
sor_prompt = PromptTemplate.from_template("""
Вы — эксперт в разработке оценочных материалов. Создайте структуру и содержание для СОР (Суммативного Оценивания за Раздел) на {language} языке. Оценивание должно быть понятно учащимся и включать следующие части:

1. **Описание**: Введите краткое описание цели оценивания, объясняя, что проверяется, и какие знания и навыки учеников оцениваются по завершённому разделу. Укажите общую продолжительность СОР (до 40 минут) и вес оценивания в итоговой оценке по предмету.

2. **Задания**:
    - **Первое задание (минимум 2-3 балла)**: Разработайте простое задание, проверяющее базовые знания и понимание темы. Опишите условия задания, критерии оценивания и максимальные баллы.
    - **Второе задание (минимум 3-4 балла)**: Усложните задачу, включив элементы анализа или расчёта, требующие более глубокого понимания темы. Добавьте чёткие критерии оценивания.
    - **Третье задание (минимум 4-5 баллов)**: Это задание должно быть на высший уровень сложности, проверяющее навыки применения и синтеза знаний. Описание задачи должно быть чётким, с указанием на способы её решения, и максимальные баллы для оценки. Если тема требует работы с данными, добавьте задание с использованием таблиц для анализа или расчётов.
    - **Задания с таблицами** (если применимо): Если тема включает таблицы или данные, разработайте задание, где ученики должны заполнить, проанализировать или интерпретировать данные в таблице.

3. **Рекомендации для учителей**: Включите пояснения для учителей по каждому заданию, как проверять ответы, а также ключевые моменты, на которые стоит обратить внимание при оценивании. Предложите способы предоставить обратную связь ученикам.

4. **Дополнительное задание (по желанию)**: Предложите творческое задание, которое ученики могут выполнить для дополнительного балла. Оно может быть связано с практическим применением знаний в реальной жизни или интеграцией мультимедиа и технологий.

Данные для разработки СОР:
- Предмет: {subject}
- Тема раздела: {topic}
- Класс: {class_level}
""")
soch_prompt = PromptTemplate.from_template("""
Вы — опытный специалист по оцениванию. Создайте структуру и содержание для СОЧ (Суммативного Оценивания за Четверть) на {language} языке. Оценивание должно быть адаптировано для учащихся и включать следующие части:

1. **Описание**: Введите краткое описание цели СОЧ, объясните, что проверяется в конце {term}-й четверти, какие знания и навыки учеников оцениваются. Укажите продолжительность СОЧ (до 50 минут) и долю оценки в итоговой оценке за четверть.

2. **Задания**:
    - **Первое задание (минимум 2-3 балла)**: Разработайте задание для проверки базовых знаний и умений, полученных за {term}-ю четверть. Оно должно быть нацелено на базовое понимание материала. Опишите критерии оценивания и максимальные баллы.
    - **Второе задание (минимум 3-4 балла)**: Задание средней сложности, проверяющее способность учеников применять изученные навыки в практических ситуациях. Включите примеры или задачи с чёткими критериями оценивания. Если тема требует анализа данных, добавьте задание в табличной форме.
    - **Третье задание (минимум 4-5 баллов)**: Это задание должно проверять умение учеников анализировать и синтезировать знания, полученные за {term}-ю четверть. Условия задачи должны быть чёткими с указанием возможных способов её решения и критериями для максимальной оценки. Если это уместно для темы (например, алгебра), добавьте графики для анализа и построения.
    - **Четвёртое задание (минимум 3-4 балла)**: Предложите задание, которое требует творческого или нестандартного подхода к решению, стимулирующее критическое мышление и углублённое понимание темы.
    - **Пятое задание (минимум 4-5 баллов)**: Составьте сложное задание, требующее применения нескольких понятий или методик, изученных в течение {term}-й четверти. Оно должно быть ориентировано на учеников с высоким уровнем подготовки и включать чёткие критерии оценивания. Если уместно, используйте табличную или графическую форму для представления данных.

3. **Рекомендации для учителей**: Предоставьте пояснения для учителей по каждому заданию. Опишите ключевые моменты, которые необходимо учитывать при оценивании, и предложите способы предоставления обратной связи ученикам.

4. **Дополнительное задание (по желанию)**: Создайте творческое задание, которое можно использовать для получения дополнительного балла. Оно может касаться интеграции тем {term}-й четверти в реальную жизнь или использования технологий и мультимедиа.

Данные для разработки СОЧ:
- Предмет: {subject}
- Класс: {class_level}
- Четверть: {term}
""")
ksp_prompt = PromptTemplate.from_template("""
Создай краткосрочный план урока для учащихся {class_level} класса по предмету {subject} на тему "{topic}" в табличной форме. Используй следующую структуру, позволяя GPT самому разнообразить этапы урока, задания и действия педагога, чтобы они соответствовали теме и классу:

|**Класс**: | {class_level} |
|**Раздел (сквозная тема)**: | GPT должен самостоятельно генерировать название раздела на основе темы "{topic}" и предмета "{subject}". |
|**Тема урока**: | {topic} |
|**Цели обучения в соответствии с учебной программой**: | Сформулируй не менее 2 целей обучения. |
|**Цели урока**: | Определи цели урока в зависимости от темы и предмета. |

**Ход урока**:

GPT должен варьировать этапы урока в зависимости от темы и аудитории, не ограничиваясь одной структурой.

| **Этап урока/ Время** | **Действия педагога** | **Действия ученика** | **Оценивание** | **Ресурсы** |
| --- | --- | --- | --- | --- |
| Начало урока (0-5 мин) | Учитель приветствует учеников и проводит эмоциональное упражнение, связанное с темой урока. Это может быть разминка, короткий вопрос или обсуждение, связанное с {topic}. Пример: "Как вы думаете, зачем изучать {topic} в нашем мире?". Учитель может варьировать способы привлечения внимания в зависимости от контекста. | Учащиеся приветствуют учителя, участвуют в размышлении, делятся своими мнениями. | Формативное оценивание на основе активности в обсуждении. | Презентация, доска. |
| Середина урока (5-35 мин) | Учитель использует разные методы для объяснения темы {topic}, в зависимости от её сложности. Это может включать следующие этапы: \n 1. Актуализация жизненного опыта: Примерный вопрос "Где мы можем увидеть применение {topic} в повседневной жизни?" или практическое задание. \n 2. Вводное задание: Пример: "Опишите, что вы уже знаете о {topic} и как это может быть полезным?" \n 3. Постановка цели: Пример: "Чего мы хотим достичь, изучая {topic}?" \n 4. Объяснение темы: Учитель объясняет новую тему, используя практические примеры, задает вопросы на проверку понимания. \n 5. Первичное закрепление: Пример: "Приведите свои примеры или выполните упражнение по {topic}." \n 6. Развитие функциональной грамотности: Учитель может предложить задание на применение знаний, пример: "Создайте задачу, которая демонстрирует использование {topic} в реальной жизни." | Ученики работают в парах или группах, участвуют в обсуждениях, выполняют практические задания, записывают свои наблюдения. | Оценивание через ответы на вопросы, выполнение заданий и проговаривание результатов. | Учебник, рабочие листы, компьютер или доска. |
| Конец урока (35-40 мин) | Учитель завершает урок, задавая итоговые вопросы по {topic}. Это может быть мини-дискуссия или обобщение материала. Пример: "Как знания о {topic} могут помочь в будущем?" \n Рефлексия: Учитель предлагает ученикам оценить свою работу и обсудить трудности. Пример: "Что было самым сложным на уроке и почему?" | Учащиеся делятся выводами, обсуждают материал, оценивают свои успехи и результаты. | Оценивание через участие в рефлексии и обсуждении. | Презентация, доска. |

**Домашнее задание**: Придумай задание для закрепления материала, связанное с темой {topic}.
**Ожидаемые результаты**: Определи, что ученики должны будут уметь после урока.

GPT должен самостоятельно варьировать структуру урока, задания для учителя и учеников, а также примеры, чтобы каждый урок был уникальным и соответствовал теме.
""")







# Вы можете продолжить добавлять другие шаблоны аналогичным образом...
input_test_prompts = [
    ("language_detect", language_determination_prompt),
    ("content", test_prompt),
    ("html_parse", html_prompt),
]
input_lesson_plan_prompts = [
    ("language_detect", language_determination_prompt),
    ("content", lesson_plan_prompt),
    ("html_parse", html_prompt),
]
input_quiz_prompts = [
    ("language_detect", language_determination_prompt),
    ("content", quiz_prompt),
    ("html_parse", html_prompt),
]
input_intellectual_game_prompts = [
    ("language_detect", language_determination_prompt),
    ("content", intellectual_game_prompt),
    ("html_parse", html_prompt),
]
input_exercises_prompts = [
    ("language_detect", language_determination_prompt),
    ("content", exercises_prompt),
    ("html_parse", html_prompt),
]
input_laboratory_prompts = [
    ("language_detect", language_determination_prompt),
    ("content", laboratory_prompt),
    ("html_parse", html_prompt),
]
input_summary_prompts = [
    ("language_detect", language_determination_prompt),
    ("content", summary_prompt),
    ("html_parse", html_prompt),
]
input_sor_prompts = [
    ("language_detect", language_determination_prompt),
    ("content", sor_prompt),
    ("html_parse", html_prompt),
]
input_soch_prompts = [
    ("language_detect", language_determination_prompt),
    ("content", soch_prompt),
    ("html_parse", html_prompt),
]
input_ksp_prompts = [
    ("language_detect", language_determination_prompt),
    ("content", ksp_prompt),
    ("html_parse", html_prompt),
]
# Словарь для хранения шаблонов PromptTemplate по типам задач
task_templates = {
    "TEST": PipelinePromptTemplate(final_prompt=full_prompt, pipeline_prompts=input_test_prompts),
    "LESSON_PLAN": PipelinePromptTemplate(final_prompt=full_prompt, pipeline_prompts=input_lesson_plan_prompts),
    "QUIZ": PipelinePromptTemplate(final_prompt=full_prompt, pipeline_prompts=input_quiz_prompts),
    "INTELLECTUAL_GAME": PipelinePromptTemplate(final_prompt=full_prompt, pipeline_prompts=input_intellectual_game_prompts),
    "EXERCISES": PipelinePromptTemplate(final_prompt=full_prompt, pipeline_prompts=input_exercises_prompts),
    "LABORATORY": PipelinePromptTemplate(final_prompt=full_prompt, pipeline_prompts=input_laboratory_prompts),
    "SUMMARY": PipelinePromptTemplate(final_prompt=full_prompt, pipeline_prompts=input_summary_prompts),
    "SOR": PipelinePromptTemplate(final_prompt=full_prompt, pipeline_prompts=input_sor_prompts),
    "SOCH": PipelinePromptTemplate(final_prompt=full_prompt, pipeline_prompts=input_soch_prompts),
    "KSP": PipelinePromptTemplate(final_prompt=full_prompt, pipeline_prompts=input_ksp_prompts)
}