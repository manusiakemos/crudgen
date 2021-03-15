@foreach($fields as $field)
    @if(isset($field['length']))
        @if($field['length'])
            $table->{{ $field['dbType'] }}('{{ $field['name'] }}' {!! $field['dbType'] != "integer" ? ",". $field['length'] : '' !!})
        @else
            $table->{{ $field['dbType'] }}('{{ $field['name'] }}')
        @endif
    @else
        $table->{{ $field['dbType'] }}('{{ $field['name'] }}')
    @endif
    @if($field['nullable'])->nullable()@endif
    @if($field['default'])->default({{$field['default']}});
    @else
        ;
    @endif
@endforeach
