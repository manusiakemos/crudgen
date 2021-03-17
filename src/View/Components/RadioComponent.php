<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RadioComponent extends Component
{
    public $case;
    public $selected;
    public $name;
    public $textValue;
    public $textLabel;
    public $id;
    public $errorContainer;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($case, $name, $id, $selected = "", $errorContainer="", $textValue="value", $textLabel="text")
    {
        $this->textValue = $textValue;
        $this->textLabel = $textLabel;
        $this->id = $id;
        $this->case = $case;
        $this->selected = $selected;
        $this->name = $name;
        $this->errorContainer = $errorContainer;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $options = [];
        $placeholder = "Pilih Salah Satu";
        $value = "value";
        $text = "text";
        $name = $this->name;
        $id = $this->id;
        $selected = $this->selected;
        $textValue = $this->textValue;
        $textLabel = $this->textLabel;
        $errorContainer = $this->errorContainer;

        switch ($this->case) {
            case 'status':
                $options = $this->statusOptions();
                $placeholder = "Pilih Status";
                break;
            case 'role':
                $options = $this->roleOptions();
                $placeholder = "Pilih Role";
                break;
        }
        return view('vendor.crudgen.components.radio-component', compact('options', 'placeholder', 'value', 'text', 'selected', 'name', 'id', 'errorContainer', 'textValue', 'textLabel'));
    }

    private function statusOptions()
    {
        $options = [
            [
                'value' => "1",
                'text' => 'Aktif',
            ],
            [
                'value' => "0",
                'text' => 'Tidak Aktif',
            ]
        ];
        $options = json_decode (json_encode ($options), FALSE);
        return $options;
    }

    private function roleOptions()
    {
        $options = [
            [
                'value' => "admin",
                'text' => 'Admin',
            ],
            [
                'value' => "super-admin",
                'text' => 'Super Admin',
            ]
        ];
        $options = json_decode (json_encode ($options), FALSE);
        return $options;
    }
}
