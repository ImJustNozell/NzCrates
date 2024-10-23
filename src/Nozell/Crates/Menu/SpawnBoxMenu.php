<?php

namespace Nozell\Crates\Menu;

use pocketmine\player\Player;
use pocketmine\Server;
use Vecnavium\FormsUI\CustomForm;
use Nozell\Crates\Events\SpawnCrateEvent;
use Nozell\Crates\Manager\LangManager;
use Nozell\Crates\tags\Names;

class SpawnBoxMenu extends CustomForm
{
    private array $crateTypes;

    public function __construct(Player $player)
    {
        parent::__construct(function (Player $player, $data) {
            $this->handleResponse($player, $data);
        });

        $this->crateTypes = [Names::Mage, Names::Ice, Names::Ender, Names::Magma, Names::Pegasus];

        $this->setTitle(
            LangManager::getInstance()->generateMsg(
                "form-title-spawn-crate",
                [],
                []
            )
        );
        $this->addDropdown(
            LangManager::getInstance()->generateMsg(
                "form-dropdown-crate-type",
                [],
                []
            ),
            $this->crateTypes
        );

        $player->sendForm($this);
    }

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null || !isset($this->crateTypes[$data[0]])) {
            $player->sendMessage(
                LangManager::getInstance()->generateMsg("invalid-data", [], [])
            );
            return;
        }

        $crateType = $this->crateTypes[$data[0]];

        $event = new SpawnCrateEvent($player, $crateType);
        $event->call();
    }
}
