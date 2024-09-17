<?php

namespace Nozell\Crates\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use Nozell\Crates\Menu\MainMenu;

class CratesCommand extends Command {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []){
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->setPermission("crates.menu");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if (!$sender instanceof Player){
            $sender->sendMessage("Este comando solo puede ser usado por jugadores");
            return;
        }
        
        new MainMenu($sender);
    }
    
}
