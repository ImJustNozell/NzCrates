<?php

namespace Nozell\Crates\Menu;

use pocketmine\player\Player;
use pocketmine\Server;
use Nozell\Crates\Meetings\MeetingManager;
use Vecnavium\FormsUI\CustomForm;
use Nozell\Crates\Manager\LangManager;

final class GiveAllKeyMenu extends CustomForm
{
    private array $keyTypes;

    public function __construct(Player $player)
    {
        $this->keyTypes = ["mage", "ice", "ender", "magma", "pegasus"];

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

        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
            $meeting = MeetingManager::getInstance()
                ->getMeeting($onlinePlayer)
                ->getCratesData();

            match ($keyType) {
                "mage" => $meeting->addKeyMage($amount),
                "ice" => $meeting->addKeyIce($amount),
                "ender" => $meeting->addKeyEnder($amount),
                "magma" => $meeting->addKeyMagma($amount),
                "pegasus" => $meeting->addKeyPegasus($amount),
                default => $player->sendMessage(
                    LangManager::getInstance()->generateMsg(
                        "unknown-key-type",
                        [],
                        []
                    )
                ),
            };

            $msg = LangManager::getInstance()->generateMsg(
                "received-keys",
                ["{amount}", "{keyType}"],
                [$amount, $keyType]
            );
            $onlinePlayer->sendMessage($msg);
        }

        $msg = LangManager::getInstance()->generateMsg(
            "given-keys",
            ["{amount}", "{keyType}"],
            [$amount, $keyType]
        );
        $player->sendMessage($msg);
    }
}
