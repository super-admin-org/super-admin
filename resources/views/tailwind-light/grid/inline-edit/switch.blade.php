<label class="relative inline-flex items-center cursor-pointer">
  <input type="checkbox" class="{{ $class }}" {{ $checked }} data-key="{{ $key }}" style="position:absolute;opacity:0;width:0;height:0;">
  <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary-500"></div>
</label>

@include("admin::grid/inline-edit/switch-script")
