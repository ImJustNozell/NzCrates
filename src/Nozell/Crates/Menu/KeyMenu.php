<?php

namespace Nozell\Crates\Menu;

use pocketmine\player\Player;
use Nozell\Crates\libs\FormAPI\SimpleForm;
use Nozell\Crates\Meetings\MeetingManager;

final class KeyMenu extends SimpleForm {

    public function __construct(Player $player) {
        parent::__construct(null);
        $meeting = MeetingManager::getInstance()->getMeeting($player)->getCratesData();

        $content = "§bTienes actualmente:\n" .
                   "- §e{$meeting->getKeyMage()}§f Mage\n" .
                   "- §e{$meeting->getKeyIce()}§f Ice\n" .
                   "- §e{$meeting->getKeyEnder()}§f Ender\n" .
                   "- §e{$meeting->getKeyMagma()}§f Magma\n" .
                   "- §e{$meeting->getKeyPegasus()}§f Pegasus";

        $this->setTitle("Tus Keys");
        $this->setContent($content);
        $this->addButton("Cerrar");
$player->sendForm($this);
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
    }
}
