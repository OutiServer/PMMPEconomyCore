<?php

declare(strict_types=1);

namespace outiserver\economycore\Database\Economy;

use outiserver\economycore\Database\Base\BaseData;
use poggit\libasynql\DataConnector;

class EconomyData extends BaseData
{
    private string $xuid;

    private int $money;

    public function __construct(DataConnector $dataConnector, string $xuid, int $money)
    {
        parent::__construct($dataConnector);

        $this->xuid = $xuid;
        $this->money = $money;
    }

    protected function update(): void
    {
        $this->dataConnector->executeChange("economy.core.economys.update",
            [
                "money" => $this->money,
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
     * @return int
     */
    public function getMoney(): int
    {
        return $this->money;
    }

    /**
     * @param int $money
     */
    public function setMoney(int $money): void
    {
        $this->money = $money;
        $this->update();
    }

    public function addMoney(int $addMoney): void
    {
        $this->money += $addMoney;
        $this->update();
    }

    public function removeMoney(int $removeMoney): void
    {
        $this->money -= $removeMoney;
        $this->update();
    }
}