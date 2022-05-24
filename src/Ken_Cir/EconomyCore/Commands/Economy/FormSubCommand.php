<?php

declare(strict_types=1);

namespace Ken_Cir\EconomyCore\Commands\Economy;

use CortexPE\Commando\BaseSubCommand;
use Ken_Cir\EconomyCore\Forms\EconomyForm;
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
            $sender->sendMessage(TextFormat::RED . "このコマンドはサーバー内でのみ実行可能です");
            return;
        }

        (new EconomyForm())->execute($sender);
    }
}