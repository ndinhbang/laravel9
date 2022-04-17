<?php

namespace App\Form\Components\Field;

use App\Form\Components\Concerns;
use App\Form\Components\Field;

class CheckboxList extends Field
{
    use Concerns\HasOptions;

    protected string $view = 'forms::components.checkbox-list';

    protected function setUp(): void
    {
        parent::setUp();

        $this->default([]);

        $this->afterStateHydrated(function (CheckboxList $component, $state) {
            if (is_array($state)) {
                return;
            }

            $component->state([]);
        });
    }
}
