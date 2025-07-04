<?php

namespace App\Traits\Enums;

trait LabelForDashedValueEnum
{
    public function getLabel(): ?string
    {
        return ucwords(str_replace('-', ' ', $this->value));
    }
}
