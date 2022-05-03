<?php

declare(strict_types=1);

namespace Ken_Cir\EconomyCore\Database\Player;

use Ken_Cir\EconomyCore\Database\Base\BaseDataManager;
use Ken_Cir\EconomyCore\Database\Economy\EconomyDataManager;
use pocketmine\utils\SingletonTrait;
use poggit\libasynql\DataConnector;

class PlayerDataManager extends BaseDataManager
{
    use SingletonTrait;

    public function __construct(DataConnector $dataConnector)
    {
        parent::__construct($dataConnector);
        self::setInstance($this);

        $this->dataConnector->executeSelect("economy.core.players.load",
        [],
        function (array $row) {
            foreach ($row as $data) {
                $this->data[$data["xuid"]] = new PlayerData($this->dataConnector, $data["xuid"], $data["name"]);
            }
        });
    }

    public function getXuid(string $xuid): ?PlayerData
    {
        if (!isset($this->data[$xuid])) return null;
        return $this->data[$xuid];
    }

    public function getName(string $name): ?PlayerData
    {
        $playerData = array_filter($this->data, function (PlayerData $data) use ($name) {
            return $data->getName() === strtolower($name);
        });
        if (count($playerData) < 1) return null;
        return array_shift($playerData);
    }

    public function create(string $xuid, string $name): PlayerData
    {
        if (($data = $this->getXuid($xuid)) !== null) return $data;

        $this->dataConnector->executeInsert("economy.core.players.create",
        [
            "xuid" => $xuid,
            "name" => strtolower($name)
        ]);
        return ($this->data[$xuid] = new PlayerData($this->dataConnector, $xuid, strtolower($name)));
    }

    public function deleteXuid(string $xuid): void
    {
        if (!$this->getXuid($xuid)) return;

        // Economyデータの削除
        EconomyDataManager::getInstance()->delete($xuid);

        $this->dataConnector->executeGeneric("economy.core.players.delete",
        [
            "xuid" => $xuid,
        ]);
        unset($this->data[$xuid]);
    }

    public function deleteName(string $name): void
    {
        if (!$playerData = $this->getName(strtolower($name))) return;
        $this->deleteXuid($playerData->getXuid());
    }
}