<?php

namespace Nozell\Crates\Manager;

use Nozell\Crates\Main;
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
            "es" => "spanish.yml",
            "en" => "english.yml",
            "tr" => "turkish.yml",
            "zh" => "chinese.yml",
            "ja" => "japanese.yml",
            "fr" => "french.yml",
        ];

        $langID = Main::getInstance()
            ->getConfig()
            ->get("language", "en");

        return Main::getInstance()->getDataFolder() .
            "lang/" .
            ($langPaths[$langID] ?? "english.yml");
    }

    private function fileExists(string $path): bool
    {
        return file_exists($path);
    }

    private function parseConfig(string $path): array
    {
        return (new Config($path, Config::YAML))->getAll();
    }

    private function createLangFile(string $path): void
    {
        $config = new Config($path, Config::YAML);
        $config->save();
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

        $msgFormat =
            $this->messages[$identifier] ??
            "Translation key '{$identifier}' not found.";
        return str_replace($tags, $subs, $msgFormat);
    }
}
