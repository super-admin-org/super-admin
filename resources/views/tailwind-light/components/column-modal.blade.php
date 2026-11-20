<span data-sa-toggle="modal" data-sa-target="#grid-modal-{{ $name }}-backdrop" data-key="{{ $key }}">
   <a href="javascript:void(0)" class="text-primary-600 hover:text-primary-700 text-sm inline-flex items-center gap-1">
       <i class="icon-clone"></i>&nbsp;{{ $value }}
   </a>
</span>

<div class="glass-modal-backdrop hidden" id="grid-modal-{{ $name }}-backdrop"></div>
<div class="glass-modal grid-modal hidden" id="grid-modal-{{ $name }}" tabindex="-1" role="dialog">
    <div class="glass-modal-dialog glass-modal-lg">
        <div class="glass-modal-content">
            <div class="flex items-center justify-between p-5 border-b border-gray-200/30">
                <h4 class="text-base font-semibold text-gray-800">{{ $title }}</h4>
                <button type="button" class="text-gray-400 hover:text-gray-600" data-sa-dismiss="modal" data-sa-target="#grid-modal-{{ $name }}-backdrop">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-5 modal-body">
                {!! $html !!}
            </div>
        </div>
    </div>
</div>

@if($async)
<script>
    (function(){
        var modal = document.querySelector('#grid-modal-{{ $name }}');
        var modalBody = modal.querySelector('.modal-body');

        var load = function (url) {
            modalBody.innerHTML = "<div class='text-center py-10'><i class='icon-spinner icon-3x text-gray-400'></i></div>";
            axios.get(url)
            .then(function (response) {
                modalBody.innerHTML = response.data;
            }).catch(function (error) {
                console.log(error);
            });
        };

        document.querySelector('[data-sa-target="#grid-modal-{{ $name }}-backdrop"]').addEventListener('click', function(e){
            var key = this.dataset.key;
            load('{{ $url }}'+'&key='+key);
        });
    })();
</script>
@endif
