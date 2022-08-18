<?php

namespace outiserver\economycore\Forms;

use outiserver\economycore\EconomyCore;
use outiserver\economycore\Forms\Base\BaseForm;
use outiserver\economycore\Forms\Economy\MoneyForm;
use Ken_Cir\LibFormAPI\FormContents\SimpleForm\SimpleFormButton;
use Ken_Cir\LibFormAPI\Forms\SimpleForm;
use pocketmine\player\Player;
use pocketmine\Server;

class EconomyForm implements BaseForm
{
    public function execute(Player $player): void
    {
        $form = new SimpleForm(EconomyCore::getInstance(),
            $player,
        EconomyCore::getInstance()->getLanguageManager()->getLanguage($player->getLocale())->translateString("form.economyform.title"),
        "",
        [
            new SimpleFormButton(EconomyCore::getInstance()->getLanguageManager()->getLanguage($player->getLocale())->translateString("form.economyform.button1")),
        ],
        function (Player $player, int $data): void {
            if ($data === 0) {
                (new MoneyForm())->execute($player);
            }
        },
        function (Player $player): void {
            EconomyCore::getInstance()->getStackFormManager()->deleteStack($player->getXuid());
        }
        );

        EconomyCore::getInstance()->getStackFormManager()->addStackForm($player->getXuid(), "economy_form", $form);
    }
}