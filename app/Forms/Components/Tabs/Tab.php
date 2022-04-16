<?php

namespace App\Forms\Components\Tabs;

use App\Forms\Components\Component;
use App\Forms\Components\Contracts\CanConcealComponents;
use Illuminate\Support\Str;

class Tab extends Component implements CanConcealComponents
{
    protected string $view = 'forms::components.tabs.tab';

    final public function __construct(string $label)
    {
        $this->label($label);
        $this->id(Str::slug($label));
    }

    public static function make(string $label): static
    {
        $static = app(static::class, ['label' => $label]);
        $static->setUp();

        return $static;
    }

    public function getId(): string
    {
        return $this->getContainer()->getParentComponent()->getId() . '-' . parent::getId() . '-tab';
    }
}
