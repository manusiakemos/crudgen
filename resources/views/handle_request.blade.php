@foreach($fields as $field)
    @if($field['htmlType'] == 'file' || $field['htmlType'] == 'image')
        if($request->hasFile('{{$field['name']}}')){
        $filename = Str::random().".".$request->file('{{$field['name']}}')->getClientOriginalExtension();
        Storage::putFileAs('uploads', $request->file('{{$field['name']}}'), $filename);
        $db->{{$field['name']}} = $filename;
        }
    @elseif($field['htmlType'] == 'money')
        $db->{{$field['name']}} = str_replace(".", "",$request->{{$field['name']}});
    @else
        $db->{{$field['name']}} = $request->{{$field['name']}};
    @endif
@endforeach
