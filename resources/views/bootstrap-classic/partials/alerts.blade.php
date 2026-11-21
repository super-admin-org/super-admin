@if($error = session()->get('error'))
    @php($flash = admin_flash_message($error))
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">×</button>
        @if($flash['title'] !== '')
            <h4><i class="icon icon-ban"></i>{{ $flash['title'] }}</h4>
        @endif
        <p>{!! $flash['message'] !!}</p>
    </div>
@elseif ($errors = session()->get('errors'))
    @if (is_object($errors) && method_exists($errors, 'hasBag') && $errors->hasBag('error'))
      <div class="alert alert-danger alert-dismissable">

        <button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">×</button>
        @foreach($errors->getBag("error")->toArray() as $message)
            <p>{!!  \Illuminate\Support\Arr::get($message, 0) !!}</p>
        @endforeach
      </div>
    @elseif (is_string($errors))
      <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">×</button>
        <p>{!! $errors !!}</p>
      </div>
    @endif
@endif

@if($success = session()->get('success'))
    @php($flash = admin_flash_message($success))
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">×</button>
        @if($flash['title'] !== '')
            <h4><i class="icon icon-check"></i>{{ $flash['title'] }}</h4>
        @endif
        <p>{!! $flash['message'] !!}</p>
    </div>
@endif

@if($info = session()->get('info'))
    @php($flash = admin_flash_message($info))
    <div class="alert alert-info alert-dismissable">
        <button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">×</button>
        @if($flash['title'] !== '')
            <h4><i class="icon icon-info"></i>{{ $flash['title'] }}</h4>
        @endif
        <p>{!! $flash['message'] !!}</p>
    </div>
@endif

@if($warning = session()->get('warning'))
    @php($flash = admin_flash_message($warning))
    <div class="alert alert-warning alert-dismissable">
        <button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">×</button>
        @if($flash['title'] !== '')
            <h4><i class="icon icon-exclamation-triangle"></i>{{ $flash['title'] }}</h4>
        @endif
        <p>{!! $flash['message'] !!}</p>
    </div>
@endif
