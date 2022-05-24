<?php

declare(strict_types=1);

namespace Ken_Cir\EconomyCore\Forms\Economy;

use jojoe77777\FormAPI\CustomForm;
use Ken_Cir\EconomyCore\Database\Economy\EconomyDataManager;
use Ken_Cir\EconomyCore\Database\Player\PlayerDataManager;
use Ken_Cir\EconomyCore\Forms\Base\BaseForm;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class MoneyForm implements BaseForm
{
    public function execute(Player $player): void
    {
        $form = new CustomForm(function (Player $player, $data) {
            if ($data === null) return;
            elseif ($data[0]) {
                $playerData = PlayerDataManager::getInstance()->getName($data[0]);
                if ($playerData !== null) {
                    $economyData = EconomyDataManager::getInstance()->get($playerData->getXuid());
                    $player->sendMessage(TextFormat::GREEN . "プレイヤー名{$playerData->getName()}の所持金は{$economyData->getMoney()}円です");
                }
                else {
                    $player->sendMessage(TextFormat::RED . "プレイヤー名 $data[0]のデータが見つかりませんでした");
                }
            }
            else {
                $economyData = EconomyDataManager::getInstance()->get($player->getXuid());
                $player->sendMessage(TextFormat::GREEN . "あなたの所持金は{$economyData->getMoney()}円です");
            }
        });

        $form->setTitle("[EconomyCore] 所持金の確認");
        $form->addInput("所持金を確認するプレイヤー名", "playerName");
        $form->addLabel(TextFormat::YELLOW . "このフィールドを空にすると自分の所持金を確認できます");
        $player->sendForm($form);
    }
}