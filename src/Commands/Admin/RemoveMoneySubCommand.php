<?php

declare(strict_types=1);

namespace outiserver\economycore\Commands\Admin;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use outiserver\economycore\Database\Economy\EconomyDataManager;
use outiserver\economycore\Database\Player\PlayerDataManager;
use outiserver\economycore\EconomyCore;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class RemoveMoneySubCommand extends BaseSubCommand
{
    protected function prepare(): void
    {
        $this->setPermission("economy.command.admin.removeMoney");
        $this->registerArgument(0, new RawStringArgument("playerName", false));
        $this->registerArgument(1, new IntegerArgument("removeMoney", false));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (isset($args["playerName"]) and isset($args["removeMoney"])) {
            $playerData = PlayerDataManager::getInstance()->getName($args["playerName"]);
            if ($playerData === null) {
                $sender->sendMessage(EconomyCore::getInstance()->getLanguageManager()->getLanguage($sender->getLanguage()->getLang())->translateString("command.money.error.player_not_found", [$args["playerName"]]));
                return;
            }

            $economyData = EconomyDataManager::getInstance()->get($playerData->getXuid());
            $economyData->removeMoney($args["removeMoney"]);
            $sender->sendMessage(EconomyCore::getInstance()->getLanguageManager()->getLanguage($sender->getLanguage()->getLang())->translateString("command.money.remove.success", [$playerData->getName(), $args["removeMoney"], $economyData->getMoney()]));
        }
        else {
            $this->sendUsage();
        }
    }
}