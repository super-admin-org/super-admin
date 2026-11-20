<tr>
    <td class="text-xs font-semibold text-gray-600 pr-3 whitespace-nowrap">{{ $label }}</td>
    <td>
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" class="sr-only peer {{ $class }}" {{ $checked }} data-key="{{ $key }}">
            <div class="w-9 h-5 bg-gray-200 rounded-full peer-focus:outline-none peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary-500"></div>
        </label>
    </td>
</tr>

@include("admin::grid/inline-edit/switch-script")
