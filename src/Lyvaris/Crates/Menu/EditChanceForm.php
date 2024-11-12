<?php

namespace Lyvaris\Crates\Menu;

use pocketmine\player\Player;
use Vecnavium\FormsUI\CustomForm;
use Lyvaris\Crates\Rewards\Reward;
use Lyvaris\Crates\Manager\LangManager;

class EditChanceForm extends CustomForm
{
    private Reward $reward;

    public function __construct(Player $player, Reward $reward)
    {
        $this->reward = $reward;

        parent::__construct(function (Player $player, $data) {
            $this->handleResponse($player, $data);
        });

        $this->setTitle(LangManager::getInstance()->generateMsg("edit-chance-title", [], []));

        $this->addLabel(LangManager::getInstance()->generateMsg("edit-item-label", ["{itemName}"], [$this->reward->getItem()->getName()]));

        $this->addInput(LangManager::getInstance()->generateMsg("new-chance-label", [], []), LangManager::getInstance()->generateMsg("new-chance-placeholder", [], []), (string)$this->reward->getChance());

        $player->sendForm($this);
    }

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null || !isset($data[1])) {
            $player->sendMessage(LangManager::getInstance()->generateMsg("invalid-data", [], []));
            return;
        }

        $newChance = trim($data[1]);
        if (!is_numeric($newChance)) {
            $player->sendMessage(LangManager::getInstance()->generateMsg("invalid-data", [], []));
            return;
        }

        $this->reward->setChance((float)$newChance);

        $player->sendMessage(LangManager::getInstance()->generateMsg("chance-updated", [], []));
    }
}
