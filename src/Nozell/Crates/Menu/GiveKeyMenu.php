<?php

namespace Nozell\Crates\Menu;

use pocketmine\player\Player;
use pocketmine\Server;
use Nozell\Main;
use Nozell\Crates\libs\FormAPI\CustomForm;
use Nozell\Crates\Meetings\MeetingManager;

final class GiveKeyMenu extends CustomForm {

    private array $keyTypes;
    private array $onlinePlayers;

    public function __construct(Player $player) {
        parent::__construct(null);

        $this->keyTypes = ["mage", "ice", "ender", "magma", "pegasus"];
        $this->onlinePlayers = array_map(fn(Player $p) => $p->getName(), array_values(Server::getInstance()->getOnlinePlayers()));

        $this->setTitle("Dar Key");
        $this->addDropdown("Selecciona el tipo de key", $this->keyTypes);
        $this->addInput("Cantidad", "Ingresa la cantidad de keys");
        $this->addDropdown("Selecciona el jugador", $this->onlinePlayers);
        $player->sendForm($this);
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null || !isset($this->keyTypes[$data[0]]) || !is_numeric($data[1]) || $data[1] <= 0 || !isset($this->onlinePlayers[$data[2]])) {
            $player->sendMessage("§cDatos inválidos proporcionados.");
            return;
        }
        

        $keyType = $this->keyTypes[$data[0]];
        $amount = (int)$data[1];
        $targetPlayerName = $this->onlinePlayers[$data[2]];
        $targetPlayer = Server::getInstance()->getPlayerExact($targetPlayerName);

        if ($targetPlayer === null) {
            $player->sendMessage("§cEl jugador seleccionado no está en línea.");
            return;
        }
        $meeting = MeetingManager::getInstance()->getMeeting($targetPlayer)->getCratesData();

        switch ($keyType) {
            case "mage":
                $meeting->addKeyMage($amount);
                break;
            case "ice":
                $meeting->addKeyIce($amount);
                break;
            case "ender":
                $meeting->addKeyEnder($amount);
                break;
            case "magma":
                $meeting->addKeyMagma($amount);
                break;
            case "pegasus":
                $meeting->addKeyPegasus($amount);
                break;
            default:
                $player->sendMessage("§cTipo de key desconocido.");
                return;
        }

        $player->sendMessage("§aHas dado exitosamente §e{$amount} keys de tipo {$keyType} §aa {$targetPlayer->getName()}.");
    }
}
