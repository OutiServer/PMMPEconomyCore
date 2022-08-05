<?php

declare(strict_types=1);

namespace outiserver\economycore\Forms\Admin;

use Ken_Cir\LibFormAPI\Forms\CustomForm;
use outiserver\economycore\EconomyCore;
use outiserver\economycore\Forms\Base\BaseForm;
use pocketmine\player\Player;

class AddMoneyForm implements BaseForm
{
    public function execute(Player $player): void
    {
        $form = new CustomForm(EconomyCore::getInstance(),
        $player,
        "[Econom]");
    }
}