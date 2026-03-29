@if($errors->hasBag('exception') && config('app.debug') == true)
    <?php $error = $errors->getBag('exception');?>
    <div class="glass-alert-warning mb-4 animate-fade-in">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <div class="flex-1 min-w-0">
            <h4 class="font-semibold text-sm">
                <span class="cursor-pointer border-b border-dashed border-current" title="{{ $error->first('type') }}" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">{{ class_basename($error->first('type')) }}</span>
                <span class="text-xs font-normal opacity-75">
                    in <span class="cursor-pointer border-b border-dashed border-current" title="{{ $error->first('file') }} line {{ $error->first('line') }}" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">{{ basename($error->first('file')) }} line {{ $error->first('line') }}</span>
                </span>
            </h4>
            <p class="text-sm mt-1">
                <a class="cursor-pointer hover:underline" onclick="document.querySelector('#super-admin-exception-trace').classList.toggle('hidden');">
                    <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    {!! $error->first('message') !!}
                </a>
            </p>
            <pre class="hidden mt-3 p-3 bg-black/5 rounded-lg text-xs overflow-auto max-h-64" id="super-admin-exception-trace">{!! nl2br($error->first('trace')) !!}</pre>
        </div>
        <button onclick="this.parentElement.remove()" class="flex-shrink-0 text-current opacity-50 hover:opacity-100 transition-opacity">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
@endif
