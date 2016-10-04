<?php


namespace Ad5001\CodeBlocks;


use pocketmine\command\CommandSender;


use pocketmine\command\Command;


use pocketmine\event\Listener;


use pocketmine\plugin\PluginBase;


use pocketmine\utils\Config;


use pocketmine\Server;


use pocketmine\Player;






class Main extends PluginBase implements Listener {
	
	
	private $editors = [];
	
	
	
	
	public function onEnable(){
		
		@mkdir($this->getDataFolder() . "tmp");
		
		
		$this->reloadConfig();
		
		
		$this->editors = [];
		
		
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		
		
		$this->attemps = [];
		
		
	}
	
	
	
	public function onInteract(\pocketmine\event\player\PlayerInteractEvent $event) {
		if(!is_dir($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks")) {
			@mkdir($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks");
		}
		if(!is_dir($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks/Ad5001")) {
			@mkdir($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks/Ad5001");
		}
		if(!file_exists($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks/Ad5001/CodeBlocks.json")) {
			file_put_contents($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks/Ad5001/CodeBlocks.json", "{}");
		}
		$cfg = new Config($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks/Ad5001/CodeBlocks.json");
		if(isset($this->editors[$event->getPlayer()->getName()])) {
			$cfg->set($event->getBlock()->x . "@" . $event->getBlock()->y ."@" .$event->getBlock()->z, $this->editors[$event->getPlayer()->getName()]);
			unset($this->editors[$event->getPlayer()->getName()]);
			$event->getPlayer()->sendMessage("§4§l§o[§r§l§7CodeBlocks§o§4]§f§r Succefully set code " . $this->editors[$event->getPlayer()->getName()] . " to this block (" . $event->getBlock()->getName() . ").");
			$cfg->save();
			unset($this->editors[$event->getPlayer()->getName()]);
			return true;
		}
		elseif (!is_bool($cfg->get($event->getBlock()->x . "@" . $event->getBlock()->y ."@" .$event->getBlock()->z))) {
			if($this->getConfig()->get("activate_on_click") == "true" or $this->getConfig()->get("activate_on_click")) {
				$id = time() + $event->getBlock()->x * $event->getBlock()->z + count($cfg->getAll()) + count(scandir($this->getDataFolder() . "tmp"));
				$vars = ["player" => $event->getPlayer(), "sender" => $event->getPlayer(), "block" => $event->getBlock(), "method" => SecureEvalEnv::INTERACT];
				$env = new SecureEvalEnv($cfg->get($event->getBlock()->x . "@" . $event->getBlock()->y ."@" .$event->getBlock()->z), $id, $vars);
				if($env->error !== "") {
					$event->getPlayer()->sendMessage("There were an error while excuting the code :" . $env->error);
				}
				else {
					$env->execute();
				}
			}
			return true;
		}
	}
	
	
	
	public function onBlockBreak(\pocketmine\event\block\BlockBreakEvent $event) {
		if(!is_dir($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks")) {
			@mkdir($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks");
		}
		if(!is_dir($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks/Ad5001")) {
			@mkdir($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks/Ad5001");
		}
		if(!file_exists($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks/Ad5001/CodeBlocks.json")) {
			file_put_contents($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks/Ad5001/CodeBlocks.json", "{}");
		}
		$cfg = new Config($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks/Ad5001/CodeBlocks.json");
		if (!is_bool($cfg->get($event->getBlock()->x . "@" . $event->getBlock()->y ."@" .$event->getBlock()->z))) {
			if($this->getConfig()->get("activate_on_break") == "true" or $this->getConfig()->get("activate_on_break")) {
				$id = time() + $event->getBlock()->x * $event->getBlock()->z + count($cfg->getAll()) + count(scandir($this->getDataFolder() . "tmp"));
				$vars = ["player" => $event->getPlayer(), "sender" => $event->getPlayer(), "block" => $event->getBlock(), "method" => SecureEvalEnv::BREAK];
				$env = new SecureEvalEnv($cfg->get($event->getBlock()->x . "@" . $event->getBlock()->y ."@" .$event->getBlock()->z), $id, $vars);
				if($env->error !== "") {
					$event->getPlayer()->sendMessage("There were an error while excuting the code :" . $env->error);
				}
				else {
					$env->execute();
				}
			}
			$event->setCancelled();
			return true;
		}
	}
	
	
	
	public function onPlayerMove(\pocketmine\event\player\PlayerMoveEvent $event) {
		if(!is_dir($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks")) {
			@mkdir($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks");
		}
		if(!is_dir($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks/Ad5001")) {
			@mkdir($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks/Ad5001");
		}
		if(!file_exists($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks/Ad5001/CodeBlocks.json")) {
			file_put_contents($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks/Ad5001/CodeBlocks.json", "{}");
		}
		$cfg = new Config($event->getPlayer()->getLevel()->getFolderName() . "plugins_blocks/Ad5001/CodeBlocks.json");
		if (!is_bool($cfg->get(round($event->getPlayer()->x) . "@" . round($event->getPlayer()->y) - 1 ."@" . round($event->getPlayer()->z)))) {
			if($this->getConfig()->get("activate_on_walk") == "true" or $this->getConfig()->get("activate_on_walk")) {
				$id = time() + round($event->getPlayer()->x) * round($event->getPlayer()->z) + count($cfg->getAll()) + count(scandir($this->getDataFolder() . "tmp"));
				$block = $event->getPlayer()->getLevel()->getBlock(new \pocketmine\math\Vector3(round($event->getPlayer()->x), round($event->getPlayer()->y) - 1, round($event->getPlayer()->z)));
				$vars = ["player" => $event->getPlayer(), "sender" => $event->getPlayer(), "block" => $block, "method" => SecureEvalEnv::BREAK];
				$env = new SecureEvalEnv($cfg->get($block->x . "@" . $block->y ."@" .$block->z), $id, $vars);
				
				if($env->error !== "") {
					$event->getPlayer()->sendMessage("There were an error while excuting the code :" . $env->error);
				}
				else {
					$env->execute();
				}
			}
			return true;
		}
	}
	
	
	
	
	public function onLoad(){
		
		$this->saveDefaultConfig();
		
	}
	
	
	
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
		
		switch($cmd->getName()){
			
			case 'changecodeblock':
			
			switch(in_array($this->getConfig()->get("Type"), ["password", "perm", "usernames"]) ? $this->getConfig()->get("Type") : "perm") {
				
				case "password":
																if(isset($args[1])) {
					
					if(!isset($this->attemps[$sender->getName()])) {
						
						$this->attemps[$sender->getName()] = (int) $this->getConfig()->get("PasswordAttemps");
						
					}
					
					$password = $args[0];
					$cfgpass = $this->getConfig()->get("password");
					
					unset($args[0]);
					
					$code = implode(" ", $args);
					
					if($this->attemps[$sender->getName()] <= 0) {
						$sender->sendMessage("§cYou have used all your password attemps.");
						return true;
					}
					
					if($password == $cfgpass) {
						
						$this->editors[$sender->getName()] = $code;
						$sender->sendMessage("§aTouch a block to set it's code");
						
					}
					else {
						$this->attemps[$sender->getName()]--;
						$sender->sendMessage("§cWrong password");
					}
					
				}
				break;
				case "perm":
																if(isset($args[0])) {
					
					$code = implode(" ", $args);
					
					if($sender->hasPermission("codeblocks.modify")) {
						
						$this->editors[$sender->getName()] = $code;
						$sender->sendMessage("§aTouch a block to set it's code");
						
					}
					else {
						$this->attemps[$sender->getName()]--;
						$sender->sendMessage("§cYou don't have the permission to use this command.");
					}
					
				}
				break;
				case "usernames":
																if(isset($args[0])) {
					
					$code = implode(" ", $args);
					
					if(in_array($sender->getName(), $this->getConfig()->get("usernames"))) {
						
						$this->editors[$sender->getName()] = $code;
						$sender->sendMessage("§aTouch a block to set it's code");
						
					}
					else {
						$this->attemps[$sender->getName()]--;
						$sender->sendMessage("§cYou are not allowed to use this command.");
					}
					
				}
				break;
				
			}
			
			
			break;
			
		}
		
		return false;
		
		
	}
	
	
}
