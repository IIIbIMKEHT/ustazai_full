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
        <div class="mx-3"></div>
        <a id="export-word"
                class="btn bg-slate-150 font-medium text-slate-800 hover:bg-slate-200 focus:bg-slate-200 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90"
        >
            {{ __('export_button') }}
        </a>
    </div>
</div>

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("livewire:init", () => {
            Livewire.on('start-stream', (event) => {
                const { class_level, subject, topic, is_kk, qty, term, task_type, token, api_url } = event.detail;

                const eventSource = new EventSource(`${api_url}/stream_material/?class_level=${class_level}&subject=${subject}&topic=${topic}&is_kk=${is_kk}&qty=${qty}&term=${term}&task_type=${task_type}&token=${token}`);
                const generateDocBtn = document.getElementById("export-word");
                const streamOutput = document.getElementById('content');
                let downloadLink = '';
                let collectedHTML = '';

                // Функция для декодирования HTML-сущностей
                function decodeHTMLEntities(text) {
                    const textArea = document.createElement('textarea');
                    textArea.innerHTML = text;
                    return textArea.value;
                }

                // Получаем данные и добавляем их как HTML
                eventSource.onmessage = function(event) {
                    if (event.data == "[DONE]") {
                        console.log("Stream ended")
                        generateDocBtn.style.display = 'flex';

                        eventSource.close(); // Закрываем стрим
                        // Обновляем содержимое элемента
                        streamOutput.innerHTML = collectedHTML;
                        generate_doc().then((val) => {
                            downloadLink = val
                            Livewire.dispatch('save-data', {content: streamOutput.innerHTML, link: val});
                        });

                    } else {
                        const newHTML = decodeHTMLEntities(event.data);
                        collectedHTML += newHTML;
                        // Обновляем содержимое элемента
                        streamOutput.innerHTML = collectedHTML;
                        streamOutput.scrollTop = streamOutput.scrollHeight;  // Прокрутка к концу
                    }
                };

                async function generate_doc() {
                    const response = await fetch(`${api_url}/generate_doc/`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify({ html_content: streamOutput.innerHTML }),
                        });

                        const result = await response.json();

                        if (result.download_link) {
                            return api_url + result.download_link;
                        }
                }
                // Отправляем HTML контент на сервер для генерации документа
                generateDocBtn.addEventListener('click', async function() {
                    window.location.href = downloadLink;
                });

                // Когда поток завершается
                eventSource.onclose = function() {
                    console.log("Stream завершен и контент преобразован в HTML.");
                };

                eventSource.onerror = function() {
                    eventSource.close();
                };
            })

            Livewire.on('clear-content', (event) => {
                const streamOutput = document.getElementById('content');
                document.getElementById('export-word').style.display = 'none';
                streamOutput.innerHTML = '';
            })

            Livewire.on('alert-message', (event) => {
                Swal.fire({
                    icon: "error",
                    title: "Ваш лимит попыток исчерпан",
                    html: "Для получения дополнительной информации, пожалуйста, свяжитесь с нами по телефону <br> +7 (707) 500-17-10",
                    footer: '<a href="mailto:kazitech2023@gmail.com">или по электронной почте kazitech2023@gmail.com</a>'
                });
            })
        });

    </script>
@endpush
