<?php

declare(strict_types=1);

namespace Nozell\Crates\Meetings;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\world\World;
use Nozell\Crates\Utils\CratesUtils;
use Nozell\Crates\Data\CratesData;

final class Meeting {
    
    private CratesData $CratesData;
    
    public function __construct(
        private readonly Player $player) {
        $this->CratesData = new CratesData($this);
    }

    public function getCratesData(): CratesData {
        return $this->CratesData;
    }

    public function getPlayer(): Player {
        return $this->player;
    }

    public function getXuid(): string {
        return $this->player->getXuid();
    }

    public function join(): void {
        
        $player = $this->player;
        $player->sendMessage(TextFormat::colorize('&aCargando Tus Datos ...'));
        $this->CratesData->setKeyMage(CratesUtils::getKeyBox($player, "mage"));
        $this->CratesData->setKeyIce(CratesUtils::getKeyBox($player, "ice"));
        $this->CratesData->setKeyEnder(CratesUtils::getKeyBox($player, "ender"));
        $this->CratesData->setKeyMagma(CratesUtils::getKeyBox($player, "magma"));
        $this->CratesData->setKeyPegasus(CratesUtils::getKeyBox($player, "pegasus"));
        $player->sendMessage(TextFormat::colorize('&aDatos cargados correctamente'));
        
    }

    public function Close(bool $onClose = false): void {
        $player = $this->player; 
        CratesUtils::setKeyBox($player, "mage", $this->CratesData->getKeyMage());
        CratesUtils::setKeyBox($player, "ice", $this->CratesData->getKeyIce());
        CratesUtils::setKeyBox($player, "ender", $this->CratesData->getKeyEnder());
        CratesUtils::setKeyBox($player, "magma", $this->CratesData->getKeyMagma());
        CratesUtils::setKeyBox($player, "pegasus", $this->CratesData->getKeyPegasus());
        
MeetingManager::getInstance()->removeMeeting($player);
    }
}
