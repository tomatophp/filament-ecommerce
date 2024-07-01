<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

trait GenerateUUID
{
    public function generateUUID(): string
    {
        return setting('ordering_stating_code') .'-'. \Illuminate\Support\Str::random(8);
    }
}
