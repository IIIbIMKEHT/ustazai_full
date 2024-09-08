@extends('layout.default')
@section('content')
    <main class="grid w-full grow grid-cols-1 place-items-center">
        <div class="w-full max-w-[26rem] p-4 sm:px-5 text-center">
            <livewire:load-pdf />
        </div>
    </main>

@endsection
