<?php

declare(strict_types=1);

namespace Ken_Cir\EconomyCore\Commands\Economy;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use Ken_Cir\EconomyCore\Database\Economy\EconomyDataManager;
use Ken_Cir\EconomyCore\Database\Player\PlayerDataManager;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class MoneySubCommand extends BaseSubCommand
{
    protected function prepare(): void
    {
        $this->setPermission("economy.command.economy.money");
        $this->registerArgument(0, new RawStringArgument("playerName", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (isset($args["playerName"])) {
            $playerData = PlayerDataManager::getInstance()->getName($args["playerName"]);
            if ($playerData !== null) {
                $economyData = EconomyDataManager::getInstance()->get($playerData->getXuid());
                $sender->sendMessage(TextFormat::GREEN . "[EconomyCore] {$playerData->getName()}の所持金は{$economyData->getMoney()}円です");
            }
            else {
                $sender->sendMessage(TextFormat::RED . "[EconomyCore] プレイヤー名{$args["playerName"]}のデータが見つかりませんでした");
            }
        }
        elseif ($sender instanceof Player) {
            $economyData = EconomyDataManager::getInstance()->get($sender->getXuid());
            $sender->sendMessage(TextFormat::GREEN . "[EconomyCore] あなたの所持金は{$economyData->getMoney()}円です");
        }
        else {
            $this->sendUsage();
        }
    }
}