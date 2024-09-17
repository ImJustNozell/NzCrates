
<?php

declare(strict_types=1);

namespace Nozell\Crates\Data;

use Nozell\Crates\Meetings\Meeting;

final class CratesData {

    public function __construct(
        private readonly Meeting $meeting,
        private int $keyMage = 0,
        private int $keyIce = 0,
        private int $keyEnder = 0,
        private int $keyMagma = 0,
        private int $keyPegasus = 0
    ) {}

    public function getKeyMage(): int {
        return $this->keyMage;
    }

    public function setKeyMage(int $amount): void {
        $this->keyMage = $amount;
    }

    public function addKeyMage(int $amount): void {
        $this->keyMage += $amount;
    }

    public function reduceKeyMage(): void {
        $this->keyMage--;
    }

    public function getKeyIce(): int {
        return $this->keyIce;
    }

    public function setKeyIce(int $amount): void {
        $this->keyIce = $amount;
    }

    public function addKeyIce(int $amount): void {
        $this->keyIce += $amount;
    }

    public function reduceKeyIce(): void {
        $this->keyIce--;
    }

    public function getKeyEnder(): int {
        return $this->keyEnder;
    }

    public function setKeyEnder(int $amount): void {
        $this->keyEnder = $amount;
    }

    public function addKeyEnder(int $amount): void {
        $this->keyEnder += $amount;
    }

    public function reduceKeyEnder(): void {
        $this->keyEnder--;
    }

    public function getKeyMagma(): int {
        return $this->keyMagma;
    }

    public function setKeyMagma(int $amount): void {
        $this->keyMagma = $amount;
    }

    public function addKeyMagma(int $amount): void {
        $this->keyMagma += $amount;
    }

    public function reduceKeyMagma(): void {
        $this->keyMagma--;
    }

    public function getKeyPegasus(): int {
        return $this->keyPegasus;
    }

    public function setKeyPegasus(int $amount): void {
        $this->keyPegasus = $amount;
    }

    public function addKeyPegasus(int $amount): void {
        $this->keyPegasus += $amount;
    }

    public function reduceKeyPegasus(): void {
        $this->keyPegasus--;
    }
}
