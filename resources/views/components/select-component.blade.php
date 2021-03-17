<select name="{{$name}}" id="{{$id}}" data-parsley-errors-container="{{$errorContainer}}" class="select2 form-control">
    <option value="">{{isset($placeholder) ? $placeholder : "Pilih Salah Satu"}}</option>
    @foreach($options as $option)
        <option value="{{ $option->{$textValue} }}" {{ $option->{$textValue} == $selected ? 'selected' : '' }}>{{ $option->{$textLabel} }}</option>
    @endforeach
</select>
