<?php

namespace usy4\XboxProfile;

use usy4\XboxProfile\commands\XboxProfileCommand;

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
        $url = "https://playerdb.co/api/player/xbox/" . $gt_nospace; // powerd by steam from 2016 to 2022
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
  
        $obj = json_decode($result, false);

        if($obj->code !== "player.found"){
            $player->sendMessage("§cError, There is no xbox gamertag called: " . $gt);
            return;
        }
        
        $xProfile = (
            "§dUserName: §f" . $obj->data->player->username . "\n" .
            "§dGamerScore: §f" . $obj->data->player->meta->gamerscore . "\n" .
            "§dAccountTier: §f" . $obj->data->player->meta->accountTier . "\n" .    
            "§dXboxOneRep: §f" . $obj->data->player->meta->xboxOneRep . "\n" .
            "§dRealName: §f" . $obj->data->player->meta->realName . "\n" .
            "§dBio: §f" . $obj->data->player->meta->bio . "\n"
        );
        
        $form = new SimpleForm(function (Player $player, $data){
            if($data === null) {
                return true;
            }
        });
        $form->setTitle("§aXbox Profile");
        $form->setContent($xProfile);
        $form->addButton("§2:O");
        $player->sendForm($form);
    }
}
