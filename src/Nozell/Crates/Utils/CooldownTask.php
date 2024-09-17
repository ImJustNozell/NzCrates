<?php

namespace Nozell\Crates\Utils;

use pocketmine\scheduler\Task;
use pocketmine\player\Player;

class CooldownTask extends Task {

    private Player $player;
    private array $steps;
    private int $currentIndex;

    public function __construct(Player $player, array $steps) {
        $this->player = $player;
        $this->steps = $steps;
        $this->currentIndex = count($steps) - 1;
    }

    public function onRun(): void {
        if (!$this->player->isOnline() || $this->currentIndex < 0) {
            $this->getHandler()->cancel();
            return;
        }

        $step = $this->steps[$this->currentIndex--];

        if (empty($step['actions']) || !is_array($step['actions'])) return;

        array_walk($step['actions'], fn($action) => $action($this->player));
    }
}
