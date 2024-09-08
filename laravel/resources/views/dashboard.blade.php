@extends('layout.default')
@push('css')
    <style>
        .btn {
            background-color: transparent!important;
            box-shadow: inherit!important;
            border-color: transparent!important;
        }
        .z-5000 {z-index: 5000!important;}
        #text-img img {
            width: 300px!important;
            height: 100%!important;
            border-radius: inherit!important;
        }
        #preview-img img {width: 100%; max-width: 320px; height: auto; border-radius: inherit!important;}
        #preview-img p {white-space: pre-line}
        mjx-container {text-align: left!important; display: inline!important;}
        #answers_math > li {margin: 20px 20px}
        .MJXc-display {display: inline!important; text-align: center; margin: 1em 0; padding: 0}
    </style>
@endpush

@section('content')
    <!-- Main Content Wrapper -->
    <livewire:dashboard />
@endsection

