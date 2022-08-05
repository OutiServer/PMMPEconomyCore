<?php

declare(strict_types=1);

namespace outiserver\economycore\Commands\Base;

use outiserver\economycore\EconomyCore;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

abstract class SubCommandBase extends Command implements PluginOwned
{
    protected Plugin $plugin;

    /**
     * @param Plugin $plugin
     * @param string $name
     * @param Translatable|string $description
     * @param Translatable|string|null $usageMessage
     * @param array $aliases
     */
    public function __construct(Plugin $plugin, string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);

        $this->plugin = $plugin;
    }

    public function getOwningPlugin(): Plugin
    {
        return $this->plugin;
    }
}