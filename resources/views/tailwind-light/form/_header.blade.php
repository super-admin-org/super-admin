@if(!empty($inline))
<div class="col-auto px-2">
@else
@if (!empty($showAsSection))
    <div class="px-5 pt-4">
        <h4 class="text-sm font-semibold text-gray-700 mb-2">{{ $label }}</h4>
    </div>
    <hr class="border-gray-200/50 mx-5">
@endif

<div class="{{$viewClass['form-group']}} flex flex-wrap items-start px-5 py-3 {!! !$errors->has($errorKey) ? '' : 'bg-red-50/30' !!}">
    <label for="{{$id}}" class="{{$viewClass['label']}} text-sm font-medium text-gray-600 pt-2 shrink-0">@if (empty($showAsSection)){{$label}}@endif</label>
    <div class="{{$viewClass['field']}} flex-1 min-w-0">
        @include('admin::form.error')
@endif
