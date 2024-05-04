@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $banner   = getContent('banner.content', true);
        $elements = getContent('banner.element');
    @endphp


    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/swiper.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/swiper.css') }}">
@endpush
@php app()->offsetSet('swiper_assets',true) @endphp
