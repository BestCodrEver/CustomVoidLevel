<?php

namespace BestCodrEver\CustomVoidLevel;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\{ClosureTask, TaskScheduler};
use pocketmine\event\{player\PlayerMoveEvent, Listener};
use pocketmine\utils\{TextFormat as TF, Config};
use pocketmine\{Player, Server};

class Main extends PluginBase implements Listener
{
  //Config File
  public $config;
  
  public function onEnable(): void
  {
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    //Save Config
    $this->saveResource("config.yml");
    $this->config = $plugin->getConfig();
  }
  
  public function onMove(PlayerMoveEvent $event): void
  {
    $player = $event->getPlayer();
    //Get the player's Y-Level to check if it's in the void level
    $playerY = $player->getY();
    //Return if player is not at void level
    if ($playerY !== $config->get("void-y-level")) return;
    //Check if command payload is enabled in the config
    if ($config->get("payload.command-enabled") === true){
      //If player kill is set to true, kill the player
      if ($config->get("payload.kill-enabled") === true) $player->kill();
      //Get commands set in config
      $commands = $config->get("payload.commands");
      //Run commands
      foreach ($commands as $command){
        if (is_null($command)) return;
        
      }
    }
  }
}
