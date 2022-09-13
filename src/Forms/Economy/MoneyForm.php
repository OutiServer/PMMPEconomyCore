<?php

declare(strict_types=1);

namespace outiserver\economycore\Forms\Economy;

use Ken_Cir\LibFormAPI\Utils\FormUtil;
use outiserver\economycore\Database\Economy\EconomyDataManager;
use outiserver\economycore\Database\Player\PlayerData;
use outiserver\economycore\Database\Player\PlayerDataManager;
use outiserver\economycore\EconomyCore;
use outiserver\economycore\Forms\Base\BaseForm;
use outiserver\economycore\Forms\Utils\PlayerSelectorForm;
use Ken_Cir\LibFormAPI\FormContents\CustomForm\ContentInput;
use Ken_Cir\LibFormAPI\FormContents\CustomForm\ContentLabel;
use Ken_Cir\LibFormAPI\Forms\CustomForm;
use outiserver\economycore\Language\LanguageManager;
use pocketmine\player\Player;

class MoneyForm implements BaseForm
{
    public const FORM_KEY = "economy_money_form";

    public function execute(Player $player): void
    {
        $form = new CustomForm(EconomyCore::getInstance(),
            $player,
            LanguageManager::getInstance()->getLanguage($player->getLocale())->translateString("form.money.title"),
            [
                new ContentInput(LanguageManager::getInstance()->getLanguage($player->getLocale())->translateString("form.money.input1"), "playerName", requirement: false),
                new ContentLabel(LanguageManager::getInstance()->getLanguage($player->getLocale())->translateString("form.money.label1"))
            ],
            function (Player $player, array $data): void {
                if (!$data[0]) {
                    $economyData = EconomyDataManager::getInstance()->get($player->getXuid());
                    $player->sendMessage(LanguageManager::getInstance()->getLanguage($player->getLocale())->translateString("form.money.success.me", [$economyData->getMoney()]));
                } elseif ($playerData = PlayerDataManager::getInstance()->getName($data[0])) {
                    $economyData = EconomyDataManager::getInstance()->get($playerData->getXuid());
                    $player->sendMessage(LanguageManager::getInstance()->getLanguage($player->getLocale())->translateString("form.money.success.player", [$playerData->getName(), $economyData->getMoney()]));
                } else {
                    $playerSelectorForm = new PlayerSelectorForm();
                    $playerSelectorForm->execute($player, $data[0], function (Player $player, PlayerData $playerData): void {
                        $economyData = EconomyDataManager::getInstance()->get($playerData->getXuid());
                        $player->sendMessage(LanguageManager::getInstance()->getLanguage($player->getLocale())->translateString("form.money.success.player", [$playerData->getName(), $economyData->getMoney()]));
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