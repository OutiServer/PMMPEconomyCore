<?php

declare(strict_types=1);

namespace Ken_Cir\EconomyCore\Caches\Base;

abstract class BaseCacheManager
{
    protected array $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function getAll(): array
    {
        return $this->data;
    }

    public function reset(): void
    {
        $this->data = [];
    }
}