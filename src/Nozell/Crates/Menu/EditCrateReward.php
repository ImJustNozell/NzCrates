<?php

namespace Nozell\Crates\Menu;

use pocketmine\player\Player;
use Vecnavium\FormsUI\SimpleForm;
use Nozell\Crates\Manager\LangManager;

class EditCrateReward extends SimpleForm
{
    private string $crateType;

    public function __construct(Player $player, string $crateType)
    {
        $this->crateType = $crateType;
        parent::__construct(null);

        $this->setTitle(LangManager::getInstance()->generateMsg("edit-crate-rewards", [], []));
        $this->addButton(LangManager::getInstance()->generateMsg("edit-crate-items", [], []));
        $this->addButton(LangManager::getInstance()->generateMsg("edit-crate-chances", [], []));

        $player->sendForm($this);
    }

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null) {
            return;
        }

        switch ($data) {
            case 0:
                new SetItemsMenu($player, $this->crateType);
                break;
            case 1:
                new EditCrateChances($player, $this->crateType);
                break;
        }
    }
}
