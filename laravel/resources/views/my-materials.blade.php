@extends('layout.default')
@push('css')
    <style>
        .btn {
            background-color: transparent!important;
            box-shadow: inherit!important;
            border-color: transparent!important;
        }
    </style>
@endpush

@section('content')
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

        <div class="container p-5">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:gap-6">
                @foreach($materials as $material)
                    <div class="card items-center justify-between lg:flex-row">
                        <div class="flex flex-col items-center p-4 text-center sm:p-5 lg:flex-row lg:space-x-4 lg:text-left">
                            <div class="mt-2 lg:mt-0">
                                <div class="flex items-center justify-center space-x-1">
                                    <h4 class="text-base font-medium text-slate-700 line-clamp-1 dark:text-navy-100">
                                        {{$material->title}}
                                    </h4>
                                    <a href="javascript:void (0)" class="btn hidden h-6 rounded-full px-2 text-xs font-medium text-primary hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:text-accent-light dark:hover:bg-accent-light/20 dark:focus:bg-accent-light/20 dark:active:bg-accent-light/25 lg:inline-flex">
                                        {{$material->type->title_ru}}
                                    </a>
                                </div>
                                <p class="text-xs+">{{$material->created_at->format('d.m.Y')}}</p>
                            </div>
                        </div>
                        <div x-data="usePopper({placement:'bottom-end',offset:4})" @click.outside="isShowPopper &amp;&amp; (isShowPopper = false)" class="absolute top-0 right-0 m-2 lg:static">
                            <button x-ref="popperRef" @click="isShowPopper = !isShowPopper" class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>

                            <div x-ref="popperRoot" class="popper-root" :class="isShowPopper &amp;&amp; 'show'" style="position: fixed; inset: 0px 0px auto auto; margin: 0px; transform: translate(-940px, 208px);" data-popper-placement="bottom-end">
                                <div class="popper-box rounded-md border border-slate-150 bg-white py-1.5 font-inter dark:border-navy-500 dark:bg-navy-700">
                                    <ul>
                                        <li>
                                            <a href="{{route('show-material', $material->id)}}" class="flex h-8 items-center px-3 pr-8 font-medium tracking-wide outline-none transition-all hover:bg-slate-100 hover:text-slate-800 focus:bg-slate-100 focus:text-slate-800 dark:hover:bg-navy-600 dark:hover:text-navy-100 dark:focus:bg-navy-600 dark:focus:text-navy-100">
                                                Посмотреть
                                            </a>
                                        </li>
                                        <li>
                                            <a  href="{{$material->word_link}}" class="flex h-8 items-center px-3 pr-8 font-medium tracking-wide outline-none transition-all hover:bg-slate-100 hover:text-slate-800 focus:bg-slate-100 focus:text-slate-800 dark:hover:bg-navy-600 dark:hover:text-navy-100 dark:focus:bg-navy-600 dark:focus:text-navy-100">
                                                Экспорт в WORD
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{route('delete-material', $material->id)}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex h-8 items-center px-3 pr-8 font-medium tracking-wide outline-none transition-all hover:bg-slate-100 hover:text-slate-800 focus:bg-slate-100 focus:text-slate-800 dark:hover:bg-navy-600 dark:hover:text-navy-100 dark:focus:bg-navy-600 dark:focus:text-navy-100">
                                                    Удалить
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="my-3">
                {!! $materials->links() !!}
            </div>
        </div>

        <livewire:right-side-bar />
    </main>


@endsection

