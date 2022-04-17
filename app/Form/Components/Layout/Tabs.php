<?php

namespace App\Form\Components\Layout;

use App\Form\Components\Component;
use App\Form\Components\Concerns;
use App\Form\Components\Tabs\Tab;

class Tabs extends Component
{
    use Concerns\HasExtraAlpineAttributes;

    protected string $view = 'forms::components.tabs';

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

    public function tabs(array $tabs): static
    {
        $this->schema($tabs);

        return $this;
    }

    public function getTabsConfig(): array
    {
        return collect($this->getChildComponentContainer()->getComponents())
            ->filter(fn (Tab $tab): bool => ! $tab->isHidden())
            ->mapWithKeys(fn (Tab $tab): array => [$tab->getId() => $tab->getLabel()])
            ->toArray();
    }
}