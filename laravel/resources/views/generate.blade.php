@extends('layout.default')
@push('css')
    <style>
        .btn {
            background-color: transparent!important;
            box-shadow: inherit!important;
            border-color: transparent!important;
        }
        .progress-container {
            width: 100%;
            background-color: #f3f3f3;
            border-radius: 5px;
            margin-top: 10px;
        }

        .progress-bar {
            text-align: center;
            color: white;
            border-radius: 5px;
            height: 20px;
            line-height: 20px;
            transition: width 0.4s ease;
        }
    </style>
@endpush

@section('content')
    <!-- Main Content Wrapper -->
    <livewire:generate-material :type="$type"/>
@endsection

