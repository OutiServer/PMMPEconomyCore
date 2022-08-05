<?php

declare(strict_types=1);

namespace outiserver\economycore\Commands\Economy;

use CortexPE\Commando\BaseSubCommand;
use outiserver\economycore\EconomyCore;
use outiserver\economycore\Forms\EconomyForm;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class FormSubCommand extends BaseSubCommand
{
    protected function prepare(): void
    {
        $this->setPermission("economy.command.economy.form");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage(EconomyCore::getInstance()->getLanguageManager()->getLanguage($sender->getLanguage()->getLang())->translateString("command.error.please_used_server"));
            return;
        }

        (new EconomyForm())->execute($sender);
    }
}