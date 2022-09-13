<?php

declare(strict_types=1);

namespace outiserver\economycore\Database\Player;

use outiserver\economycore\Database\Base\BaseData;
use outiserver\economycore\EconomyCore;
use poggit\libasynql\DataConnector;
use poggit\libasynql\SqlError;

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
            ],
            null,
            function (SqlError $error) {
                EconomyCore::getInstance()->getLogger()->error("[SqlError] {$error->getErrorMessage()}");
            });
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