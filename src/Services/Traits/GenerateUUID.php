<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

use Illuminate\Support\Str;

trait GenerateUUID
{
    public function generateUUID(): string
    {
        return setting('ordering_stating_code') . '-' . Str::random(8);
    }
}
