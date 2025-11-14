<?php

namespace NewPlayerMC;

use NewPlayerMC\commands\ChatLockCommand;
use NewPlayerMC\listener\ChatListener;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;

class ChatLock extends \pocketmine\plugin\PluginBase implements Listener {
    use SingletonTrait;

    public bool $chatLock = false;

    protected function onEnable(): void
    {
        self::$instance = $this;
        $this->saveResource("config.yml");
        $this->saveDefaultConfig();
        $this->getServer()->getCommandMap()->register("chatlock", new ChatLockCommand());
        $this->getServer()->getPluginManager()->registerEvents(new ChatListener(), $this);
    }

    public function lockChat(CommandSender $sender): void {
        if ($this->chatLock) $sender->sendMessage($this->getConfig()->get("chatlock-already-on"));
        else {
            $this->chatLock = true;
            $sender->sendMessage($this->getConfig()->get("chatlock-on"));
            Server::getInstance()->broadcastMessage(str_replace("{player}", $sender->getName(), $this->getConfig()->get("broadcast-message-locked")));
        }
    }

    public function unlockChat(CommandSender $sender): void {
        if (!$this->chatLock) $sender->sendMessage($this->getConfig()->get("chatlock-already-off"));
        else {
            $this->chatLock = false;
            $sender->sendMessage($this->getConfig()->get("chatlock-off"));
            Server::getInstance()->broadcastMessage(str_replace("{player}", $sender->getName(), $this->getConfig()->get("broadcast-message-unlocked")));
        }
    }

    public function isChatLocked(): bool {return $this->chatLock;}

}