<?php

namespace Lyvaris\Crates\Menu;

use pocketmine\player\Player;
use muqsit\invmenu\InvMenu;
use pocketmine\inventory\Inventory;
use Lyvaris\Crates\Rewards\Reward;
use Lyvaris\Crates\Rewards\RewardManager;
use Lyvaris\Crates\Manager\LangManager;

class SetItemsMenu
{
    private RewardManager $rewardManager;
    private string $crateType;

    public function __construct(Player $player, string $crateType)
    {
        $this->crateType = $crateType;
        $this->rewardManager = RewardManager::getInstance();

        $this->openMenu($player);
    }

    public function openMenu(Player $player): void
    {
        $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $menu->setName(LangManager::getInstance()->generateMsg("set-items-menu", ["{crateType}"], [ucfirst($this->crateType)]));

        $rewards = $this->rewardManager->getRewardsForCrate($this->crateType);
        $inventory = $menu->getInventory();

        foreach ($rewards as $reward) {
            $inventory->setItem($reward->getSlot(), $reward->getItem());
        }

        $menu->setInventoryCloseListener(function (Player $closingPlayer, Inventory $inventory) use ($player): void {
            $crateItems = [];

            foreach ($inventory->getContents() as $slot => $item) {
                $existingReward = $this->rewardManager->getRewardForSlot($this->crateType, $slot);
                $chance = $existingReward ? $existingReward->getChance() : 0.1;

                $crateItems[] = new Reward($item, $chance, $slot);
            }

            $this->rewardManager->clearRewardsForCrate($this->crateType);
            foreach ($crateItems as $reward) {
                $this->rewardManager->addRewardToCrate($this->crateType, $reward);
            }

            $player->sendMessage(LangManager::getInstance()->generateMsg("items-saved", ["{crateType}"], [$this->crateType]));
        });

        $menu->send($player);
    }
}
