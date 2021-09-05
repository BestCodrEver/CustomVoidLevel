<?php

/*
  ____            _    _____          _      ______              
 |  _ \          | |  / ____|        | |    |  ____|             
 | |_) | ___  ___| |_| |     ___   __| |_ __| |____   _____ _ __ 
 |  _ < / _ \/ __| __| |    / _ \ / _` | '__|  __\ \ / / _ \ '__|
 | |_) |  __/\__ \ |_| |___| (_) | (_| | |  | |___\ V /  __/ |   
 |____/ \___||___/\__|\_____\___/ \__,_|_|  |______\_/ \___|_|   
This plugin was made by BestCodrEver.
Discord: FaithlessMC#7013
*/

namespace BestCodrEver\CustomVoidLevel;

use pocketmine\plugin\PluginBase;
use pocketmine\command\{Command, ConsoleCommandSender, CommandSender};
use pocketmine\scheduler\{ClosureTask, TaskScheduler};
use pocketmine\event\{player\PlayerMoveEvent, Listener};
use pocketmine\utils\{TextFormat as TF, Config};
use pocketmine\{Player, Server};

class Main extends PluginBase implements Listener
{
  public $config;
  
  public function onEnable()
  {
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->saveResource("config.yml");
    $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);   
  }
  
  public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
  {
    if (!$sender instanceof Player) return true;
    if ($cmd->getName() !== "void") return true;
    if (!$sender->hasPermission("customvoidlevel.void")) return true;
    if (!isset($args[0])){
      $sender->sendMessage(TF::RED . "Usage: /void <y-level | reset>");
      return true;
    }
    $coord = (int)$args[0];
    if ($coord >= -40 && $coord < 0){
      $this->config->set("void-y-level", $coord);
      $this->config->save();
      $sender->sendMessage(TF::GREEN . "Successfully set the void level to {$args[0]}.");
    }
    if ($args[0] === "reset"){
      $this->config->set("void-y-level", -40);
      $this->config->save();
      $sender->sendMessage(TF::GREEN . "Successfully reset the void level.");      
    }
    return true; 
  }
  
  public function onMove(PlayerMoveEvent $event)
  {
    $player = $event->getPlayer();
    $playerY = $player->getY();
    $this->config->reload();
    if ($this->config->get("void-y-level") < -40 || $this->config->get("void-y-level") > 0) return;
    if ($playerY >= $this->config->get("void-y-level")) return;
    if ($this->config->get("payload")["kill-enabled"] === true) $player->kill();
    if ($this->config->get("payload")["command-enabled"] === true){
      $this->getScheduler()->scheduleDelayedTask(new ClosureTask(
        function(int $currentTick){
          foreach ($this->config->getNested("payload")["commands"] as $command){
            if (is_null($command)) return;
            $formattedcommand = str_replace("{player}", "{$player->getName()}", $command);
            $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "$formattedcommand");
          }
        }
      ), $this->config->get("payload")["command-delay"]);
    }
  }
}
