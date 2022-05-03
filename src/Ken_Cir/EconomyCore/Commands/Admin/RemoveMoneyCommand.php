<?php

declare(strict_types=1);

namespace Ken_Cir\EconomyCore\Commands\Admin;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use Ken_Cir\EconomyCore\Database\Economy\EconomyDataManager;
use Ken_Cir\EconomyCore\Database\Player\PlayerDataManager;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class RemoveMoneyCommand extends BaseCommand
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
                $sender->sendMessage(TextFormat::RED . "[EconomyCore] プレイヤー名 {$args["playerName"]} のデータが見つかりませんでした");
                return;
            }

            $economyData = EconomyDataManager::getInstance()->get($playerData->getXuid());
            $economyData->removeMoney($args["removeMoney"]);
            $sender->sendMessage(TextFormat::GREEN . "[EconomyCore] {$playerData->getName()}から{$args["removeMoney"]}円減らしました、現在の所持金は{$economyData->getMoney()}円です");
        }
        else {
            $this->sendUsage();
        }
    }
}