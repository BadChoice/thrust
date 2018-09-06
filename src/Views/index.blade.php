
<table>
    <thead>
        @foreach($fields as $field)
            <th> {{ $field->getTitle() }}</th>
        @endforeach
    </thead>

    @foreach ($rows as $row)
        <tr>
            @foreach($fields as $field)
                <td> {{ $field->displayInIndex($row) }}</td>
            @endforeach
        </tr>
    @endforeach
</table>
