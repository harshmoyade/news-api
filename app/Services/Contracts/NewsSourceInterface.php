<?php

namespace App\Services\Contracts;
interface NewsSourceInterface
{
    public function fetch(): array;
}
