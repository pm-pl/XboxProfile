<?php 

namespace usy4\XboxProfile\commands;

/*  
 *  A plugin for PocketMine-MP.
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 	
 */

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

use usy4\XboxProfile\commands\TargetPlayerArgument;
use usy4\XboxProfile\Main;

class XboxProfileCommand extends BaseCommand 
{
    
    protected function prepare(): void {
        $this->registerArgument(0, new TargetPlayerArgument(true));
        $this->setPermission("xboxprofile.command");
    }
	
    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
        if(!$sender instanceof Player){
            $sender->sendMessage("use this command-ingame");
            return;
        }
        if(count($args) < 1) {
            $sender->sendMessage("Usage: /xboxprofile <player: target>");
            return;    
        }
        Main::xboxProfileForm($sender, $args["player"]);
	}
	
}
