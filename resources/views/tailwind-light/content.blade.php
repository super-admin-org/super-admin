@extends('admin::index', ['header' => strip_tags($header)])

@section('content')

    @foreach ($css_files as $css_file)
        <link rel="stylesheet" href="{{ $css_file }}">
    @endforeach
    @isset($css)
        <style type="text/css">{{ $css }}</style>
    @endisset

    {{-- Page header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                {!! $header ?: trans('admin.title') !!}
            </h1>
            @if($description = ($description ?: trans('admin.description')))
                <p class="text-sm text-gray-500 mt-1">{!! $description !!}</p>
            @endif
        </div>

        @include('admin::partials.breadcrumb')
    </div>

    {{-- Alerts --}}
    @include('admin::partials.alerts')
    @include('admin::partials.exception')
    @include('admin::partials.toastr')

    {{-- Content --}}
    @if($_view_)
        @include($_view_['view'], $_view_['data'])
    @else
        {!! $_content_ !!}
    @endif

@endsection
