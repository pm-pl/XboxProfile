<?php

namespace usy4\XboxProfile;

use usy4\XboxProfile\commands\XboxProfileCommand;
use usy4\XboxProfile\tasks\GetInfoTask;

use CortexPE\Commando\PacketHooker;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

use Vecnavium\FormsUI\SimpleForm;

class Main extends PluginBase{

    public function onEnable() : void{
        if (!PacketHooker::isRegistered()){    
            PacketHooker::register($this); 
        }        
        $this->getServer()->getCommandMap()->register($this->getName(), new XboxProfileCommand($this, "xboxprofile", "to view players xbox profile :O", aliases: ["xp"])); 
    }

    public static function xboxProfileForm(Player $player, string $gt) : void {
        $gt_nospace = str_replace(' ', '%20', $gt);
        Server::getInstance()->getAsyncPool()->submitTask(new GetInfoTask($gt_nospace, function (int $results, array $data, ?string $error = null) use ($player, $gt){      
            if($data["code"] !== "player.found"){
                $player->sendMessage("§cError, There is no xbox gamertag called: " . $gt);
                return;
            }
            
            $xProfile = (
                "§aUserName: §f" . $data["data"]["player"]["username"] . "\n" .
                "§aId: §f" . $data["data"]["player"]["id"] . "\n" .
                "§aGamerScore: §f" . $data["data"]["player"]["meta"]["gamerscore"] . "\n" .
                "§aAccountTier: §f" . $data["data"]["player"]["meta"]["accountTier"] . "\n" .    
                "§aXboxOneRep: §f" . $data["data"]["player"]["meta"]["xboxOneRep"] . "\n" .
                "§aRealName: §f" . $data["data"]["player"]["meta"]["realName"] . "\n" .
                "§aBio: §f" . $data["data"]["player"]["meta"]["bio"] . "\n"
            );

            $form = new SimpleForm(function (Player $player, $data){
                if($data === null) {
                    return true;
                }
            });
            $form->setTitle("§2Xbox Profile");
            $form->setContent($xProfile);
            $form->addButton("§2:O");
            $player->sendForm($form);
        }));
    }
    
}
