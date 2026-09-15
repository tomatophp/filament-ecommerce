<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

use Illuminate\Http\Request;

trait SyncMeta
{
    private function syncMeta(Request $request): void
    {
        $this->meta('issue_date', $request->input('issue_date') ?? $this->order->created_at->format('Y-m-d'));
        $this->meta('due_date', $request->input('due_date') ?? $this->order->created_at->format('Y-m-d'));
    }

    public function meta(string $key, mixed $value = null): mixed
    {
        if ($value) {
            return $this->order->meta($key, $value);
        } else {
            return $this->order->meta($key);
        }
    }
}
