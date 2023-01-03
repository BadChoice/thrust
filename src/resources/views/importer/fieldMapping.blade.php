<div class="flex-row space-y-4">
    <select name="mapping[{{$field}}]" placeholder="{{ __('thrust::pickAColumn') }}" @if ($required) required @endif >
        <option value="">--</option>
        @foreach($csvFields as $csvField)
            <option value="{{$csvField}}">{{$csvField}}</option>
        @endforeach
    </select>
    @icon(arrow-right)
    <div class="inline bg-gray-100 rounded-lg px-2 py-2 w-48" >
        {{ $title }} @if ($required) * @endif
    </div>
</div>