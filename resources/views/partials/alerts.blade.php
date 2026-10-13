@if($error = session()->get('error'))
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon icon-ban"></i>{{ \Illuminate\Support\Arr::get(admin_flash_to_array($error), 'title.0') }}</h4>
        <p>{!!  \Illuminate\Support\Arr::get(admin_flash_to_array($error), 'message.0') !!}</p>
    </div>
@elseif ($errors = session()->get('errors'))
    @if ($errors->hasBag('error'))
      <div class="alert alert-danger alert-dismissable">

        <button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">×</button>
        @foreach($errors->getBag("error")->toArray() as $message)
            <p>{!!  \Illuminate\Support\Arr::get($message, 0) !!}</p>
        @endforeach
      </div>
    @endif
@endif

@if($success = session()->get('success'))
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon icon-check"></i>{{ \Illuminate\Support\Arr::get(admin_flash_to_array($success), 'title.0') }}</h4>
        <p>{!!  \Illuminate\Support\Arr::get(admin_flash_to_array($success), 'message.0') !!}</p>
    </div>
@endif

@if($info = session()->get('info'))
    <div class="alert alert-info alert-dismissable">
        <button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon icon-info"></i>{{ \Illuminate\Support\Arr::get(admin_flash_to_array($info), 'title.0') }}</h4>
        <p>{!!  \Illuminate\Support\Arr::get(admin_flash_to_array($info), 'message.0') !!}</p>
    </div>
@endif

@if($warning = session()->get('warning'))
    <div class="alert alert-warning alert-dismissable">
        <button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon icon-exclamation-triangle"></i>{{ \Illuminate\Support\Arr::get(admin_flash_to_array($warning), 'title.0') }}</h4>
        <p>{!!  \Illuminate\Support\Arr::get(admin_flash_to_array($warning), 'message.0') !!}</p>
    </div>
@endif