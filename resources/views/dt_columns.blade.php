[
{
"data": "checked",
"name": "checked",
"orderable": false,
"searchable": false,
"title": "#",
"class": "w-10",
},
@foreach($fields as $column)
    {
    "data": "{{$column['name']}}",
    "name": "{{$column['name']}}",
    "orderable": true,
    "searchable": true,
    "title": "{{$column['label']}}",
    "class": "auto text-capitalize"
    },
@endforeach
{
"data": "action",
"orderable": false,
"searchable": false,
"title": "Action",
"class": "text-center w-25"
}
]
