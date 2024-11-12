<?php

namespace Lyvaris\Crates\Menu;

use pocketmine\player\Player;
use Vecnavium\FormsUI\CustomForm;
use Lyvaris\Crates\Manager\LangManager;
use Lyvaris\Crates\Events\GiveAllKeysEvent;
use Lyvaris\Crates\tags\Names;

final class GiveAllKeyMenu extends CustomForm
{
    private array $keyTypes;

    public function __construct(Player $player)
    {
        $this->keyTypes = [Names::Mage, Names::Ice, Names::Ender, Names::Magma, Names::Pegasus];

        $form = new CustomForm(function (Player $player, $data) {
            $this->handleResponse($player, $data);
        });

        $form->setTitle(
            LangManager::getInstance()->generateMsg("form-title", [], [])
        );
        $form->addDropdown(
            LangManager::getInstance()->generateMsg("form-dropdown", [], []),
            $this->keyTypes
        );
        $form->addInput(
            LangManager::getInstance()->generateMsg("form-input", [], []),
            LangManager::getInstance()->generateMsg(
                "form-input-placeholder",
                [],
                []
            )
        );

        $player->sendForm($form);
    }

    public function handleResponse(Player $player, $data): void
    {
        if (
            $data === null ||
            !isset($this->keyTypes[$data[0]]) ||
            $data[1] === "" ||
            $data[1] <= 0 ||
            !ctype_digit($data[1])
        ) {
            $msg = LangManager::getInstance()->generateMsg(
                "invalid-data",
                [],
                []
            );
            $player->sendMessage($msg);
            return;
        }

        $keyType = $this->keyTypes[$data[0]];
        $amount = (int) $data[1];

        $event = new GiveAllKeysEvent($player, $keyType, $amount);
        $event->call();

        $msg = LangManager::getInstance()->generateMsg(
            "given-keys",
            ["{amount}", "{keyType}"],
            [$amount, $keyType]
        );
        $player->sendMessage($msg);
    }
}
