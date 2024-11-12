<?php

namespace Lyvaris\Crates\Menu;

use pocketmine\player\Player;
use Vecnavium\FormsUI\SimpleForm;
use Lyvaris\Crates\Manager\LangManager;
use Lyvaris\Crates\Session\SessionFactory;
use Lyvaris\Crates\tags\Names;

final class KeyMenu extends SimpleForm
{
    public function __construct(Player $player)
    {
        parent::__construct(function (Player $player, $data) {
            $this->handleResponse($player, $data);
        });

        $session = SessionFactory::getInstance()
            ->getSession($player);

        $content = LangManager::getInstance()->generateMsg(
            "keys-overview",
            [
                "{mageKeys}",
                "{iceKeys}",
                "{enderKeys}",
                "{magmaKeys}",
                "{pegasusKeys}",
            ],
            [
                $session->getKey(Names::Mage),
                $session->getKey(Names::Ice),
                $session->getKey(Names::Ender),
                $session->getKey(Names::Magma),
                $session->getKey(Names::Pegasus),
            ]
        );

        $this->setTitle(
            LangManager::getInstance()->generateMsg("form-title-keys", [], [])
        );
        $this->setContent($content);
        $this->addButton(
            LangManager::getInstance()->generateMsg("form-button-close", [], [])
        );

        $player->sendForm($this);
    }

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null) {
            return;
        }
    }
}
