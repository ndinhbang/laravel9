<?php

namespace App\Form\Components\Field;

use App\Form\Components\Field;

class Hidden extends Field
{
    protected string $view = 'forms::components.hidden';

    protected function setUp(): void
    {
        parent::setUp();

        $this->columnSpan('hidden');
    }
}
