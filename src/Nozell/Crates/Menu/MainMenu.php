<?php

namespace Nozell\Crates\Menu;

use pocketmine\player\Player;
use Vecnavium\FormsUI\SimpleForm;
use Nozell\Crates\Manager\LangManager;
use Nozell\Crates\tags\Perms;

class MainMenu extends SimpleForm
{
    public function __construct(Player $player)
    {
        parent::__construct([$this, "handleResponse"]);

        $this->setTitle(LangManager::getInstance()->generateMsg("form-title-main-menu", [], []));
        $this->setContent(LangManager::getInstance()->generateMsg("form-content-select-option", [], []));

        $this->addButton(LangManager::getInstance()->generateMsg("form-button-give-all-keys", [], []));
        $this->addButton(LangManager::getInstance()->generateMsg("form-button-give-key", [], []));
        $this->addButton(LangManager::getInstance()->generateMsg("form-button-view-keys", [], []));
        $this->addButton(LangManager::getInstance()->generateMsg("form-button-set-items", [], []));
        $this->addButton(LangManager::getInstance()->generateMsg("form-button-spawn-crate", [], []));

        $player->sendForm($this);
    }

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null) {
            return;
        }

        switch ($data) {
            case 0:
                $this->handleGiveAllKeys($player);
                break;

            case 1:
                $this->handleGiveKey($player);
                break;

            case 2:
                $this->handleViewKeys($player);
                break;

            case 3:
                $this->handleSetItems($player);
                break;

            case 4:
                $this->handleSpawnCrate($player);
                break;
        }
    }

    private function handleGiveAllKeys(Player $player): void
    {
        if ($player->hasPermission(Perms::Admin)) {
            new GiveAllKeyMenu($player);
        } else {
            $player->sendMessage(LangManager::getInstance()->generateMsg("no-permission", [], []));
        }
    }

    private function handleGiveKey(Player $player): void
    {
        if ($player->hasPermission(Perms::Admin)) {
            new GiveKeyMenu($player);
        } else {
            $player->sendMessage(LangManager::getInstance()->generateMsg("no-permission", [], []));
        }
    }

    private function handleViewKeys(Player $player): void
    {
        if ($player->hasPermission(Perms::Default)) {
            new KeyMenu($player);
        } else {
            $player->sendMessage(LangManager::getInstance()->generateMsg("no-permission", [], []));
        }
    }

    private function handleSetItems(Player $player): void
    {
        if ($player->hasPermission(Perms::Admin)) {
            new SelectCrateType($player);
        } else {
            $player->sendMessage(LangManager::getInstance()->generateMsg("no-permission", [], []));
        }
    }

    private function handleSpawnCrate(Player $player): void
    {
        if ($player->hasPermission(Perms::Admin)) {
            new SpawnBoxMenu($player);
        } else {
            $player->sendMessage(LangManager::getInstance()->generateMsg("no-permission", [], []));
        }
    }
}
