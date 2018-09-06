
<table class="list">
    <thead>
        @foreach($fields as $field)
            <th> {{ $field->getTitle() }}</th>
        @endforeach
        <td colspan="2"></td>
    </thead>

    @foreach ($rows as $row)
        <tr>
            @foreach($fields as $field)
                <td> {{ $field->displayInIndex($row) }}</td>
            @endforeach

            <td class="action"> <a class='showPopup edit' href="{{route('thrust.edit', [$resource, $row->id]) }}"> </a> </td>
            <td class="action"> <a class="delete-resource" data-delete="confirm resource" href="{{route('thrust.delete', [$resource, $row->id])}}"></a></td>
        </tr>
    @endforeach
</table>
