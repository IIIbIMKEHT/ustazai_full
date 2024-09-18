@extends('layout.default')
@push('css')
    <style>
        .btn {
            background-color: transparent!important;
            box-shadow: inherit!important;
            border-color: transparent!important;
        }
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

@section('content')
    <main
        x-data="{isShowChatInfo : !$store.breakpoints.mdAndDown , activeChat:{chatId:'chat-1',name:'Ustaz AI', avatar_url:'/assets/images/200x200.png'}}"
        x-effect="$store.breakpoints.mdAndDown === true && (isShowChatInfo = false)"
        class="main-content h-full chat-app mt-0 flex w-full flex-col"
        :class="isShowChatInfo && 'lg:mr-80'"
        @change-active-chat.window="activeChat=$event.detail"
    >
    <livewire:navbar />
        <div class="p-5">
            <div class="flex flex-col items-center justify-between space-y-4 py-5 sm:flex-row sm:space-y-0 lg:py-6">
                <div class="flex items-center space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h2 class="text-xl font-medium text-slate-700 line-clamp-1 dark:text-navy-50">
                        {{$material->type->title}}
                    </h2>
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

