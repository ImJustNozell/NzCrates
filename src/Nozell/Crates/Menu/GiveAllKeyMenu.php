<?php

namespace Nozell\Crates\Menu;

use pocketmine\player\Player;
use pocketmine\Server;
use Nozell\Crates\Main;
use Nozell\Crates\Meetings\MeetingManager;
use Nozell\Crates\libs\FormAPI\CustomForm;

final class GiveAllKeyMenu extends CustomForm {

    private array $keyTypes;

    public function __construct(Player $player) {
        parent::__construct(null);

        $this->keyTypes = ["mage", "ice", "ender", "magma", "pegasus"];

        $this->setTitle("Dar Keys a Todos");
        $this->addDropdown("Selecciona el tipo de key", $this->keyTypes);
        $this->addInput("Cantidad", "Ingresa la cantidad de keys");
        $player->sendForm($this);
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null || !isset($this->keyTypes[$data[0]]) || $data[1] === '' || $data[1] <= 0 || !ctype_digit($data[1])) {
            $player->sendMessage("§cDatos inválidos proporcionados.");
            return;
        }

        $keyType = $this->keyTypes[$data[0]];
        $amount = (int)$data[1];

        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
            $meeting = MeetingManager::getInstance()->getMeeting($onlinePlayer)->getCratesData();

            match($keyType) {
                "mage" => $meeting->addKeyMage($amount),
                "ice" => $meeting->addKeyIce($amount),
                "ender" => $meeting->addKeyEnder($amount),
                "magma" => $meeting->addKeyMagma($amount),
                "pegasus" => $meeting->addKeyPegasus($amount),
                default => $player->sendMessage("§cTipo de key desconocido.")
            };

            $onlinePlayer->sendMessage("§bHas recibido §e{$amount} keys de tipo {$keyType}");
        }

        $player->sendMessage("§aHas dado exitosamente §e{$amount} keys de tipo {$keyType} §aa todos los jugadores en línea.");
    }
}
