<?php

namespace BestCodrEver\CustomVoidLevel;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\{ClosureTask, TaskScheduler};
use pocketmine\event\{player\PlayerMoveEvent, Listener};
use pocketmine\utils\{TextFormat as TF, Config};
use pocketmine\{Player, Server};

class Main extends PluginBase implements Listener
{
  public $config;
  
  public function onEnable(): void
  {
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->saveResource("config.yml");
    $this->config = $plugin->getConfig();
  }
  
  public function onMove(PlayerMoveEvent $event): void
  {
    $player = $event->getPlayer();
    $playerY = $player->getY();
    if ($config->get("void-y-level") < -40 || $config->get("void-y-level") > 0) return;
    if ($playerY !== $config->get("void-y-level")) return;
    if ($config->get("payload.command-enabled") === true){
      if ($config->get("payload.kill-enabled") === true) $player->kill();
      $commands = $config->get("payload.commands", []);
      $this->getScheduler()->scheduleDelayedTask(new ClosureTask(
        function(int $currentTick){
          foreach ($commands as $command){
            if (is_null($command)) return;
            
          }
        }
      ), $config->get("payload.command-delay", 0));
    }
  }
}
