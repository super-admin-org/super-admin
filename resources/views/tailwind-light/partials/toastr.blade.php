@if(Session::has('toastr'))
    @php
        $toastr     = admin_flash_to_array(Session::get('toastr'));
        $type       = \Illuminate\Support\Arr::get($toastr, 'type.0', 'success');
        $message    = \Illuminate\Support\Arr::get($toastr, 'message.0', '');
        $options    = json_encode(\Illuminate\Support\Arr::get($toastr, 'options', []) ?: []);
    @endphp
    <script>
        (function () {
            admin.toastr.{{$type}}('{!!  $message  !!}', {!! $options !!});
        }());
    </script>
@endif
