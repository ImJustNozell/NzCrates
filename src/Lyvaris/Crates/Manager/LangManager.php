<?php

namespace Lyvaris\Crates\Manager;

use Lyvaris\Crates\Main;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class LangManager
{
    use SingletonTrait;

    private array $messages = [];

    public function loadLangs(): void
    {
        $path = $this->resolvePath();

        if (!$this->fileExists($path)) {
            $this->createLangFile($path);
        }

        $this->messages = $this->parseConfig($path);
    }

    private function resolvePath(): string
    {
        $langPaths = [
            "es" => "spanish.json",
            "en" => "english.json",
            "tr" => "turkish.json",
            "zh" => "chinese.json",
            "ja" => "japanese.json",
            "fr" => "french.json",
        ];

        $langID = Main::getInstance()
            ->getConfig()
            ->get("language", "en");

        return Main::getInstance()->getDataFolder() .
            "lang/" .
            ($langPaths[$langID] ?? "english.json");
    }

    private function fileExists(string $path): bool
    {
        return file_exists($path);
    }

    private function parseConfig(string $path): array
    {
        $jsonContent = file_get_contents($path);
        return json_decode($jsonContent, true) ?? [];
    }

    private function createLangFile(string $path): void
    {
        file_put_contents($path, json_encode([], JSON_PRETTY_PRINT));
        Main::getInstance()
            ->getLogger()
            ->info("Created new empty translation file at: " . $path);
    }

    public function generateMsg(
        string $identifier,
        array $tags = [],
        array $subs = []
    ): ?string {
        if (count($tags) !== count($subs)) {
            return null;
        }

        $msgFormat = $this->messages[$identifier] ??
            "Translation key '{$identifier}' not found.";

        return str_replace($tags, $subs, $msgFormat);
    }
}
