<?php

namespace Nozell\Crates\Menu;

use pocketmine\player\Player;
use Nozell\Crates\Entity\MageBoxEntity;
use Nozell\Crates\Entity\EnderBoxEntity;
use Nozell\Crates\Entity\PegasusBoxEntity;
use Nozell\Crates\Entity\IceBoxEntity;
use Nozell\Crates\Entity\MagmaBoxEntity;
use Vecnavium\FormsUI\CustomForm;
use pocketmine\nbt\tag\CompoundTag;
use Nozell\Crates\Manager\LangManager;

class SpawnBoxMenu extends CustomForm
{
    private array $crateTypes;

    public function __construct(Player $player)
    {
        parent::__construct(function (Player $player, $data) {
            $this->handleResponse($player, $data);
        });

        $this->crateTypes = ["mage", "ice", "ender", "magma", "pegasus"];

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

        switch ($crateType) {
            case "mage":
                new MageBoxEntity($player->getLocation(), new CompoundTag());
                break;
            case "ice":
                new IceBoxEntity($player->getLocation(), new CompoundTag());
                break;
            case "ender":
                new EnderBoxEntity($player->getLocation(), new CompoundTag());
                break;
            case "magma":
                new MagmaBoxEntity($player->getLocation(), new CompoundTag());
                break;
            case "pegasus":
                new PegasusBoxEntity($player->getLocation(), new CompoundTag());
                break;
        }

        $player->sendMessage(
            LangManager::getInstance()->generateMsg(
                "crate-spawned",
                ["{crateType}"],
                [$crateType]
            )
        );
    }
}
