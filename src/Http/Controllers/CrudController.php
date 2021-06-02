<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class CrudController extends Controller
{
    public function __construct()
    {
        //        $this->middleware(['auth'])->only('ui');
    }

    public function index()
    {
        return view("vendor.crudgen.index");
    }

    public function data()
    {
        $get_json = file_get_contents(base_path("/database/json/crudgen.json"));
        return response()->json(json_decode($get_json, FALSE));
    }

    public function truncate(Request $request)
    {
        $data = $request->data;
        $save = file_put_contents(base_path("/database/json/crudgen.json"), $data);
        return response()->json(['status' => $save, 'message' => 'Successfully generated']);
    }

    public function store(Request $request)
    {
        //        dd($request->all());
        $this->handleRequest($request);
        //        Artisan::call("ide-helper:models --write");
        if ($request->migrate) {
            Artisan::call("migrate");
        }

        $get_json = file_get_contents(base_path("/database/json/crudgen.json"));
        $array = json_decode($get_json, true);
        $collection = collect($array);
        $data = $collection->where("class", $request->class)->first();
        if ($data) {
            $filtered = $collection->reject(function ($value, $key) use ($request) {
                return $value['class'] == $request->class;
            });
            $x = collect($request->all());
            $filtered->push($x);
            $json = $filtered;
        } else {
            $x = collect($request->all());
            $collection->push($x);
            $json = $collection;
        }
        $save = file_put_contents(base_path("/database/json/crudgen.json"), $json);
        return response()->json(['status' => $save, 'message' => 'Successfully generated']);
    }

    private function handleRequest($request)
    {
        try {
            if ($request->controller) {
                $this->generateController($request);
            }

            if ($request->model) {
                $this->generateModel($request);
            }

            if ($request->router) {
                $this->generateRouter($request);
            }

            if ($request->migration) {
                $this->generateMigration($request);
            }

            if ($request->view) {
                $this->generateView($request);
            }
        } catch (Exception $e) {
            return $e;
        }

        return true;
    }

    private function generateController($request)
    {
        $fields_all = collect($request->fields);
        $fields = $fields_all->where("primary", false);
        $pk = $fields_all->where('primary', true)->first();

        $className = $request->class;
        $classNameLower = Str::slug($request->class, "_");
        $handleRequest = View::make('vendor.crudgen.handle_request', compact('fields'))->render();

        $field_validate = $fields_all->where('validations', true);
        $validate = View::make('vendor.crudgen.validate_generator', compact('field_validate'))->render();
        $stubTemplate = [
            '{@primaryKey}',
            '{@className}',
            '{@classNameLower}',
            '{@handleRequest}',
            '{@validate}'
        ];
        $stubReplaceTemplate = [
            $pk['name'],
            $className,
            $classNameLower,
            $handleRequest,
            $validate
        ];
        if (config('app.mode') == 'spa') {
            $stub_template = file_get_contents(base_path("stubs/vendor/crudgen/controller_spa.stub"));
        } else {
            $stub_template = file_get_contents(base_path("stubs/vendor/crudgen/controller.stub"));
        }
        $controllerTemplate = str_replace($stubTemplate, $stubReplaceTemplate, $stub_template);

        file_put_contents(app_path("/Http/Controllers/{$className}Controller.php"), $controllerTemplate);
    }

    private function generateModel($request)
    {
        $className = $request->class;
        $table = $request->table;
        $primary = collect($request->fields)->where('primary', true)->first();
        $primarykey = $primary['name'];

        $stubReplaceTemplate = [
            $className,
            $table,
            $primarykey
        ];

        $stubTemplate = [
            '{@className}',
            '{@table}',
            '{@primaryKey}'
        ];

        $stub_template = file_get_contents(base_path("stubs/vendor/crudgen/model.stub"));
        $modelTemplate = str_replace($stubTemplate, $stubReplaceTemplate, $stub_template);

        file_put_contents(app_path("Models/{$className}.php"), $modelTemplate);
    }

    private function generateRouter(Request $request)
    {
        $className = $request->input('class');
        //        Route::post('agama/api', [App\Http\Controllers\AgamaController::class, 'api'])
        //            ->name('agama.api');
        $routeBulk = "\n " . "Route::post('" . strtolower($className) . "/bulkdelete', [App\Http\Controllers\\" . $className . "Controller::class, 'bulkDelete'])
            ->name('" . strtolower($className) . ".bulkdelete');";
        $routeApi = "\n " . "Route::post('" . strtolower($className) . "/api', [App\Http\Controllers\\" . $className . "Controller::class, 'api'])
            ->name('" . strtolower($className) . ".api');";
        $route = "\n " . "Route::resource('" . strtolower($className) . "',\App\Http\Controllers\\" . $className . "Controller::class);";
        File::append(base_path("routes/admin.php"), $routeBulk);
        File::append(base_path("routes/admin.php"), $routeApi);
        File::append(base_path("routes/admin.php"), $route);

        /*
        $route = strtolower($className);
        $routeText = $className;

        $templateToReplace = '<li class="{{ (request()->is(\'' . $route . '*\')) ? \'active\' : \'\' }}">
    <a href="{{route(\'' . $route . '.index\')}}">' . $routeText . '</a></li>';
        File::append(resource_path("views/parts/sidebar.blade.php"), $templateToReplace);*/
    }

    private function generateView($request)
    {
        $this->generateViewIndex($request);
        $this->generateAction($request);
        $this->generateChecked($request);
        $this->generateCreate($request);
        $this->generateEdit($request);
        $this->generateFormView($request);
        //        Artisan::call("ide-helper:models --write");
    }

    private function generateMigration($request)
    {
        $className = $request->class;
        $tableName = $request->table;
        $classNameLower = strtolower($className);
        $classNameSlug = Str::slug($classNameLower, "_");
        $fields = $request->fields;

        $generatedColumns = View::make("vendor.crudgen.migration", compact('fields'))->render();
        $search = [
            '{@className}', '{@tableName}', '{@generatedColumns}', '{@classNameSlug}', '{@classNameLower}',
        ];
        $replace = [
            $className, $tableName, $generatedColumns, $classNameSlug, $classNameLower
        ];

        $subject = file_get_contents(base_path("stubs/vendor/crudgen/migration.stub"));
        $index_replace_template = str_replace($search, $replace, $subject);
        file_put_contents(database_path("/migrations/2020_00_00_00_create_{$classNameSlug}_table.php"), $index_replace_template);
    }

    private function generateAction(Request $request)
    {
        $className = $request->input('class');
        $classNameLower = strtolower($className);
        $classNameSlug = Str::slug($classNameLower, "_");

        $fields = collect($request->fields);
        $primaryKey = $fields->where("primary", true)->first();
        $search = [
            '{@primaryKey}', '{@classNameLower}'
        ];
        $replace = [
            $primaryKey['name'], $classNameLower
        ];
        if (config('app.mode') == 'spa') {
            $subject = file_get_contents(base_path("stubs/vendor/crudgen/action_spa.stub"));
        } else {
            $subject = file_get_contents(base_path("stubs/vendor/crudgen/action.stub"));
        }
        $index_replace_template = str_replace($search, $replace, $subject);
        $fileName = "/views/$classNameSlug";
        file_put_contents(resource_path("$fileName/actions_{$classNameSlug}.blade.php"), $index_replace_template);
    }

    private function generateChecked(Request $request)
    {
        $className = $request->input('class');
        $classNameLower = strtolower($className);
        $classNameSlug = Str::slug($classNameLower, "_");

        $fields = collect($request->fields);
        $primaryKey = $fields->where("primary", true)->first();
        $search = [
            '{@primaryKey}', '{@classNameLower}'
        ];
        $replace = [
            $primaryKey['name'], $classNameLower
        ];
        $subject = file_get_contents(base_path("stubs/vendor/crudgen/checked.stub"));
        $index_replace_template = str_replace($search, $replace, $subject);
        $fileName = "/views/$classNameSlug";
        file_put_contents(resource_path("$fileName/checked_{$classNameSlug}.blade.php"), $index_replace_template);
    }

    private function generateFormView(Request $request)
    {
        $className = $request->input('class');
        $classNameLower = strtolower($className);
        $classNameSlug = Str::slug($classNameLower, "_");

        $fields = collect($request->fields);
        $fields_form = $fields->where("htmlType", "<>", false);
        $primaryKey = $fields->where("primary", true)->first();
        $generatedForm = View::make("vendor.crudgen.forms_generator", compact("fields_form", "primaryKey"))->render();
        $search = [
            '{@primaryKey}', '{@classNameLower}', '{@generatedForm}', 'xx-', '{@enctype}'
        ];
        if ($request->upload) {
            $enc = "enctype=\"multipart/form-data\"";
        } else {
            $enc = "";
        }
        $replace = [
            $primaryKey['name'], $classNameLower, $generatedForm, 'x-', $enc
        ];
        $subject = file_get_contents(base_path("stubs/vendor/crudgen/form.stub"));
        $index_replace_template = str_replace($search, $replace, $subject);
        $fileName = "/views/$classNameSlug";
        file_put_contents(resource_path("$fileName/form.blade.php"), $index_replace_template);
    }

    private function generateViewIndex($request)
    {
        $className = $request->class;
        $classNameLower = strtolower($className);
        $classNameSlug = Str::slug($classNameLower, "_");
        $fields = collect($request->fields);
        $primaryKey = $fields->where("primary", true)->first();
        $fields = $fields->where("primary", false);
        $pkName = $primaryKey['name'];
        $generatedColumns = View::make("vendor.crudgen.dt_columns", compact('fields'))->render();
        $fields = collect($request->fields);
        $fields_form = $fields->where("htmlType", "<>", false);
        $generatedForm = View::make("vendor.crudgen.forms_generator_spa", compact("fields_form", "primaryKey"))->render();
        $jsonArr = [];
        foreach ($fields as $field) {
            $jsonArr[] = $field['name'] . ':"",';
        }
        $jsonData = implode("\n", $jsonArr);
        $search = [
            '{@className}', '{@generatedColumns}', '{@classNameSlug}', '{@classNameLower}', '{@pkName}', '{@generatedForm}', '{@data}',
        ];
        $replace = [
            $className, $generatedColumns, $classNameSlug, $classNameLower, $pkName, $generatedForm, $jsonData
        ];

        if (config('app.mode') == 'spa') {
            $subject = file_get_contents(base_path("stubs/vendor/crudgen/vue.stub"));
            $path = "js/screens";
            $index_replace_template = str_replace($search, $replace, $subject);
            file_put_contents(resource_path("$path/$className.vue"), $index_replace_template);
        } else {
            $subject = file_get_contents(base_path("stubs/vendor/crudgen/index.stub"));
            $path = "/views/$classNameSlug";
            if (!is_dir(resource_path($path))) {
                mkdir(resource_path($path));
            }
            $index_replace_template = str_replace($search, $replace, $subject);

            file_put_contents(resource_path("$path/index.blade.php"), $index_replace_template);
        }
        //action_slider
    }

    private function generateCreate($request)
    {
        $className = $request->class;
        $classNameLower = strtolower($className);
        $classNameSlug = Str::slug($classNameLower, "_");
        $search = [
            '{@className}', '{@classNameSlug}', '{@classNameLower}',
        ];
        $replace = [
            $className, $classNameSlug, $classNameLower
        ];
        $subject = file_get_contents(base_path("stubs/vendor/crudgen/create.stub"));
        $index_replace_template = str_replace($search, $replace, $subject);
        $path = "/views/$classNameSlug";
        file_put_contents(resource_path("$path/create.blade.php"), $index_replace_template);
    }

    private function generateEdit($request)
    {
        $className = $request->class;
        $classNameLower = strtolower($className);
        $classNameSlug = Str::slug($classNameLower, "_");
        $primaryKey = collect($request->fields)->where('primary', true)->first()['name'];
        $search = [
            '{@className}', '{@classNameSlug}', '{@classNameLower}', '{@primaryKey}',
        ];
        $replace = [
            $className, $classNameSlug, $classNameLower, $primaryKey
        ];
        $subject = file_get_contents(base_path("stubs/vendor/crudgen/edit.stub"));
        $index_replace_template = str_replace($search, $replace, $subject);
        $path = "/views/$classNameSlug";
        file_put_contents(resource_path("$path/edit.blade.php"), $index_replace_template);
    }
}
