<?php

namespace Nozell\Crates\Menu;

use pocketmine\player\Player;
use Nozell\Crates\libs\FormAPI\SimpleForm;

class MainMenu extends SimpleForm {

    public function __construct(Player $player) {
        parent::__construct(null);

        $this->setTitle("§l§6Main Menu");
        $this->setContent("§eSelecciona una opción:");

        if ($player->hasPermission("box.give.all")) {
            $this->addButton("§bGive All Keys");
        } else {
            $this->addButton("§7Give All Keys\n§cBloqueado");
        }
        if ($player->hasPermission("box.give")) {
            $this->addButton("§aGive Key");
        } else {
            $this->addButton("§7Give Key\n§cBloqueado");
        }
        if ($player->hasPermission("keys.info")) {
            $this->addButton("§dView Keys");
        } else {
            $this->addButton("§7View Keys\n§cBloqueado");
        }
        if ($player->hasPermission("box.spawn")) {
            $this->addButton("§cSet Items for Crate");
        } else {
            $this->addButton("§7Set Items for Crate\n§cBloqueado");
        }
        if ($player->hasPermission("box.spawn")) {
            $this->addButton("§6Spawn Crate");
        } else {
            $this->addButton("§7Spawn Crate\n§cBloqueado");
        }

        $player->sendForm($this);
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }

        switch ($data) {
            case 0:
                if ($player->hasPermission("box.give.all")) {
                    new GiveAllKeyMenu($player);
                } else {
                    $player->sendMessage("§cNo tienes permiso para usar esta opción.");
                }
                break;
            case 1:
                if ($player->hasPermission("box.give")) {
                    new GiveKeyMenu($player);
                } else {
                    $player->sendMessage("§cNo tienes permiso para usar esta opción.");
                }
                break;
            case 2:
                if ($player->hasPermission("keys.info")) {
                    new KeyMenu($player);
                } else {
                    $player->sendMessage("§cNo tienes permiso para usar esta opción.");
                }
                break;
            case 3:
                if ($player->hasPermission("box.spawn")) {
                    new SetItemsMenu($player);
                } else {
                    $player->sendMessage("§cNo tienes permiso para usar esta opción.");
                }
                break;
            case 4:
                if ($player->hasPermission("box.spawn")) {
                    new SpawnBoxMenu($player);
                } else {
                    $player->sendMessage("§cNo tienes permiso para usar esta opción.");
                }
                break;
        }
    }
}
