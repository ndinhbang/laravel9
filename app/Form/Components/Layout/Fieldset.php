<?php

namespace App\Form\Components\Layout;

use App\Form\Components\Component;

class Fieldset extends Component
{
    protected string $view = 'forms::components.fieldset';

    final public function __construct(string $label)
    {
        $this->label($label);
    }

    public static function make(string $label): static
    {
        $static = app(static::class, ['label' => $label]);
        $static->setUp();

        return $static;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->columnSpan('full');

        $this->columns(2);
    }
}
