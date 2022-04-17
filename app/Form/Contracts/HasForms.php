<?php

namespace App\Form\Contracts;

interface HasForms
{
    public function dispatchFormEvent(...$args): void;

    public function validate(?array $rules = null, array $messages = [], array $attributes = []);
}
