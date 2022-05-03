<?php

declare(strict_types=1);

namespace Ken_Cir\EconomyCore\Database\Base;

use poggit\libasynql\DataConnector;

abstract class BaseData
{
    protected DataConnector $dataConnector;

    public function __construct(DataConnector $dataConnector)
    {
        $this->dataConnector = $dataConnector;
    }

    abstract protected function update(): void;
}