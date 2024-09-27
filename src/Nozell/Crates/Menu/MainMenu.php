<?php

namespace Nozell\Crates\Menu;

use pocketmine\player\Player;
use Vecnavium\FormsUI\SimpleForm;
use Nozell\Crates\Manager\LangManager;

class MainMenu extends SimpleForm
{

    public function __construct(Player $player)
    {
        parent::__construct(function (Player $player, $data) {
            $this->handleResponse($player, $data);
        });

        $this->setTitle(LangManager::getInstance()->generateMsg('form-title-main-menu', [], []));
        $this->setContent(LangManager::getInstance()->generateMsg('form-content-select-option', [], []));

        $this->addButton(LangManager::getInstance()->generateMsg('form-button-give-all-keys', [], []));
        $this->addButton(LangManager::getInstance()->generateMsg('form-button-give-key', [], []));
        $this->addButton(LangManager::getInstance()->generateMsg('form-button-view-keys', [], []));
        $this->addButton(LangManager::getInstance()->generateMsg('form-button-set-items', [], []));
        $this->addButton(LangManager::getInstance()->generateMsg('form-button-spawn-crate', [], []));

        $player->sendForm($this);
    }

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null) {
            return;
        }

        switch ($data) {
            case 0:
                if ($player->hasPermission("box.give.all")) {
                    new GiveAllKeyMenu($player);
                } else {
                    $player->sendMessage(LangManager::getInstance()->generateMsg('no-permission', [], []));
                }
                break;
            case 1:
                if ($player->hasPermission("box.give")) {
                    new GiveKeyMenu($player);
                } else {
                    $player->sendMessage(LangManager::getInstance()->generateMsg('no-permission', [], []));
                }
                break;
            case 2:
                if ($player->hasPermission("keys.info")) {
                    new KeyMenu($player);
                } else {
                    $player->sendMessage(LangManager::getInstance()->generateMsg('no-permission', [], []));
                }
                break;
            case 3:
                if ($player->hasPermission("box.spawn")) {
                    new SetItemsMenu($player);
                } else {
                    $player->sendMessage(LangManager::getInstance()->generateMsg('no-permission', [], []));
                }
                break;
            case 4:
                if ($player->hasPermission("box.spawn")) {
                    new SpawnBoxMenu($player);
                } else {
                    $player->sendMessage(LangManager::getInstance()->generateMsg('no-permission', [], []));
                }
                break;
        }
    }
}
