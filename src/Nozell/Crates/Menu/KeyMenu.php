<?php

namespace Nozell\Crates\Menu;

use pocketmine\player\Player;
use Nozell\Crates\Meetings\MeetingManager;
use Vecnavium\FormsUI\SimpleForm;
use Nozell\Crates\Manager\LangManager;

final class KeyMenu extends SimpleForm
{

    public function __construct(Player $player)
    {
        parent::__construct(function (Player $player, $data) {
            $this->handleResponse($player, $data);
        });

        $meeting = MeetingManager::getInstance()->getMeeting($player)->getCratesData();

        $content = LangManager::getInstance()->generateMsg('keys-overview', [
            '{mageKeys}',
            '{iceKeys}',
            '{enderKeys}',
            '{magmaKeys}',
            '{pegasusKeys}'
        ], [
            $meeting->getKeyMage(),
            $meeting->getKeyIce(),
            $meeting->getKeyEnder(),
            $meeting->getKeyMagma(),
            $meeting->getKeyPegasus()
        ]);

        $this->setTitle(LangManager::getInstance()->generateMsg('form-title-keys', [], []));
        $this->setContent($content);
        $this->addButton(LangManager::getInstance()->generateMsg('form-button-close', [], []));

        $player->sendForm($this);
    }

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null) {
            return;
        }
    }
}
