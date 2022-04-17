<?php

namespace App\Form\Components\Field;

class DatePicker extends DateTimePicker
{
    public function hasTime(): bool
    {
        return false;
    }
}
