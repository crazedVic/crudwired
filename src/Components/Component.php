<?php

namespace Crazed\Crudwired\Components;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class Component extends \Livewire\Component
{
    public $routeUri, $routeName, $routeMiddleware, $routeDomain, $routeWhere;
    public $model = [];

    public function model($key)
    {
        return Arr::get($this->model, $key);
    }

    public function validate($rules = null, $messages = [], $attributes = [])
    {
        [$rules, $messages, $attributes] = $this->providedOrGlobalRulesMessagesAndAttributes($rules, $messages, $attributes);
        $validator = Validator::make($this->model, $rules, $messages, $attributes);
        $validated = $validator->validate();

        $this->resetErrorBag();

        return $validated;
    }
}
