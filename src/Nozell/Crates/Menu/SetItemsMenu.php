<?php

namespace Nozell\Crates\Menu;

use pocketmine\player\Player;
use Nozell\Crates\Main;
use Vecnavium\FormsUI\CustomForm;
use muqsit\invmenu\InvMenu;
use Nozell\Crates\Manager\CrateManager;
use pocketmine\inventory\Inventory;
use Nozell\Crates\Manager\LangManager;

class SetItemsMenu extends CustomForm
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
                "form-title-set-items",
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

        $this->openCrateMenu($player, $crateType);
    }

    public function openCrateMenu(Player $player, string $crateType): void
    {
        $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $menu->setName(
            LangManager::getInstance()->generateMsg(
                "crate-title",
                ["{crateType}"],
                [ucfirst($crateType)]
            )
        );

        $crateManager = CrateManager::getInstance();
        $items = $crateManager->getCrateItems($crateType);

        $inventory = $menu->getInventory();
        foreach ($items as $item) {
            $inventory->addItem($item);
        }

        $menu->setInventoryCloseListener(function (
            Player $player,
            Inventory $inventory
        ) use ($crateType): void {
            $crateManager = CrateManager::getInstance();
            $crateItems = [];

            foreach ($inventory->getContents() as $item) {
                $crateItems[] = $item;
            }

            $crateManager->addCrateItems($crateType, $crateItems);
            $player->sendMessage(
                LangManager::getInstance()->generateMsg(
                    "items-saved",
                    ["{crateType}"],
                    [$crateType]
                )
            );
        });

        $menu->send($player);
    }
}
