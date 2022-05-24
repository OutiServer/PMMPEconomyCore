<?php

namespace Ken_Cir\EconomyCore\Forms;

use jojoe77777\FormAPI\SimpleForm;
use Ken_Cir\EconomyCore\Forms\Base\BaseForm;
use Ken_Cir\EconomyCore\Forms\Economy\MoneyForm;
use pocketmine\player\Player;

class EconomyForm implements BaseForm
{
    public function execute(Player $player): void
    {
        $form = new SimpleForm(function (Player $player, $data) {
            if ($data === null) return;
            elseif ($data === 0) {
                (new MoneyForm())->execute($player);
            }
        });

        $form->setTitle("[EconomyCore] Form");
        $form->addButton("所持金の確認");
        $player->sendForm($form);
    }
}