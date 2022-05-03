<?php

declare(strict_types=1);

namespace Ken_Cir\EconomyCore;

use CortexPE\Commando\PacketHooker;
use Ken_Cir\EconomyCore\Commands\Admin\AddMoneyCommand;
use Ken_Cir\EconomyCore\Commands\Admin\RemoveMoneyCommand;
use Ken_Cir\EconomyCore\Commands\Economy\MoneyCommand;
use Ken_Cir\EconomyCore\Database\Economy\EconomyDataManager;
use Ken_Cir\EconomyCore\Database\Player\PlayerDataManager;
use Ken_Cir\EconomyCore\Handlers\EventHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;

class EconomyCore extends PluginBase
{
    use SingletonTrait;

    const VERSION = "1.0.0";

    private DataConnector $dataConnector;

    private PlayerDataManager $playerDataManager;

    private EconomyDataManager $economyDataManager;

    protected function onLoad(): void
    {
        self::setInstance($this);
    }

    protected function onEnable(): void
    {
        if (@file_exists("{$this->getDataFolder()}database.yml")) {
            $config = new Config("{$this->getDataFolder()}database.yml", Config::YAML);
            // データベース設定のバージョンが違う場合は
            if ($config->get("version") !== self::VERSION) {
                rename("{$this->getDataFolder()}database.yml", "{$this->getDataFolder()}database.yml.{$config->get("version")}");
                $this->getLogger()->warning("database.yml バージョンが違うため、上書きしました");
                $this->getLogger()->warning("前バージョンのdatabase.ymlは{$this->getDataFolder()}database.yml.{$config->get("version")}にあります");
            }
        }
        if (@file_exists("{$this->getDataFolder()}config.yml")) {
            $config = new Config("{$this->getDataFolder()}config.yml", Config::YAML);
            // データベース設定のバージョンが違う場合は
            if ($config->get("version") !== self::VERSION) {
                rename("{$this->getDataFolder()}database.yml", "{$this->getDataFolder()}config.yml.{$config->get("version")}");
                $this->getLogger()->warning("config.yml バージョンが違うため、上書きしました");
                $this->getLogger()->warning("前バージョンのconfig.ymlは{$this->getDataFolder()}config.yml.{$config->get("version")}にあります");
            }
        }

        $this->saveResource("database.yml");
        $this->saveResource("config.yml");

        $this->dataConnector = libasynql::create($this, (new Config($this->getDataFolder() . "database.yml", Config::YAML))->get("database"), [
            "sqlite" => "sqlite.sql"
        ]);
        #$this->dataConnector->executeGeneric("economy.core.players.drop");
        #$this->dataConnector->executeGeneric("economy.core.economys.drop");
        $this->dataConnector->executeGeneric("economy.core.players.init");
        $this->dataConnector->executeGeneric("economy.core.economys.init");
        $this->dataConnector->waitAll();

        $this->playerDataManager = new PlayerDataManager($this->dataConnector);
        $this->economyDataManager = new EconomyDataManager($this->dataConnector);

        if (!PacketHooker::isRegistered()) {
            PacketHooker::register($this);
        }

        $this->getServer()->getPluginManager()->registerEvents(new EventHandler($this), $this);
        $this->getServer()->getCommandMap()->registerAll($this->getName(), [
            new MoneyCommand($this,
            "money",
            "所持金を確認する",
            []),
            new AddMoneyCommand($this,
            "addmoney",
            "所持金を増やす",
            []),
            new RemoveMoneyCommand($this,
            "removemoney",
            "所持金を減らす",
            [])
        ]);
    }

    protected function onDisable(): void
    {
        $this->dataConnector->waitAll();
        $this->dataConnector->close();
    }

    /**
     * @return DataConnector
     */
    public function getDataConnector(): DataConnector
    {
        return $this->dataConnector;
    }

    /**
     * @return PlayerDataManager
     */
    public function getPlayerDataManager(): PlayerDataManager
    {
        return $this->playerDataManager;
    }

    /**
     * @return EconomyDataManager
     */
    public function getEconomyDataManager(): EconomyDataManager
    {
        return $this->economyDataManager;
    }
}