$rules = [
@foreach($field_validate as $field)
    "{{$field['name']}}" => [
    "required"
    ],
@endforeach
];
$this->validate($request, $rules);
