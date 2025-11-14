<?php

namespace NewPlayerMC\listener;

use NewPlayerMC\ChatLock;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Server;

class ChatListener implements \pocketmine\event\Listener
{
    public function onChat(PlayerChatEvent $event): void {
        $player = $event->getPlayer();
        if ((!Server::getInstance()->isOp($player->getName()) || !$player->hasPermission("chatlock.bypass")) and ChatLock::getInstance()->isChatLocked()) {
            $player->sendMessage(ChatLock::getInstance()->getConfig()->get("chat-is-locked"));
            $event->cancel();
        }
    }

}