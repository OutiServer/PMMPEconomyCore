<?php

declare(strict_types=1);

namespace outiserver\economycore\Forms\Base;

use pocketmine\player\Player;

interface BaseForm
{
    public function execute(Player $player): void;
}