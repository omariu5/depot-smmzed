@extends('themes.'.$settings['theme'].'.layouts.master')
@php
    if (count($params) ) {
        foreach ($matches[1] as $key => $val) {
            ${$matches[1][$key]} = $params[$key];
        }
    }
@endphp
@section('title', $page->meta_title)

@section('seo')
    <meta content="{{$page->meta_description}}" name="description">
@endsection

@push('head')
    @if ($page->header)
        @php
            eval($page->header)
        @endphp
    @endif
@endpush
@section('content')

        @if ($page->content)
            <main id="main">
            @php
                eval($page->content)
            @endphp
            </main>
        @endif
@endsection

@push('script')
    @if ($page->footer)
        @php
            eval($page->footer)
        @endphp
    @endif
@endpush
