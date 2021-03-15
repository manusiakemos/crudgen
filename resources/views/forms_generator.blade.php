@foreach($fields_form as $field)
    @switch($field['htmlType'])
        @case("text")
        <div class="form-group">
            <label for="{{ $field['name'] }}">{{$field['label']}}</label>
            <input name="{{ $field['name'] }}" type="text"
                   data-parsley-errors-container="#{{$field['name']}}-errors"
                   value="{!! '{{isset($data) ? $data->'.$field["name"].' : ""}}' !!}"
                   class="form-control" id="{{ $field['name'] }}">
            <div id="{{$field['name']}}-errors"></div>
        </div>
        @break

        @case("date")
        <div class="form-group">
            <label for="{{ $field['name'] }}">{{$field['label']}}</label>
            <input name="{{ $field['name'] }}" type="text"
                   placeholder="YYYY-MM-DD"
                   data-parsley-errors-container="#{{$field['name']}}-errors"
                   value="{!! '{{isset($data) ? $data->'.$field["name"].' : "'.date("Y-m-d").'"}}' !!}"
                   class="form-control date" id="{{ $field['name'] }}">
            <div id="{{$field['name']}}-errors"></div>
        </div>
        @break

        @case("money")
        <div class="form-group">
            <label for="{{ $field['name'] }}">{{$field['label']}}</label>
            <input name="{{ $field['name'] }}" type="text"
                   placeholder=""
                   data-parsley-errors-container="#{{$field['name']}}-errors"
                   value="{!! '{{isset($data) ? $data->'.$field["name"].' : ""}}' !!}"
                   class="form-control money" id="{{ $field['name'] }}">
            <div id="{{$field['name']}}-errors"></div>
        </div>
        @break

        @case("file")
        <div class="form-group">
            <div class="custom-file mt-4">
                <input name="{{ $field['name'] }}" type="file"
                       class="custom-file-input" id="{{ $field['name'] }}">
                <label class="custom-file-label" for="{{ $field['name'] }}">{{$field['label']}}</label>
            </div>
        </div>
        @break

        @case("select")
        <div class="form-group">
            <label for="{{ $field['name'] }}">{{$field['label']}}</label>
            {!!'<xx-select-component id="'.$field["name"].'"
               name="'.$field["name"].'"
               error-container="#'.$field["name"].'-errors"
               case="status"
               :selected="isset($data) ? $data->'.$field["name"].' : 1">
            </xx-select-component>'
            !!}
            <div id="{{$field['name']}}-errors"></div>
        </div>
        @break

        @case("radio")
        <div class="form-group">
            <label for="{{ $field['name'] }}">{{$field['label']}}</label>
            {!!'<xx-radio-component id="'.$field["name"].'"
               name="'.$field["name"].'"
               error-container="#'.$field["name"].'-errors"
               case="status"
               :selected="isset($data) ? $data->'.$field["name"].' : 1">
            </xx-radio-component>'
            !!}
            <div id="{{$field['name']}}-errors"></div>
        </div>
        @break

        @case("checkbox")
        <div class="form-group">
            <label for="{{ $field['name'] }}">{{$field['label']}}</label>
            {!!'<xx-checkbox-component id="'.$field["name"].'"
               name="'.$field["name"].'"
               error-container="#'.$field["name"].'-errors"
               case="status"
               :selected="isset($data) ? $data->'.$field["name"].' : 1">
            </xx-checkbox-component>'
            !!}
            <div id="{{$field['name']}}-errors"></div>
        </div>
        @break
    @endswitch
@endforeach
