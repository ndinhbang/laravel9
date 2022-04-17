<?php

namespace App\Form\Concerns;

use App\Form\ComponentContainer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

trait InteractsWithForms
{
    protected ?array $cachedForms = null;

    protected bool $isCachingForms = false;

    public function __get($property)
    {
        if (! $this->isCachingForms && $form = $this->getCachedForm($property)) {
            return $form;
        }

        return parent::__get($property);
    }

    public function dispatchFormEvent(...$args): void
    {
        foreach ($this->getCachedForms() as $form) {
            $form->dispatchEvent(...$args);
        }
    }

    public function validate($rules = null, $messages = [], $attributes = [])
    {
        try {
            return parent::validate($rules, $messages, $attributes);
        } catch (ValidationException $exception) {
            $this->focusConcealedComponents(array_keys($exception->validator->failed()));

            throw $exception;
        }
    }

    public function validateOnly($field, $rules = null, $messages = [], $attributes = [])
    {
        try {
            return parent::validateOnly($field, $rules, $messages, $attributes);
        } catch (ValidationException $exception) {
            $this->focusConcealedComponents(array_keys($exception->validator->failed()));

            throw $exception;
        }
    }

    protected function callBeforeAndAfterSyncHooks($name, $value, $callback): void
    {
        parent::callBeforeAndAfterSyncHooks($name, $value, $callback);

        foreach ($this->getCachedForms() as $form) {
            $form->callAfterStateUpdated($name);
        }
    }

    protected function cacheForm(string $name): ComponentContainer
    {
        $this->isCachingForms = true;

        if ($this->cachedForms === null) {
            $this->cacheForms();
        } else {
            $this->cachedForms[$name] = $this->getUncachedForms()[$name];
        }

        $this->isCachingForms = false;

        return $this->cachedForms[$name];
    }

    protected function cacheForms(): array
    {
        $this->isCachingForms = true;

        $this->cachedForms = $this->getUncachedForms();

        $this->isCachingForms = false;

        return $this->cachedForms;
    }

    protected function getUncachedForms(): array
    {
        return array_merge($this->getTraitForms(), $this->getForms());
    }

    protected function getTraitForms(): array
    {
        $forms = [];

        foreach (class_uses_recursive($class = static::class) as $trait) {
            if (method_exists($class, $method = 'get' . class_basename($trait) . 'Forms')) {
                $forms = array_merge($forms, $this->{$method}());
            }
        }

        return $forms;
    }

    protected function focusConcealedComponents(array $statePaths): void
    {
        $componentToFocus = null;

        foreach ($this->getCachedForms() as $form) {
            if ($componentToFocus = $form->getInvalidComponentToFocus($statePaths)) {
                break;
            }
        }

        if ($concealingComponent = $componentToFocus?->getConcealingComponent()) {
            $this->dispatchBrowserEvent('expand-concealing-component', [
                'id' => $concealingComponent->getId(),
            ]);
        }
    }

    protected function getCachedForm($name): ?ComponentContainer
    {
        return $this->getCachedForms()[$name] ?? null;
    }

    protected function getCachedForms(): array
    {
        if ($this->cachedForms === null) {
            return $this->cacheForms();
        }

        return $this->cachedForms;
    }

    protected function getFormModel(): Model | string | null
    {
        return null;
    }

    protected function getFormSchema(): array
    {
        return [];
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->makeForm()
                ->schema($this->getFormSchema())
                ->model($this->getFormModel())
                ->statePath($this->getFormStatePath()),
        ];
    }

    protected function getFormStatePath(): ?string
    {
        return null;
    }

    protected function getRules(): array
    {
        $rules = [];

        foreach ($this->getCachedForms() as $form) {
            $rules = array_merge($rules, $form->getValidationRules());
        }

        return $rules;
    }

    protected function getValidationAttributes(): array
    {
        $attributes = [];

        foreach ($this->getCachedForms() as $form) {
            $attributes = array_merge($attributes, $form->getValidationAttributes());
        }

        return $attributes;
    }

    protected function makeForm(): ComponentContainer
    {
        return ComponentContainer::make($this);
    }
}
