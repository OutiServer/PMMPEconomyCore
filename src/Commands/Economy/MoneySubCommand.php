<?php

declare(strict_types=1);

namespace outiserver\economycore\Commands\Economy;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use outiserver\economycore\Database\Economy\EconomyDataManager;
use outiserver\economycore\Database\Player\PlayerDataManager;
use outiserver\economycore\EconomyCore;
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
                $sender->sendMessage(EconomyCore::getInstance()->getLanguageManager()->getLanguage($sender->getLanguage()->getLang())->translateString("command.money.success", [$playerData->getName(), $economyData->getMoney()]));
            }
            else {
                $sender->sendMessage(EconomyCore::getInstance()->getLanguageManager()->getLanguage($sender->getLanguage()->getLang())->translateString("command.money.error.player_not_found", [$args["playerName"]]));
            }
        }
        elseif ($sender instanceof Player) {
            $economyData = EconomyDataManager::getInstance()->get($sender->getXuid());
            $sender->sendMessage(EconomyCore::getInstance()->getLanguageManager()->getLanguage($sender->getLocale())->translateString("command.money.success", [$sender->getName(), $economyData->getMoney()]));
        }
        else {
            $this->sendUsage();
        }
    }
}