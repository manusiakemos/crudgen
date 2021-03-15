@foreach($fields_form as $field)
    @switch($field['htmlType'])
        @case("text")
        <b-form-group class="mb-3">
            <vs-input
                class="w-100"
                :danger="errors.{{$field["name"]}} != undefined"
                :danger-text="errors.{{$field["name"]}} != undefined ? errors.{{$field["name"]}}[0] : ''"
                label="{{$field['label']}}"
                v-model="data.{{$field["name"]}}"/>
        </b-form-group>
        @break

        @case("date")
        <b-form-group class="mb-3">
            <datepicker
                class="w-100"
                :danger="errors.{{$field["name"]}} != undefined"
                :danger-text="errors.{{$field["name"]}} != undefined ? errors.{{$field["name"]}}[0] : ''"
                label="{{$field['label']}}"
                v-model="data.{{$field["name"]}}"/>
        </b-form-group>
        @break

        @case("select")
        <b-form-group class="mb-3">
            <select-ajax v-model="data.{{$field["name"]}}" url="/api/select/boolean"
                         :errors="errors.{{$field["name"]}} != undefined ? errors.{{$field["name"]}}[0] : ''"
                         label="{{$field['label']}}"
                         option-text="text"
                         option-value="value"></select-ajax>
        </b-form-group>
        @break

        @case("radio")
        <b-form-group class="mb-3">
            <radio-ajax v-model="data.{{$field["name"]}}" url="/api/select/boolean"
                        :errors="errors.{{$field["name"]}} != undefined ? errors.{{$field["name"]}}[0] : ''"
                        label="{{$field['label']}}"
                        option-text="text"
                        option-value="value"></radio-ajax>
        </b-form-group>
        @break

        @case("checkbox")
        <b-form-group class="mb-3">
            <checkbox-ajax v-model="data.{{$field["name"]}}" url="/api/select/boolean"
                        :errors="errors.{{$field["name"]}} != undefined ? errors.{{$field["name"]}}[0] : ''"
                        label="{{$field['label']}}"
                        option-text="text"
                        option-value="value"></checkbox-ajax>
        </b-form-group>
        @break

        @case("file")
        <b-form-group class="mb-3">
            <b-form-file
                v-model="image"
                placeholder="Choose a file or drop it here..."
                drop-placeholder="Drop file here..."
            ></b-form-file>
            <error-message v-if="errors.image != undefined && errors.image[0]"
                           :message="errors.image[0]"></error-message>
        </b-form-group>
        @break
    @endswitch
@endforeach
