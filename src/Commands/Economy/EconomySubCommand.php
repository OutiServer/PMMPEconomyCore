<?php

declare(strict_types=1);

namespace outiserver\economycore\Commands\Economy;

use outiserver\economycore\Commands\Base\SubCommandBase;
use outiserver\economycore\EconomyCore;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;

class EconomySubCommand extends SubCommandBase
{
    /**
     * @var SubCommandBase[]
     */
    private array $subCommands;

    /**
     * @param Plugin $plugin
     * @param string $name
     * @param Translatable|string $description
     * @param Translatable|string|null $usageMessage
     * @param array $aliases
     */
    public function __construct(Plugin $plugin, string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($plugin, $name, $description, $usageMessage, $aliases);

        $this->subCommands = [
        ];
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (count($args) < 1 or !isset($this->subCommands[$args[0]])) {
            $sender->sendMessage(EconomyCore::getInstance()->getLanguageManager()->getLanguage($sender->getLanguage()->getLang())->translateString("command.economy"));
            foreach ($this->subCommands as $subCommand) {
                if ($subCommand->testPermission($sender)) {
                    $sender->sendMessage(TextFormat::GREEN . $subCommand->getUsage());
                }
                else {
                    $sender->sendMessage(TextFormat::RED . $subCommand->getUsage());
                }
            }
        }
        else {
            $this->subCommands[$args[0]]->execute($sender, $commandLabel, $args);
        }
    }
}