<?php

declare(strict_types=1);

namespace Ken_Cir\EconomyCore\Database\Base;

use poggit\libasynql\DataConnector;

abstract class BaseDataManager
{
    protected DataConnector $dataConnector;

    protected array $data;

    public function __construct(DataConnector $dataConnector)
    {
        $this->dataConnector = $dataConnector;
        $this->data = [];
    }

    public function getAll(bool $keyValue): array
    {
        if ($keyValue) return array_values($this->data);
        return $this->data;
    }
}