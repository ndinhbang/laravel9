<?php

namespace App\Form\Components\Contracts;

interface CanHaveNumericState
{
    public function isNumeric(): bool;
}
