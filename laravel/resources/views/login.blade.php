@extends('layout.default')
@push('css')
    <style>
        .btn {
            background-color: transparent!important;
        }
    </style>
@endpush
@section('content')
    <main class="grid w-full grow grid-cols-1 place-items-center">
        <div class="w-full max-w-[26rem] p-4 sm:px-5">
            <div class="text-center">
                <img
                    class="mx-auto h-16 w-16"
                    src="{{asset('assets/images/app-logo.svg')}}"
                    alt="logo"
                />
                <div class="mt-4">
                    <h2
                        class="text-2xl font-semibold text-slate-600 dark:text-navy-100"
                    >
                        Welcome Back
                    </h2>
                    <p class="text-slate-400 dark:text-navy-300">
                        Please sign in to continue
                    </p>
                </div>
            </div>
            <div class="flex space-x-4 my-5">
                <a href="{{route('auth.google')}}"
                    class="btn w-full space-x-3 border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90"
                >
                    <div class="bg-center bg-cover w-12 h-8" style='background-image: url("{{asset('assets/images/google-logo.png')}}")'></div>
                    <span>Google</span>
                </a>
            </div>
            <div
                class="flex justify-center text-xs text-slate-400 dark:text-navy-300"
            >
                <a href="#">Privacy Notice</a>
                <div class="mx-3 my-1 w-px bg-slate-200 dark:bg-navy-500"></div>
                <a href="#">Term of service</a>
            </div>
        </div>
    </main>
@endsection
