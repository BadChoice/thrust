@include('thrust::components.paginator', ["data" => $rows])
@if (count($rows) > 0)
    @include('thrust::components.tableDensity')
    <table class="thrust-table list striped">
        <thead class="sticky">
            <th class="hide-mobile">
                <input type="checkbox" onclick="toggleSelectAll(this)">
            </th>
            @if ($sortable)
                <th class="hide-mobile">  </th>
            @endif
            @foreach($fields as $field)
                <th class="{{ $field->rowClass }}">
                    @include('thrust::fieldHeader')
                </th>
            @endforeach
        </thead>

        <tbody class="@if($sortable) sortable @endif">
        @foreach ($rows as $row)
            <tr id="sort_{{$row->id}}">
                <td class="action hide-mobile"><input class='actionCheckbox' type="checkbox" name="selected[{{$row->id}}]" meta:id="{{$row->id}}"></td>
                @if ($sortable)
                    <td class="sort action hide-mobile"></td>
                @endif
                @foreach($fields as $field)
                    <td class="{{$field->rowClass}}">
                        @if (! $field->shouldHide($row, 'index') && $field->shouldShow($row, 'index') && $resource->can($field->policyAction, $row))
                            {!! $field->displayInIndex($row) !!}
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
    @include('thrust::components.paginator',["data" => $rows])
@else
    @include('thrust::components.noData')
@endif
