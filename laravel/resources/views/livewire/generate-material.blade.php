<main
    x-data="{isShowChatInfo : !$store.breakpoints.mdAndDown , activeChat:{chatId:'chat-1',name:'Ustaz AI', avatar_url:'/assets/images/200x200.png'}}"
    x-effect="$store.breakpoints.mdAndDown === true && (isShowChatInfo = false)"
    class="main-content h-full chat-app mt-0 flex w-full flex-col"
    :class="isShowChatInfo && 'lg:mr-80'"
    @change-active-chat.window="activeChat=$event.detail"
>
    <div
        class="chat-header relative z-10 flex h-[61px] w-full shrink-0 items-center justify-between border-b border-slate-150 bg-white px-[calc(var(--margin-x)-.5rem)] shadow-sm transition-[padding,width] duration-[.25s] dark:border-navy-700 dark:bg-navy-800"
    >
        <div class="flex items-center space-x-5">

            <div
                @click="isShowChatInfo = true"
                class="flex cursor-pointer items-center space-x-4 font-inter"
            >
                <div>
                    <p
                        class="font-medium text-slate-700 line-clamp-1 dark:text-navy-100"
                        x-text="activeChat.name"
                    ></p>
                    <a href="{{route('dashboard')}}"><p class="mt-0.5 text-xs">AI assistant</p></a>
                </div>
            </div>
        </div>

        <div class="-mr-1 flex items-center">
            <button @click="isShowChatInfo = !isShowChatInfo" :class="isShowChatInfo ? 'text-primary dark:text-accent-light' : 'text-slate-500 dark:text-navy-200'" class="btn hidden h-9 w-12 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25 sm:flex text-primary dark:text-accent-light">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5.5 w-5.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.25 21.167h5.5c4.584 0 6.417-1.834 6.417-6.417v-5.5c0-4.583-1.834-6.417-6.417-6.417h-5.5c-4.583 0-6.417 1.834-6.417 6.417v5.5c0 4.583 1.834 6.417 6.417 6.417ZM13.834 2.833v18.334"></path>
                </svg>
            </button>
        </div>
    </div>

    <form wire:submit.prevent="send">
        <div class="p-5">
            <div class="flex flex-col items-center justify-between space-y-4 py-5 sm:flex-row sm:space-y-0 lg:py-6">
                <div class="flex items-center space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h2 class="text-xl font-medium text-slate-700 line-clamp-1 dark:text-navy-50">
                        {{$type->title_ru}}
                    </h2>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">
                <div class="col-span-12 lg:col-span-8">
                    <div class="card">
                        <div class="tabs flex flex-col">
                            <div class="is-scrollbar-hidden mr-auto overflow-x-auto">
                                <div class="border-b-2 border-slate-150 dark:border-navy-500">
                                    <div class="tabs-list -mb-0.5 flex">
                                        <button type="button" class="btn h-14 shrink-0 space-x-2 rounded-none border-b-2 border-primary px-4 font-medium text-primary dark:border-accent dark:text-accent-light sm:px-5">
                                            <i class="fa-solid fa-layer-group text-base"></i>
                                            <span>Материал</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card px-4 py-4 sm:px-5">
                                <div class="spinner is-grow relative size-7" wire:loading wire:target="send">
                                    <span
                                        class="absolute inline-block h-full w-full rounded-full bg-primary opacity-75 dark:bg-accent"
                                    ></span>
                                    <span
                                        class="absolute inline-block h-full w-full rounded-full bg-primary opacity-75 dark:bg-accent"
                                    ></span>
                                </div>
                            <livewire:content-viewer />

                        </div>
                    </div>
                </div>
                <div class="col-span-12 lg:col-span-4">
                    <div class="card space-y-5 p-4 sm:p-5">
                        <label class="block">
                            <span>Выберите язык</span>
                            <select wire:model.blur="lang"
                                class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent"
                            >
                                <option value="1">Казахский</option>
                                <option value="0">Русский</option>
                            </select>
                        </label>
                        <label class="block">
                            <span>Выберите класс</span>
                            <select wire:model.change="class_id"
                                class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent"
                            >
                                <option value="">Выберите класс</option>
                                @foreach($classes as $id => $class)
                                    <option value="{{ $id }}">{{ $class }}</option>
                                @endforeach
                            </select>
                        </label>
                    
                        @if (!empty($subjects))
                            <label class="block">
                                <span>Выберите предмет</span>
                                <select wire:model="subject_id"
                                    class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent"
                                >
                                    <option value="">Выберите предмет</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->title_ru }}</option>
                                    @endforeach
                                </select>
                            </label>
                        @endif
                        @if($type->id == 1 || $type->id == 2)
                            <label class="block">
                                <span>Выберите количество тестов</span>
                                <select wire:model.blur="qty"
                                    class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent"
                                >
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="20">15</option>
                                </select>
                            </label>
                        @endif
                        @if($type->id == 9)
                            <label class="block">
                                <span>Выберите четверть</span>
                                <select wire:model.blur="term"
                                    class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent"
                                >
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                            </label>
                        @endif
                        @if($type->id != 9)
                            <label class="block">
                                <span>Тема урока:</span>
                                <input
                                    class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                    placeholder="введите тему урока"
                                    type="text"
                                    wire:model.blur="topic"
                                />
                                <div class="text-red-500">@error('topic') {{ $message }} @enderror</div>
                            </label>
                        @endif
                        <label class="block">

                            <input
                                class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                type="submit"
                                value="Отправить"
                            />
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <livewire:right-side-bar />
</main>

