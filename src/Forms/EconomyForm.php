<?php

namespace outiserver\economycore\Forms;

use outiserver\economycore\EconomyCore;
use outiserver\economycore\Forms\Admin\AddMoneyForm;
use outiserver\economycore\Forms\Admin\RemoveMoneyForm;
use outiserver\economycore\Forms\Admin\SetMoney;
use outiserver\economycore\Forms\Base\BaseForm;
use outiserver\economycore\Forms\Economy\MoneyForm;
use Ken_Cir\LibFormAPI\FormContents\SimpleForm\SimpleFormButton;
use Ken_Cir\LibFormAPI\Forms\SimpleForm;
use pocketmine\player\Player;
use pocketmine\Server;

class EconomyForm implements BaseForm
{
    public const FORM_KEY = "economy_form";

    public function execute(Player $player): void
    {
        $contents = [
            new SimpleFormButton(EconomyCore::getInstance()->getLanguageManager()->getLanguage($player->getLocale())->translateString("form.economyform.button1"))
        ];

        if (Server::getInstance()->isOp($player->getName())) {
            $contents[] = new SimpleFormButton(EconomyCore::getInstance()->getLanguageManager()->getLanguage($player->getLocale())->translateString("form.economyform.button2"));
            $contents[] = new SimpleFormButton(EconomyCore::getInstance()->getLanguageManager()->getLanguage($player->getLocale())->translateString("form.economyform.button3"));
            $contents[] = new SimpleFormButton(EconomyCore::getInstance()->getLanguageManager()->getLanguage($player->getLocale())->translateString("form.economyform.button4"));
        }

        $form = new SimpleForm(EconomyCore::getInstance(),
            $player,
            EconomyCore::getInstance()->getLanguageManager()->getLanguage($player->getLocale())->translateString("form.economyform.title"),
            "",
            $contents,
            function (Player $player, int $data): void {
                if ($data === 0) {
                    (new MoneyForm())->execute($player);
                } elseif ($data === 1 and Server::getInstance()->isOp($player->getName())) {
                    (new AddMoneyForm())->execute($player);
                } elseif ($data === 2 and Server::getInstance()->isOp($player->getName())) {
                    (new RemoveMoneyForm())->execute($player);
                } elseif ($data === 3 and Server::getInstance()->isOp($player->getName())) {
                    (new SetMoney())->execute($player);
                }
            },
            function (Player $player): void {
                EconomyCore::getInstance()->getStackFormManager()->deleteStack($player->getXuid());
            });

        EconomyCore::getInstance()->getStackFormManager()->addStackForm($player->getXuid(), self::FORM_KEY, $form);
    }
}