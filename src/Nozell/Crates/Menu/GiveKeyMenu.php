<?php

namespace Nozell\Crates\Menu;

use pocketmine\player\Player;
use pocketmine\Server;
use Vecnavium\FormsUI\CustomForm;
use Nozell\Crates\Meetings\MeetingManager;
use Nozell\Crates\Manager\LangManager;

final class GiveKeyMenu extends CustomForm
{
    private array $keyTypes;
    private array $onlinePlayers;

    public function __construct(Player $player)
    {
        parent::__construct(null);

        $this->keyTypes = ["mage", "ice", "ender", "magma", "pegasus"];
        $this->onlinePlayers = array_map(
            fn(Player $p) => $p->getName(),
            array_values(Server::getInstance()->getOnlinePlayers())
        );

        $this->setTitle(
            LangManager::getInstance()->generateMsg(
                "form-title-give-key",
                [],
                []
            )
        );
        $this->addDropdown(
            LangManager::getInstance()->generateMsg(
                "form-dropdown-key-type",
                [],
                []
            ),
            $this->keyTypes
        );
        $this->addInput(
            LangManager::getInstance()->generateMsg(
                "form-input-amount",
                [],
                []
            ),
            LangManager::getInstance()->generateMsg(
                "form-input-amount-placeholder",
                [],
                []
            )
        );
        $this->addDropdown(
            LangManager::getInstance()->generateMsg(
                "form-dropdown-player",
                [],
                []
            ),
            $this->onlinePlayers
        );

        $player->sendForm($this);
    }

    public function handleResponse(Player $player, $data): void
    {
        if (
            $data === null ||
            !isset($this->keyTypes[$data[0]]) ||
            !is_numeric($data[1]) ||
            $data[1] <= 0 ||
            !isset($this->onlinePlayers[$data[2]])
        ) {
            $player->sendMessage(
                LangManager::getInstance()->generateMsg("invalid-data", [], [])
            );
            return;
        }

        $keyType = $this->keyTypes[$data[0]];
        $amount = (int) $data[1];
        $targetPlayerName = $this->onlinePlayers[$data[2]];
        $targetPlayer = Server::getInstance()->getPlayerExact(
            $targetPlayerName
        );

        if ($targetPlayer === null) {
            $player->sendMessage(
                LangManager::getInstance()->generateMsg(
                    "player-not-online",
                    [],
                    []
                )
            );
            return;
        }

        $meeting = MeetingManager::getInstance()
            ->getMeeting($targetPlayer)
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
            "given-keys-to-player",
            ["{amount}", "{keyType}", "{playerName}"],
            [$amount, $keyType, $targetPlayer->getName()]
        );
        $player->sendMessage($msg);
    }
}
