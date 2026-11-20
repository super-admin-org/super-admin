<form action="{!! $action !!}" pjax-container style="display: inline-flex;vertical-align:middle;">
    <div class="flex">
        <input type="text" name="{{ $key }}" class="glass-input rounded-r-none text-sm" value="{{ $value }}" placeholder="{{ $placeholder }}">
        <button type="submit" class="glass-btn-secondary rounded-l-none px-3 border-l-0">
            <i class="icon-search text-sm"></i>
        </button>
    </div>
</form>
