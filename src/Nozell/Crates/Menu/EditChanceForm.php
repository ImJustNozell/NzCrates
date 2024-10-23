<?php

namespace Nozell\Crates\Menu;

use pocketmine\player\Player;
use Vecnavium\FormsUI\CustomForm;
use Nozell\Crates\Rewards\Reward;
use Nozell\Crates\Manager\LangManager;

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
        $this->addLabel("Editing chance for item: " . $this->reward->getItem()->getName());
        $this->addInput("New chance:", "Enter new chance", (string)$this->reward->getChance());

        $player->sendForm($this);
    }

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null || !is_numeric($data[0])) {
            $player->sendMessage(LangManager::getInstance()->generateMsg("invalid-data", [], []));
            return;
        }

        $newChance = (float)$data[0];
        $this->reward->setChance($newChance);
        $player->sendMessage(LangManager::getInstance()->generateMsg("chance-updated", [], []));
    }
}
