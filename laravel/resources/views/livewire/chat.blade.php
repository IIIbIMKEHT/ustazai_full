<main
    x-data="{isShowChatInfo : !$store.breakpoints.mdAndDown ,activeChat:{chatId:'chat-1',name:'E-Zan',avatar_url:'/assets/images/200x200.png'}}"
    x-effect="$store.breakpoints.mdAndDown === true && (isShowChatInfo = false)"
    class="main-content h-100vh chat-app mt-0 flex w-full flex-col"
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
                    <p class="mt-0.5 text-xs">AI юрист</p>
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

    <div
        :class="$store.breakpoints.smAndUp && 'scrollbar-sm'"
        class="grow overflow-y-auto px-[calc(var(--margin-x)-.5rem)] py-5 transition-all duration-[.25s]"
    >
        <div
            x-show="activeChat.chatId === 'chat-1'"
            x-transition:enter="transition-all duration-500 easy-in-out"
            x-transition:enter-start="opacity-0 [transform:translate3d(0,1rem,0)]"
            x-transition:enter-end="opacity-100 [transform:translate3d(0,0,0)]"
            class="space-y-5"
        >

            @if($messages)

                @foreach($messages as $message)

                    @if($message->sender_type == 'user')
                        <div class="flex items-start justify-end space-x-2.5 sm:space-x-5">
                            <div class="flex flex-col items-end space-y-3.5">
                                <div class="ml-4 max-w-lg sm:ml-10">
                                    <div
                                        class="rounded-2xl rounded-tr-none bg-info/10 p-3 text-slate-700 shadow-sm dark:bg-accent dark:text-white"
                                    >
                                        {{$message->message}}
                                    </div>
                                    <p
                                        class="mt-1 ml-auto text-left text-xs text-slate-400 dark:text-navy-300"
                                    >
                                        {{$message->created_at->format('d.m.Y m:h')}}
                                    </p>
                                </div>
                            </div>
                            <div class="avatar">
                                <img
                                    class="rounded-full"
                                    src="{{asset('assets/images/200x200.png')}}"
                                    alt="avatar"
                                />
                            </div>
                        </div>
                    @endif
                    @if($message->sender_type == 'ai')
                            <div class="flex items-start space-x-2.5 sm:space-x-5">
                                <div class="avatar">
                                    <img
                                        class="rounded-full"
                                        src="{{asset('assets/images/200x200.png')}}"
                                        alt="avatar"
                                    />
                                </div>

                                <div class="flex flex-col items-start space-y-3.5">
                                    <div class="mr-4 max-w-lg sm:mr-10">
                                        <div
                                            class="rounded-2xl rounded-tl-none bg-white p-3 text-slate-700 shadow-sm dark:bg-navy-700 dark:text-navy-100"
                                        >
                                            {{$message->message}}
                                        </div>
                                        <p
                                            class="mt-1 ml-auto text-right text-xs text-slate-400 dark:text-navy-300"
                                        >
                                            {{$message->created_at->format('d.m.Y m:h')}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                    @endif
                @endforeach

            @endif
        </div>
        <div wire:loading class="flex justify-center items-center">
            <img src="{{asset('assets/images/loading.gif')}}" alt="">
        </div>
    </div>

    <div
        class="chat-footer relative flex h-12 w-full shrink-0 items-center justify-between border-t border-slate-150 bg-white px-[calc(var(--margin-x)-.25rem)] transition-[padding,width] duration-[.25s] dark:border-navy-600 dark:bg-navy-800"
    >
        <div class="-ml-1.5 flex flex-1 space-x-2">
            <input
                wire:model.blur="question"
                type="text"
                class="form-input h-9 w-full bg-transparent placeholder:text-slate-400/70"
                placeholder="Write the message"
            />
        </div>

        <div class="-mr-1.5 flex">
            <button
                wire:click="sendQuestion()"
                wire:loading.attr="disabled"
                class="btn h-9 w-12 shrink-0 rounded-full p-0 text-primary hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:text-accent-light dark:hover:bg-accent-light/20 dark:focus:bg-accent-light/20 dark:active:bg-accent-light/25"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5.5 w-5.5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m9.813 5.146 9.027 3.99c4.05 1.79 4.05 4.718 0 6.508l-9.027 3.99c-6.074 2.686-8.553.485-5.515-4.876l.917-1.613c.232-.41.232-1.09 0-1.5l-.917-1.623C1.26 4.66 3.749 2.46 9.813 5.146ZM6.094 12.389h7.341"
                    />
                </svg>
            </button>
        </div>
    </div>

    <template x-teleport="#x-teleport-target">
        <div
            x-data="{
            get showDrawer() {return $data.isShowChatInfo;},
            set showDrawer(val) {$data.isShowChatInfo = val;},
          }"
            x-show="showDrawer"
            @keydown.window.escape="showDrawer = false"
        >
            <div
                class="fixed inset-0 z-[100] bg-slate-900/60 transition-opacity duration-200"
                @click="showDrawer = false"
                x-show="showDrawer && $store.breakpoints.mdAndDown"
                x-transition:enter="ease-out"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            ></div>
            <div class="fixed right-0 top-0 z-[101] h-full w-full sm:w-80">
                <div
                    class="flex h-full w-full flex-col border-l border-slate-150 bg-white transition-transform duration-200 dark:border-navy-600 dark:bg-navy-750"
                    x-show="showDrawer"
                    x-transition:enter="ease-out transform-gpu"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="ease-in transform-gpu"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                >
                    <div class="flex h-[60px] items-center justify-between p-4">
                        <button wire:click="generateNewChat()" class="btn h-8 w-12 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24" class="icon-xl-heavy"><path d="M15.673 3.913a3.121 3.121 0 1 1 4.414 4.414l-5.937 5.937a5 5 0 0 1-2.828 1.415l-2.18.31a1 1 0 0 1-1.132-1.13l.311-2.18A5 5 0 0 1 9.736 9.85zm3 1.414a1.12 1.12 0 0 0-1.586 0l-5.937 5.937a3 3 0 0 0-.849 1.697l-.123.86.86-.122a3 3 0 0 0 1.698-.849l5.937-5.937a1.12 1.12 0 0 0 0-1.586M11 4A1 1 0 0 1 10 5c-.998 0-1.702.008-2.253.06-.54.052-.862.141-1.109.267a3 3 0 0 0-1.311 1.311c-.134.263-.226.611-.276 1.216C5.001 8.471 5 9.264 5 10.4v3.2c0 1.137 0 1.929.051 2.546.05.605.142.953.276 1.216a3 3 0 0 0 1.311 1.311c.263.134.611.226 1.216.276.617.05 1.41.051 2.546.051h3.2c1.137 0 1.929 0 2.546-.051.605-.05.953-.142 1.216-.276a3 3 0 0 0 1.311-1.311c.126-.247.215-.569.266-1.108.053-.552.06-1.256.06-2.255a1 1 0 1 1 2 .002c0 .978-.006 1.78-.069 2.442-.064.673-.192 1.27-.475 1.827a5 5 0 0 1-2.185 2.185c-.592.302-1.232.428-1.961.487C15.6 21 14.727 21 13.643 21h-3.286c-1.084 0-1.958 0-2.666-.058-.728-.06-1.369-.185-1.96-.487a5 5 0 0 1-2.186-2.185c-.302-.592-.428-1.233-.487-1.961C3 15.6 3 14.727 3 13.643v-3.286c0-1.084 0-1.958.058-2.666.06-.729.185-1.369.487-1.961A5 5 0 0 1 5.73 3.545c.556-.284 1.154-.411 1.827-.475C8.22 3.007 9.021 3 10 3A1 1 0 0 1 11 4"></path></svg>
                        </button>
                        <div class="-mr-1.5 flex space-x-1">
                            <a href="{{route('pdf-load')}}"
                                class="btn h-8 w-12 rounded-full p-0 hover:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:active:bg-navy-300/25"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" class="icon-xl-heavy"><path fill="currentColor" fill-rule="evenodd" d="M11.293 3.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1-1.414 1.414L13 6.414V15a1 1 0 1 1-2 0V6.414L8.707 8.707a1 1 0 0 1-1.414-1.414zM4 14a1 1 0 0 1 1 1v3a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-3a1 1 0 1 1 2 0v3a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-3a1 1 0 0 1 1-1" clip-rule="evenodd"></path></svg>
                            </a>
                            <button
                                @click="$store.global.isDarkModeEnabled = !$store.global.isDarkModeEnabled"
                                class="btn h-8 w-12 rounded-full p-0 hover:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:active:bg-navy-300/25"
                            >
                                <svg
                                    x-show="$store.global.isDarkModeEnabled"
                                    x-transition:enter="transition-transform duration-200 ease-out absolute origin-top"
                                    x-transition:enter-start="scale-75"
                                    x-transition:enter-end="scale-100 static"
                                    class="h-6 w-6 text-amber-400"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M11.75 3.412a.818.818 0 01-.07.917 6.332 6.332 0 00-1.4 3.971c0 3.564 2.98 6.494 6.706 6.494a6.86 6.86 0 002.856-.617.818.818 0 011.1 1.047C19.593 18.614 16.218 21 12.283 21 7.18 21 3 16.973 3 11.956c0-4.563 3.46-8.31 7.925-8.948a.818.818 0 01.826.404z"
                                    />
                                </svg>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    x-show="!$store.global.isDarkModeEnabled"
                                    x-transition:enter="transition-transform duration-200 ease-out absolute origin-top"
                                    x-transition:enter-start="scale-75"
                                    x-transition:enter-end="scale-100 static"
                                    class="h-6 w-6 text-amber-400"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>
                            <a href="{{route('logout')}}" @click="showDrawer=false" class="btn h-8 w-12 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </a>
                            <button @click="showDrawer=false" class="btn h-8 w-12 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="my-5 flex flex-col items-center">
                        <p>История чатов</p>
                    </div>
                    <div class="mx-2 overflow-auto">
                        <div class="flex flex-col space-y-3.5">
                            @foreach($this->chats as $chat)

                                <div class="flex items-center justify-between space-x-3 cursor-pointer" wire:click="getMessagesByChatId({{$chat->id}})">
                                    <div class="w-full flex items-center">
                                        <div class="mask is-squircle flex h-8 w-8 items-center justify-center bg-info text-white">
                                            {{$loop->iteration}}
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-medium text-slate-700 dark:text-navy-100">
                                                @if($chat->messages->count())
                                                    {{Str::limit((string) (collect($chat->messages)->last())->message, 30)}}
                                                @else
                                                    New chat
                                                @endif
                                                {{--                                            {{$chat->messages ? Str::limit((string) (collect($chat->messages)->last())->message, 30) : []}}--}}
                                            </p>
                                        </div>
                                    </div>
                                    <div wire:click="removeChat({{$chat->id}})">
                                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0,0,300,150">
                                            <g fill="#fc0000" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(8,8)"><path d="M15,4c-0.52344,0 -1.05859,0.18359 -1.4375,0.5625c-0.37891,0.37891 -0.5625,0.91406 -0.5625,1.4375v1h-6v2h1v16c0,1.64453 1.35547,3 3,3h12c1.64453,0 3,-1.35547 3,-3v-16h1v-2h-6v-1c0,-0.52344 -0.18359,-1.05859 -0.5625,-1.4375c-0.37891,-0.37891 -0.91406,-0.5625 -1.4375,-0.5625zM15,6h4v1h-4zM10,9h14v16c0,0.55469 -0.44531,1 -1,1h-12c-0.55469,0 -1,-0.44531 -1,-1zM12,12v11h2v-11zM16,12v11h2v-11zM20,12v11h2v-11z"></path></g></g>
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</main>
