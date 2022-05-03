<?php

declare(strict_types=1);

namespace Ken_Cir\EconomyCore\Database\Player;

use Ken_Cir\EconomyCore\Database\Base\BaseData;
use poggit\libasynql\DataConnector;

class PlayerData extends BaseData
{
    private string $xuid;

    private string $name;

    public function __construct(DataConnector $dataConnector, string $xuid, string $name)
    {
        parent::__construct($dataConnector);

        $this->xuid = $xuid;
        $this->name = $name;
    }

    protected function update(): void
    {
        $this->dataConnector->executeChange("economy.core.players.update",
        [
            "name" => $this->name,
            "xuid" => $this->xuid
        ]);
    }

    /**
     * @return string
     */
    public function getXuid(): string
    {
        return $this->xuid;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
        $this->update();
    }
}