<?php

declare(strict_types=1);

namespace outiserver\economycore\Database\Economy;

use outiserver\economycore\Database\Base\BaseDataManager;
use outiserver\economycore\EconomyCore;
use pocketmine\utils\SingletonTrait;
use poggit\libasynql\DataConnector;
use poggit\libasynql\SqlError;

class EconomyDataManager extends BaseDataManager
{
    use SingletonTrait;

    public function __construct(DataConnector $dataConnector)
    {
        parent::__construct($dataConnector);
        self::setInstance($this);

        $this->dataConnector->executeSelect("economy.core.economys.load",
            [],
            function (array $row) {
                var_dump("Economy Loaded");
                foreach ($row as $data) {
                    $this->data[$data["xuid"]] = new EconomyData($this->dataConnector, $data["xuid"], $data["money"]);
                }
            },
            function (SqlError $error) {
                EconomyCore::getInstance()->getLogger()->error("[SqlError] {$error->getErrorMessage()}");
            });
    }

    public function get(string $xuid): ?EconomyData
    {
        if (!isset($this->data[$xuid])) return null;
        return $this->data[$xuid];
    }

    public function create(string $xuid, int $money): EconomyData
    {
        if ($data = $this->get($xuid)) return $data;

        $this->dataConnector->executeInsert("economy.core.economys.create",
            [
                "xuid" => $xuid,
                "money" => $money
            ],
        null,
            function (SqlError $error) {
                EconomyCore::getInstance()->getLogger()->error("[SqlError] {$error->getErrorMessage()}");
            });

        return ($this->data[$xuid] = new EconomyData($this->dataConnector, $xuid, $money));
    }

    public function delete(string $xuid): void
    {
        if (!$this->get($xuid)) return;

        $this->dataConnector->executeGeneric("economy.core.economys.delete",
            [
                "xuid" => $xuid,
            ],
        null,
            function (SqlError $error) {
                EconomyCore::getInstance()->getLogger()->error("[SqlError] {$error->getErrorMessage()}");
            });
        unset($this->data[$xuid]);
    }
}