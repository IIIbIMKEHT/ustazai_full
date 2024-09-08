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
        <div class="p-5">
            <div class="flex flex-col items-center justify-between space-y-4 py-5 sm:flex-row sm:space-y-0 lg:py-6">
                <div class="flex items-center space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h2 class="text-xl font-medium text-slate-700 line-clamp-1 dark:text-navy-50">
                        {{$material->type->title_ru}}
                    </h2>
                </div>
                <div class="flex justify-center space-x-2">
                    <a href="{{route('my-materials', $material->subject_id)}}" type="submit"
                            class="btn !bg-slate-150 font-medium text-slate-800 hover:bg-slate-200 hover:shadow-lg hover:shadow-slate-200/50 focus:bg-slate-200 focus:shadow-lg focus:shadow-slate-200/50 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:hover:shadow-navy-450/50 dark:focus:bg-navy-450 dark:focus:shadow-navy-450/50 dark:active:bg-navy-450/90"
                    >
                        Назад
                    </a>
                </div>
            </div>

            <div>
                <div id="content">
                    {!! $material->content !!}
                </div>
            </div>
        </div>


        <livewire:right-side-bar />
    </main>


@endsection

