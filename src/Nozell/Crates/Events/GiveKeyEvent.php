<?php

namespace Nozell\Crates\Events;

use pocketmine\event\Event;
use pocketmine\player\Player;

class GiveKeyEvent extends Event
{
    private Player $sender;
    private Player $receiver;
    private string $keyType;
    private int $amount;

    public function __construct(Player $sender, Player $receiver, string $keyType, int $amount)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->keyType = $keyType;
        $this->amount = $amount;
    }

    public function getSender(): Player
    {
        return $this->sender;
    }

    public function getReceiver(): Player
    {
        return $this->receiver;
    }

    public function getKeyType(): string
    {
        return $this->keyType;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
