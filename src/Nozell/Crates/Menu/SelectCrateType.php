<?php

namespace Nozell\Crates\Menu;

use pocketmine\player\Player;
use Vecnavium\FormsUI\SimpleForm;
use Nozell\Crates\Manager\LangManager;
use Nozell\Crates\Tags\Names;

class SelectCrateType extends SimpleForm
{
    public function __construct(Player $player)
    {
        parent::__construct(null);

        $this->setTitle(LangManager::getInstance()->generateMsg("select-crate-type", [], []));
        $this->addButton(LangManager::getInstance()->generateMsg("mage-crate", [], []));
        $this->addButton(LangManager::getInstance()->generateMsg("ice-crate", [], []));
        $this->addButton(LangManager::getInstance()->generateMsg("ender-crate", [], []));
        $this->addButton(LangManager::getInstance()->generateMsg("magma-crate", [], []));
        $this->addButton(LangManager::getInstance()->generateMsg("pegasus-crate", [], []));

        $player->sendForm($this);
    }

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null) {
            return;
        }

        switch ($data) {
            case 0:
                new EditCrateReward($player, Names::Mage);
                break;
            case 1:
                new EditCrateReward($player, Names::Ice);
                break;
            case 2:
                new EditCrateReward($player, Names::Ender);
                break;
            case 3:
                new EditCrateReward($player, Names::Magma);
                break;
            case 4:
                new EditCrateReward($player, Names::Pegasus);
                break;
        }
    }
}
