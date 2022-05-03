<?php

declare(strict_types=1);

namespace Ken_Cir\EconomyCore\Handlers;

use Ken_Cir\EconomyCore\EconomyCore;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;

class EventHandler implements Listener
{
    private EconomyCore $plugin;

    public function __construct(EconomyCore $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onPlayerLogin(PlayerLoginEvent $event): void
    {
        $player = $event->getPlayer();
        $playerData = $this->plugin->getPlayerDataManager()->getXuid($player->getXuid());
        if ($playerData === null) {
            $playerData = $this->plugin->getPlayerDataManager()->create($player->getXuid(), $player->getName());
        }

        $economyData = $this->plugin->getEconomyDataManager()->get($player->getXuid());
        if ($economyData === null) {
            $economyData = $this->plugin->getEconomyDataManager()->create($player->getXuid(),
                $this->plugin->getConfig()->get("default-player-money", 10000));
        }
    }
}