<?php

declare(strict_types=1);

namespace Ken_Cir\EconomyCore\Forms\Base;

use pocketmine\player\Player;

interface BaseForm
{
    public function execute(Player $player): void;
}