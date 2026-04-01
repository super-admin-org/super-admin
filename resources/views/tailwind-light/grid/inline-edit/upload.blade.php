<a href='javascript:void(0);' class='text-gray-500 hover:text-primary-600 transition-colors inline-upload-trigger' data-target="{{ $target }}">
    <i class="icon-upload"></i>&nbsp;{!! $value !!}
</a>
<div class="hidden">
  <input type="file" class="inline-upload" id="{{ $target }}" data-key="{{ $key }}" {{ $multiple ? 'multiple' : '' }}/>
</div>

<script>
document.querySelectorAll('.inline-upload-trigger').forEach(function(el) {
    el.addEventListener('click', function() {
        document.getElementById(this.dataset.target).click();
    });
});

document.querySelectorAll('input.inline-upload').forEach(function(el) {
    el.addEventListener('change', function(event) {

        var formData = new FormData();

        @if ($multiple)
            Array.from(event.target.files).forEach(function (file) {
                formData.append("{{ $name }}[]", file);
            });
        @else
        formData.append("{{ $name }}", event.target.files[0]);
        @endif
        formData.append('_token', LA.token);
        formData.append('_method', 'PUT');

        admin.ajax.request("{{ $resource }}/" + el.dataset.key, {
            method: 'post',
            data: formData,
            processData: false,
            contentType: false,
        }, function(data) {
            admin.toastr.success(data.message || data.data);
            admin.ajax.reload();
        });
    });
});
</script>

