<?php

declare(strict_types=1);

namespace Ken_Cir\EconomyCore\Commands;

use CortexPE\Commando\BaseCommand;
use Ken_Cir\EconomyCore\Commands\Admin\AddMoneySubCommand;
use Ken_Cir\EconomyCore\Commands\Admin\RemoveMoneySubCommand;
use Ken_Cir\EconomyCore\Commands\Admin\SetMoneySubCommand;
use Ken_Cir\EconomyCore\Commands\Economy\FormSubCommand;
use Ken_Cir\EconomyCore\Commands\Economy\MoneySubCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class EconomyCommand extends BaseCommand
{
    protected function prepare(): void
    {
        $this->setPermission("economy.command");
        $this->registerSubCommand(new MoneySubCommand("money", "所持金を確認する", []));
        $this->registerSubCommand(new AddMoneySubCommand("addmoney", "所持金を増やす", []));
        $this->registerSubCommand(new RemoveMoneySubCommand("removemoney", "所持金を減らす", []));
        $this->registerSubCommand(new SetMoneySubCommand("setmoney", "所持金をセットする", []));
        $this->registerSubCommand(new FormSubCommand("form", "EconomyのFormを出す", []));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
    }
}