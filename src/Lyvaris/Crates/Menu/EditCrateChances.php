<?php

namespace Lyvaris\Crates\Menu;

use pocketmine\player\Player;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\SimpleInvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use Lyvaris\Crates\Rewards\RewardManager;
use Lyvaris\Crates\Manager\LangManager;
use pocketmine\scheduler\ClosureTask;
use Lyvaris\Crates\Main;

class EditCrateChances
{
    private string $crateType;
    private RewardManager $rewardManager;

    public function __construct(Player $player, string $crateType)
    {
        $this->crateType = $crateType;
        $this->rewardManager = RewardManager::getInstance();
        $this->openMenu($player);
    }

    public function openMenu(Player $player): void
    {
        $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $menu->setName(LangManager::getInstance()->generateMsg("edit-chances-menu", ["{crateType}"], [ucfirst($this->crateType)]));

        $rewards = $this->rewardManager->getRewardsForCrate($this->crateType);
        $inventory = $menu->getInventory();

        foreach ($rewards as $reward) {
            $item = $reward->getItem();
            $chance = $reward->getChance();
            $lore = ["Chance: " . $chance . "%"];
            $item->setLore($lore);
            $inventory->setItem($reward->getSlot(), $item);
        }

        $menu->setListener(function (SimpleInvMenuTransaction $transaction) use ($player): InvMenuTransactionResult {
            $slot = $transaction->getAction()->getSlot();
            $reward = $this->rewardManager->getRewardForSlot($this->crateType, $slot);

            if ($reward !== null) {
                $transaction->getPlayer()->removeCurrentWindow();

                Main::getInstance()->getScheduler()->scheduleDelayedTask(
                    new ClosureTask(function () use ($player, $reward): void {
                        new EditChanceForm($player, $reward);
                    }),
                    10
                );
            }

            return $transaction->discard();
        });

        $menu->send($player);
    }
}
