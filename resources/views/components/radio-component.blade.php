<div class="form-group">
    <div class="form-check form-check-inline" data-parsley-errors-container="{{$errorContainer}}">
        @foreach($options as $key => $option)
            <input class="form-check-input" {{ $option->{$textValue} == $selected ? 'checked' : '' }} type="radio" name="{{$name}}" id="{{$id}}{{$key+1}}" value="{{ $option->{$textValue} }}">
            <label class="form-check-label mr-3" for="{{$id}}{{$key+1}}">{{ $option->{$textLabel} }}</label>
        @endforeach
    </div>
</div>
