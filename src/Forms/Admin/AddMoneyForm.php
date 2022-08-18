<?php

declare(strict_types=1);

namespace outiserver\economycore\Forms\Admin;

use Ken_Cir\LibFormAPI\FormContents\CustomForm\ContentInput;
use Ken_Cir\LibFormAPI\Forms\CustomForm;
use Ken_Cir\LibFormAPI\Utils\FormUtil;
use outiserver\economycore\Database\Economy\EconomyDataManager;
use outiserver\economycore\Database\Player\PlayerData;
use outiserver\economycore\Database\Player\PlayerDataManager;
use outiserver\economycore\EconomyCore;
use outiserver\economycore\Forms\Base\BaseForm;
use outiserver\economycore\Forms\Utils\PlayerSelectorForm;
use outiserver\economycore\Language\LanguageManager;
use pocketmine\player\Player;

class AddMoneyForm implements BaseForm
{
    public const FORM_KEY = "economy_form_addmoney";

    public function execute(Player $player): void
    {
        $form = new CustomForm(EconomyCore::getInstance(),
        $player,
        LanguageManager::getInstance()->getLanguage($player->getLocale())->translateString("form.addmoney.title"),
        [
            new ContentInput(LanguageManager::getInstance()->getLanguage($player->getLocale())->translateString("form.addmoney.button1"), "playerName"),
            new ContentInput(LanguageManager::getInstance()->getLanguage($player->getLocale())->translateString("form.addmoney.button2"), "addMoney", inputType: ContentInput::TYPE_INT)
        ],
        function (Player $player, array $data) {
            if ($playerData = PlayerDataManager::getInstance()->getName($data[0])) {
                $economyData = EconomyDataManager::getInstance()->get($playerData->getXuid());
                $economyData->addMoney((int)$data[1]);
                $player->sendMessage(LanguageManager::getInstance()->getLanguage($player->getLocale())->translateString("form.addmoney.success", [$playerData->getName(), $data[1], (string)$economyData->getMoney()]));
            }
            else {
                $playerSelectorForm = new PlayerSelectorForm();
                $playerSelectorForm->execute($player, $data[0], function (Player $player, PlayerData $playerData) use ($data): void {
                    $economyData = EconomyDataManager::getInstance()->get($playerData->getXuid());
                    $economyData->addMoney((int)$data[1]);
                    $player->sendMessage(LanguageManager::getInstance()->getLanguage($player->getLocale())->translateString("form.addmoney.success", [$playerData->getName(), $data[1], (string)$economyData->getMoney()]));
                    $player->sendMessage(LanguageManager::getInstance()->getLanguage($player->getLocale())->translateString("form.back"));
                    FormUtil::backForm(EconomyCore::getInstance(),
                        [EconomyCore::getInstance()->getStackFormManager()->getStackFormEnd($player->getXuid()), "reSend"],
                        [],
                        3);
                });
                return;
            }

            $player->sendMessage(LanguageManager::getInstance()->getLanguage($player->getLocale())->translateString("form.back"));
            FormUtil::backForm(EconomyCore::getInstance(),
                [EconomyCore::getInstance()->getStackFormManager()->getStackFormEnd($player->getXuid()), "reSend"],
                [],
                3);
        },
            function (Player $player): void {
                EconomyCore::getInstance()->getStackFormManager()->deleteStackForm($player->getXuid(), self::FORM_KEY);
                EconomyCore::getInstance()->getStackFormManager()->getStackFormEnd($player->getXuid())->reSend();
            });

        EconomyCore::getInstance()->getStackFormManager()->addStackForm($player->getXuid(), self::FORM_KEY, $form);
    }
}