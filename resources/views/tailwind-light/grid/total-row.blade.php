<tfoot>
    <tr class="bg-gray-50/50 font-semibold">
        @foreach($columns as $column)
            <td class="{{ $column['class'] }} text-gray-700">{!! $column['value'] !!}</td>
        @endforeach
    </tr>
</tfoot>
