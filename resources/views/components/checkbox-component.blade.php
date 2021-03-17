<div class="form-group">
    <div class="form-check form-check-inline" data-parsley-errors-container="{{$errorContainer}}">
        @foreach($options as $key => $option)
            @if($arrayStringValue == true)
                <input class="form-check-input"
                       type="checkbox"
                       name="{{$name}}[{{$key}}]"
                       id="{{$id}}{{$key+1}}"
                       value="{{ $option->{$textValue} }}"
                    {{ in_array( $option->{$textValue}, replaceArrayString(explode(",",$selected))) ? 'checked' : '' }}
                >
                @else
                <input class="form-check-input"
                       type="checkbox"
                       name="{{$name}}[{{$key}}]"
                       id="{{$id}}{{$key+1}}"
                       value="{{ $option->{$textValue} }}"
                    {{ in_array( $option->{$textValue}, $selected) ? 'checked' : '' }}
                >
                @endif
            <label class="form-check-label mr-3" for="{{$id}}{{$key+1}}">{{ $option->{$textLabel} }}</label>
        @endforeach
    </div>
</div>
