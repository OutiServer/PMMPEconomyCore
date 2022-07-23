<?php

declare(strict_types=1);

namespace outiserver\economycore\Forms\Economy;

use outiserver\economycore\Database\Economy\EconomyDataManager;
use outiserver\economycore\Database\Player\PlayerData;
use outiserver\economycore\Database\Player\PlayerDataManager;
use outiserver\economycore\EconomyCore;
use outiserver\economycore\Forms\Base\BaseForm;
use outiserver\economycore\Forms\Utils\PlayerSelectorForm;
use Ken_Cir\LibFormAPI\FormContents\CustomForm\ContentInput;
use Ken_Cir\LibFormAPI\FormContents\CustomForm\ContentLabel;
use Ken_Cir\LibFormAPI\FormContents\SimpleForm\SimpleFormButton;
use Ken_Cir\LibFormAPI\Forms\CustomForm;
use Ken_Cir\LibFormAPI\Forms\SimpleForm;
use Ken_Cir\LibFormAPI\Utils\FormUtil;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class MoneyForm implements BaseForm
{
    public function execute(Player $player): void
    {
        $form = new CustomForm(EconomyCore::getInstance(),
            $player,
        "[EconomyCore] 所持金の確認",
        [
            new ContentInput("所持金を確認するプレイヤー名", "playerName"),
            new ContentLabel(TextFormat::YELLOW . "このフィールドを空にすると自分の所持金を確認できます")
        ],
        function (Player $player, array $data): void {
            if (!$data[0]) {
                $economyData = EconomyDataManager::getInstance()->get($player->getXuid());
                $player->sendMessage(TextFormat::GREEN . "あなたの所持金は{$economyData->getMoney()}円です");
            }
            elseif ($playerData = PlayerDataManager::getInstance()->getName($data[0])) {
                $economyData = EconomyDataManager::getInstance()->get($playerData->getXuid());
                $player->sendMessage(TextFormat::GREEN . "プレイヤー名{$playerData->getName()}の所持金は{$economyData->getMoney()}円です");
            }
            else {
                /*
                $result = PlayerDataManager::getInstance()->getNamePrefix($data[0]);
                // Q.E.D
                if (count($result) < 1) {
                    $player->sendMessage(TextFormat::RED . "プレイヤーが見つかりませんでした");
                }
                else {
                    $formContent = [];
                    foreach ($result as $playerData) {
                        $formContent[] = new SimpleFormButton($playerData->getName());
                    }

                    new SimpleForm($player,
                        "[PlayerSelector] プレイヤーを選択",
                        "該当するプレイヤーが見つかりました、以下から選択してください",
                    $formContent,
                    function (Player $player, int $data) use ($result): void {
                        $playerData = $result[$data];
                        $economyData = EconomyDataManager::getInstance()->get($playerData->getXuid());
                        $player->sendMessage(TextFormat::GREEN . "プレイヤー名{$playerData->getName()}の所持金は{$economyData->getMoney()}円です、3秒後前のフォームに戻ります");
                        FormUtil::backForm(EconomyCore::getInstance(),
                            [EconomyCore::getInstance()->getStackFormManager()->getStackFormEnd($player->getXuid()), "reSend"],
                            [],
                            3);
                    },
                    // CLOSED
                    function (Player $player): void {
                        EconomyCore::getInstance()->getStackFormManager()->getStackFormEnd($player->getXuid())->reSend();
                    });

                    return;
                }
                */

                $playerSelectorForm = new PlayerSelectorForm();
                $playerSelectorForm->execute($player, $data[0], function (Player $player, PlayerData $playerData): void {
                    $economyData = EconomyDataManager::getInstance()->get($playerData->getXuid());
                    $player->sendMessage(TextFormat::GREEN . "プレイヤー名{$playerData->getName()}の所持金は{$economyData->getMoney()}円です、3秒後前のフォームに戻ります");
                    FormUtil::backForm(EconomyCore::getInstance(),
                        [EconomyCore::getInstance()->getStackFormManager()->getStackFormEnd($player->getXuid()), "reSend"],
                        [],
                        3);
                });
                return;
            }

            $player->sendMessage(TextFormat::GREEN . "3秒後前のフォームに戻ります");
            FormUtil::backForm(EconomyCore::getInstance(),
                [EconomyCore::getInstance()->getStackFormManager()->getStackFormEnd($player->getXuid()), "reSend"],
                [],
                3);
        },
        function (Player $player): void {
            EconomyCore::getInstance()->getStackFormManager()->deleteStackForm($player->getXuid(), "economy_money_form");
            EconomyCore::getInstance()->getStackFormManager()->getStackFormEnd($player->getXuid())->reSend();
        });

        EconomyCore::getInstance()->getStackFormManager()->addStackForm($player->getXuid(), "economy_money_form", $form);


            /*
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
            */
    }
}