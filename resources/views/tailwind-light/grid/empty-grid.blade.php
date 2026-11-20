<tr>
    <td colspan="{{ $grid->visibleColumns()->count() }}" class="text-center py-16">
        <svg viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="80" height="80" class="mx-auto mb-4 opacity-20">
            <path d="M927 679.9l-53.9-234.2c-2.8-9.9-4.9-20-6.9-30.1-3.7-18.2-19.9-31.9-39.2-31.9H197c-19.9 0-36.4 14.5-39.5 33.5-1 6.3-2.2 12.5-3.9 18.7L97 679.9v239.6c0 22.1 17.9 40 40 40h750c22.1 0 40-17.9 40-40V679.9z m-315-40c0 55.2-44.8 100-100 100s-100-44.8-100-100H149.6l42.5-193.3c2.4-8.5 3.8-16.7 4.8-22.9h630c2.2 11 4.5 21.8 7.6 32.7l39.8 183.5H612z" fill="currentColor"/>
        </svg>
        <p class="text-gray-400 text-sm">{{ trans('admin.no_records') ?? 'No records found' }}</p>
    </td>
</tr>
