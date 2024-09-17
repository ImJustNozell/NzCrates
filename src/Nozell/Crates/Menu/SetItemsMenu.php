<?php

namespace Nozell\Crates\Menu;

use pocketmine\player\Player;
use pocketmine\Server;
use Nozell\Crates\Main;
use Nozell\Crates\libs\FormAPI\CustomForm;
use Nozell\Crates\libs\muqsit\invmenu\InvMenu;
use pocketmine\inventory\Inventory;
use pocketmine\utils\TextFormat;

class SetItemsMenu extends CustomForm {

    private array $crateTypes;

    public function __construct(Player $player) {
        parent::__construct(null);

        $this->crateTypes = ["mage", "ice", "ender", "magma", "pegasus"];

        $this->setTitle("Definir Items para Crate");
        $this->addDropdown("Selecciona el tipo de crate", $this->crateTypes);

        $player->sendForm($this);
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null || !isset($this->crateTypes[$data[0]])) {
            $player->sendMessage(TextFormat::RED . "Datos inválidos proporcionados.");
            return;
        }

        $crateType = $this->crateTypes[$data[0]];

        $this->openCrateMenu($player, $crateType);
    }

    public function openCrateMenu(Player $player, string $crateType): void {
        $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $menu->setName("Crate: " . ucfirst($crateType));

        $crateManager = Main::getInstance()->getCrateManager();
        $items = $crateManager->getCrateItems($crateType);

        $inventory = $menu->getInventory();
        foreach ($items as $item) {
            $inventory->addItem($item);
        }

        $menu->setInventoryCloseListener(function (Player $player, Inventory $inventory) use ($crateType): void {
            $crateManager = Main::getInstance()->getCrateManager();
            $crateItems = [];

            foreach ($inventory->getContents() as $item) {
                $crateItems[] = $item;
            }

            $crateManager->addCrateItems($crateType, $crateItems);
            $player->sendMessage(TextFormat::GREEN . "Items guardados en el crate '$crateType'.");
        });

        $menu->send($player);
    }
}
