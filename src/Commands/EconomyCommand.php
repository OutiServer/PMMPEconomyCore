<?php

declare(strict_types=1);

namespace outiserver\economycore\Commands;

use CortexPE\Commando\BaseCommand;
use outiserver\economycore\Commands\Admin\AddMoneySubCommand;
use outiserver\economycore\Commands\Admin\RemoveMoneySubCommand;
use outiserver\economycore\Commands\Admin\SetMoneySubCommand;
use outiserver\economycore\Commands\Economy\FormSubCommand;
use outiserver\economycore\Commands\Economy\MoneySubCommand;
use pocketmine\command\CommandSender;

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