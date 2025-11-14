<?php

namespace NewPlayerMC\commands;

use NewPlayerMC\ChatLock;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TF;

class ChatLockCommand extends Command
{

    private ChatLock $chatLock;

    public function __construct()
    {
        parent::__construct("chatlock");

        $this->chatLock = ChatLock::getInstance();
        $chatLock = $this->chatLock;

        $this->setUsage("chatlock <on|off>");
        $this->setDescription($chatLock->getConfig()->get("command-description"));
        $this->setPermission("chatlock.use");
        $this->setPermissionMessage($chatLock->getConfig()->get("permission-message"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        $chatLock = $this->chatLock;
        if ($sender instanceof Player and !$this->testPermission($sender)) {
            $sender->sendMessage($chatLock->getConfig()->get("permission-message"));
            return;
        }
        
        if (!isset($args[0])) {
            match ($chatLock->isChatLocked()) {
                true => $chatLock->unlockChat($sender),
                false => $chatLock->lockChat($sender)
            };
        } else {
            switch ($args[0]) {
                case "on":
                    $chatLock->lockChat($sender);
                    break;
                case "off":
                    $chatLock->unlockChat($sender);
                    break;
                default:
                    $sender->sendMessage(TF::RED . "Usage: /" . $this->getUsage());
                    break;
            }
        }
    }

}