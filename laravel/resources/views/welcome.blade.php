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
    <!-- Main Content Wrapper -->
    <livewire:chat />
@endsection
