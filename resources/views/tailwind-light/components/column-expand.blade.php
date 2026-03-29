<div>
    <span class="{{ $elementClass }}" data-inserted="0" data-key="{{ $key }}" data-name="{{ $name }}"
          data-sa-toggle="collapse" data-sa-target="#grid-collapse-{{ $name }}">
        <a href="javascript:void(0)" class="text-primary-600 hover:text-primary-700 text-sm inline-flex items-center gap-1">
            <i class="icon-angle-double-down"></i>&nbsp;{{ $value }}
        </a>
    </span>
    <template class="grid-expand-{{ $name }}">
        <tr class="bg-gray-50/30">
            <td colspan='100%' class="p-0 border-0">
                <div id="grid-collapse-{{ $name }}" class="hidden">
                    <div class="p-4 html">
                        @if($html)
                            {!! $html !!}
                        @else
                            <div class="loading text-center py-5">
                                <i class="icon-spinner fa-pulse fa-3x fa-fw text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </td>
        </tr>
    </template>
</div>

<script>
    var expand = document.querySelectorAll('.{{ $elementClass }}');

    expand.forEach(el=>{
        el.addEventListener('click', function (e) {
            var name = el.dataset.name;

            if (el.dataset.inserted == '0') {
                var row = e.target.closest('tr');
                var key = el.dataset.key;
                var new_row = document.querySelector('template.grid-expand-'+name).content.cloneNode(true);
                row.after(new_row);
                var target = document.querySelector("#grid-collapse-"+name);
                target.classList.remove('hidden');

                @if($async)
                    let url = '{{ $url }}'+'&key='+key;
                    axios.get(url)
                    .then(function (response) {
                        target.querySelector('.html').innerHTML = response.data;
                    }).catch(function (error) {
                        console.log(error);
                    });
                @endif

                el.dataset.inserted = 1;
            } else {
                var target = document.querySelector("#grid-collapse-"+name);
                target.classList.toggle('hidden');
            }

            var i = el.querySelector("i");
            i.classList.toggle("icon-angle-double-down");
            i.classList.toggle("icon-angle-double-up");
        });
    });
    @if ($expand)
        expand[0] && expand[0].click();
    @endif
</script>
