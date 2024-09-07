<div>
    <form wire:submit.prevent="save">
        <div style="margin-bottom: 30px">
            <input
                wire:model.blur="file"
                type="file"
                accept="application/pdf"
            />
        </div>
        <button type="submit" wire:loading.attr="disabled">
            <label class="btn relative bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-cloud-arrow-up text-base"></i>
                    <span>Отправить</span>
                </div>
            </label>
        </button>
        <div wire:loading>
            Loading...
        </div>
    </form>

    <div style="margin-top: 50px">
        <a href="{{route('chat')}}">Вернуться на главную страницу</a>
    </div>
</div>
