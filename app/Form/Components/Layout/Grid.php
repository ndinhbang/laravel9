<?php

namespace App\Form\Components\Layout;

use App\Form\Components\Component;

class Grid extends Component
{
    protected string $view = 'forms::components.grid';

    final public function __construct(array | int | null $columns)
    {
        $this->columns($columns);
    }

    public static function make(array | int | null $columns = 2): static
    {
        $static = app(static::class, ['columns' => $columns]);
        $static->setUp();

        return $static;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->columnSpan('full');
    }
}
