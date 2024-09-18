<main
    x-data="{isShowChatInfo : !$store.breakpoints.mdAndDown ,activeChat:{chatId:'chat-1',name:'Ustaz AI',avatar_url:'/assets/images/200x200.png'}}"
    x-effect="$store.breakpoints.mdAndDown === true && (isShowChatInfo = false)"
    class="main-content h-100vh chat-app mt-0 flex w-full flex-col"
    :class="isShowChatInfo && 'lg:mr-80'"
    @change-active-chat.window="activeChat=$event.detail"
>
    <livewire:navbar />

    <div class="p-5">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3 lg:gap-6">
            @foreach($types as $type)
                <div class="card flex-row justify-between space-x-2 p-4 sm:p-5 cursor-pointer" wire:click="goToGenerate({{$type->id}})">
                    <div>
                        <div class="flex space-x-1">
                            <h4 class="text-base font-medium text-slate-700 line-clamp-1 dark:text-navy-100">
                                {{$type->title}}
                            </h4>
                        </div>
                        <p class="text-xs+ transition-colors duration-300 ease-in-out hover:text-slate-800 dark:hover:text-navy-50">
                            {{$type->description}}
                        </p>
                    </div>
                    <div class="avatar size-10">
                        <img class="mask is-squircle" src="{{$type->image}}" alt="avatar">
                        <div class="absolute right-0 -m-0.5 size-3 rounded-full border-2 border-white bg-primary dark:border-navy-700 dark:bg-accent"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <livewire:right-side-bar />
</main>
