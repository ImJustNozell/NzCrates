<?php

declare(strict_types=1);

use Nozell\Crates\tags\Names;

final class Profile
{
    private array $keys = [
        Names::Mage => 0,
        Names::Ice => 0,
        Names::Ender => 0,
        Names::Magma => 0,
        Names::Pegasus => 0
    ];

    public function getKey(string $crateName): int
    {
        return $this->keys[$crateName] ?? 0;
    }

    public function setKey(string $crateName, int $amount): void
    {
        $this->keys[$crateName] = $amount;
    }

    public function addKey(string $crateName, int $amount): void
    {
        $this->keys[$crateName] += $amount;
    }

    public function reduceKey(string $crateName): void
    {
        if ($this->keys[$crateName] > 0) {
            $this->keys[$crateName]--;
        }
    }
}
