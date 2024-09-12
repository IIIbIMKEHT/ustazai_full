@push('css')
    <style>
        mjx-container {text-align: left!important; display: inline!important;}
        #export-word {display: none; background-color: #4b5563!important; color: white!important;}
        #content {margin: 20px 0;}
        #content h1 {
            margin: 20px 0;
            font-size: 24px;
            font-weight: bold;
        }
        #content h2 {
            margin: 10px 0;
            font-size: 18px;
            font-weight: bold;
        }
        #content h3 {
            margin: 5px 0;
            font-weight: bold;
        }
        #content ul {
            list-style: inside;
            margin: 10px 0;
        }
        #content p {
            margin: 10px 0;
        }
        table, tbody, tr, th, td {
            border: 1px black solid;
        }
        table th, td {
            padding: 5px 10px;
        }
    </style>
@endpush
<div>
    <div id="content">

    </div>
    <div class="container flex " id="action-buttons">
{{--        <a target="_blank" href="#" id="export-pdf"--}}
{{--                class="btn bg-slate-150 font-medium text-slate-800 hover:bg-slate-200 focus:bg-slate-200 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90"--}}
{{--        >--}}
{{--            Экспорт в PDF--}}
{{--        </a>--}}
        <div class="mx-3"></div>
        <a target="_blank" href="#" id="export-word"
                class="btn bg-slate-150 font-medium text-slate-800 hover:bg-slate-200 focus:bg-slate-200 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90"
        >
            Экспорт в WORD
        </a>
    </div>
</div>

@push('js')
    <script>
        document.addEventListener("livewire:init", () => {
            Livewire.on('run-formatter', (data) => {
                const contentDiv = document.getElementById('content');
                console.log(data)

                if (contentDiv) {
                    contentDiv.innerHTML = data.content; // Добавляем содержимое
                    // document.getElementById('export-pdf').style.display = 'block';
                    document.getElementById('export-word').style.display = 'flex';

                    document.getElementById('export-word').addEventListener('click', function(event) {
                        event.preventDefault();  // Предотвращаем переход по текущей пустой ссылке
                        // Пример динамической ссылки
                        const wordFileLink = 'http://fastapi_app:5000' + data.wordLink;
                        // Вставляем ссылку в элемент <a>
                        this.setAttribute('href', wordFileLink);
                        // Перенаправляем пользователя по новой ссылке
                        window.location.href = wordFileLink;
                    });
                    // document.getElementById('export-pdf').addEventListener('click', function(event) {
                    //     event.preventDefault();  // Предотвращаем переход по текущей пустой ссылке
                    //     // Пример динамической ссылки
                    //     const pdfFileLink = 'http://localhost:9000' + data.pdfLink;
                    //     // Вставляем ссылку в элемент <a>
                    //     this.setAttribute('href', pdfFileLink);
                    //     // Перенаправляем пользователя по новой ссылке
                    //     window.location.href = pdfFileLink;
                    // });


                } else {
                    console.error('Content DIV not found!');
                }
            });
        });

    </script>
@endpush
