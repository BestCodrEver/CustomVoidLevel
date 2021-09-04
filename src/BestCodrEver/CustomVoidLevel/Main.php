<?php

namespace BestCodrEver\CustomVoidLevel;

use pocketmine\plugin\PluginBase;
use pocketmine\command\{ConsoleCommandSender, CommandSender};
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
    $this->config = $this->getConfig();
  }
  
  public function onMove(PlayerMoveEvent $event): void
  {
    $player = $event->getPlayer();
    $playerY = $player->getY();
    if ($this->config->get("void-y-level") < -40 || $this->config->get("void-y-level") > 0) return;
    if ($playerY !== $this->config->get("void-y-level")) return;
    if ($this->config->get("payload.command-enabled") === true){
      if ($this->config->get("payload.kill-enabled") === true) $player->kill();
      $commands = $this->config->get("payload.commands", []);
      $this->getScheduler()->scheduleDelayedTask(new ClosureTask(
        function(int $currentTick){
          foreach ($commands as $command){
            if (is_null($command)) return;
            $formattedcommand = str_replace("{player}", "{$player->getName()}", $command);
            $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "$formattedcommand");
          }
        }
      ), $this->config->get("payload.command-delay", 0));
    }
  }
}
