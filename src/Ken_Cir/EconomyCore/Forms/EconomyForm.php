<?php

namespace Ken_Cir\EconomyCore\Forms;

use Ken_Cir\EconomyCore\EconomyCore;
use Ken_Cir\EconomyCore\Forms\Base\BaseForm;
use Ken_Cir\EconomyCore\Forms\Economy\MoneyForm;
use Ken_Cir\LibFormAPI\FormContents\SimpleForm\SimpleFormButton;
use Ken_Cir\LibFormAPI\Forms\SimpleForm;
use pocketmine\player\Player;
use pocketmine\Server;

class EconomyForm implements BaseForm
{
    public function execute(Player $player): void
    {
        $form = new SimpleForm($player,
        "[EconomyCore] Form",
        "",
        [
            new SimpleFormButton("所持金の確認"),
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