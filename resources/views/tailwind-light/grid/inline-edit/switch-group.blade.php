<tr>
    <td class="text-xs font-semibold text-gray-600 pr-3 whitespace-nowrap">{{ $label }}</td>
    <td>
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" class="{{ $class }}" {{ $checked }} data-key="{{ $key }}" style="position:absolute;opacity:0;width:0;height:0;">
            <div class="w-9 h-5 bg-gray-200 rounded-full relative transition-colors peer-checked:bg-primary-500" style="background:{{ $checked ? '#6366f1' : '' }}"></div>
        </label>
    </td>
</tr>

@include("admin::grid/inline-edit/switch-script")
