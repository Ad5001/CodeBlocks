<?php

namespace Ad5001\CodeBlocks;




use pocketmine\utils\Config;



use pocketmine\Server;



use pocketmine\utils\MainLogger;




class SecureEvalEnv {
	
	
	
	const INTERACT = 0;
	
	
	
	const CLICK = 0;
	
	
	
	const REDSTONE = 1;
	
	
	
	const WALK = 2;
	
	
	
	const BREAK = 3;
	
	
	
	public $error;
	
	
	
	public function __construct(string $code, int $id, array $vars) {
		
		
		$this->error = "";
		
		
		if($code !== "" && $code !== null) {
			
			
			$code = $this->securityCheck($code);
			
			
			/*$resource = exec(sprintf('echo %s | ' .  Server::getInstance()->getFilePath() . "bin\\php\\php -l ", escapeshellarg($code)), $output, $exit);
            
            var_dump($output);

            $resource = $output[1];

			if(strpos("No syntax errors detected in", $resource) <= 0) {
				
				
				$this->error = str_ireplace(Server::getInstance()->getPluginPath() . "CodeBlocks/tmp/" . $id, "eval", $resource) ;
				
            }*/

            if(!$this->php_syntax_check($code)) {

                $this->error= "There were a syntax error in the code !";

            }
			
			
			$this->code = $code;
			
			
			$this->vars = $vars;
			
			
		}
		
		
	}
	
	
	
	
	public function execute() {
		
		
		if($this->error == null or $this->error == "") {
			
			
			foreach($this->vars as $varname => $value) {
				
				
				${
					
					$varname
				}
				
				= $value;
				
				
			}
			
			
			return eval($this->code);
			
		}
		
		
	}
	
	
	
	
	public function securityCheck(string $code) {
		
		
		$cfg = new Config(Server::getInstance()->getFilePath() . "plugins/CodeBlocks/config.yml");
		
		
		$code = preg_replace("/ini_set\((.+?)\);/im", "null", $code);
		
		
		$code = preg_replace("/ini_get\((.+?)\);/im", "null", $code);
		
		
		if ($cfg->get("ExecProtection") or $cfg->get("ExecProtection") == "true") {
			
			
			$code = preg_replace("/exec\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/passthru\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/shell_exec\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/system\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/proc_open\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/popen\((.+?)\);/im", "null", $code);
			
			
		}
		
		
		if ($cfg->get("LoopProtection") or $cfg->get("LoopProtection") == "true") {
			
			
			$code = preg_replace("/for\((.+?){0,1}\)[ ]{0,}{(.+?){0,1}}/im", "null", $code);
			
			
			$code = preg_replace("/while\((.+?){0,1}\)[ ]{0,}{(.+?){0,1}}/im", "null", $code);
			
			
			$code = preg_replace("/do[0]{0,}{(.+?){0,}}[ |\n]{0,}while\((.+?){0,1}\)[ ]{0,1}/im", "null", $code);
			
			
			$code = preg_replace("/foreach\((.+?){0,1}\)[ ]{0,}{(.+?){0,1}}/im", "null", $code);
			
			
		}
		
		
		if ($cfg->get("InternetProtection") or $cfg->get("InternetProtection") == "true") {
			
			
			$code = preg_replace("/curl_(.+?)*\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/ftp_(.+?)*\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/mysqli_(.+?)*\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/mysql_(.+?)*\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/new mysqli\((.+?){0,}\);/im", "null", $code);
			
			
			$code = preg_replace("/\\\\pocketmine\\\\utils\\\\Utils::(.+?)*url\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/Utils::(.+?)*url\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/mail\((.+?)\);/im", "null", $code);
			
			
		}
		
		
		if ($cfg->get("ExitProtection") or $cfg->get("ExitProtection") == "true") {
			
			
			$code = preg_replace("/exit\((.+?){0,}\);/im", "null", $code);
			
			
			$code = preg_replace("/or die\((.+?){0,}\);/im", "null", $code);
			
			
			$code = preg_replace("/die(.+?)*\((.+?){0,}\);/im", "null", $code);
			
			
		}
		
		
		if ($cfg->get("DispatchCommandProtection") or $cfg->get("DispatchCommandProtection") == "true") {
			
			
			$code = preg_replace("/->dispatchcommand\((.+?){0,}\);/im", "null", $code);
			
			
		}
		
		
		if ($cfg->get("ServerProtection") or $cfg->get("ServerProtection") == "true") {
			
			
			$code = preg_replace("/Server::getInstance\(\);/im", "null", $code);
			
			
			$code = preg_replace("/->getServer\(\);/im", "null", $code);
			
			
			$code = preg_replace("/->server\(\);/im", "null", $code);
			
			
		}
		
		
		if($cfg->get("FileProtection") or $cfg->get("FileProtection") == "true") {
			
			
			$code = preg_replace("/basename\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/chgrp\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/chmod\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/chown\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/clearstatcache\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/copy\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/delete\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/dirname\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/disk_free_space\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/disk_total_space\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/diskfreespace\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/fclose\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/feof\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/fflush\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/fgetc\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/fgetcsv\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/fgets\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/fgetss\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/file\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/file_exists\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/file_get_contents\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/file_put_contents\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/fileatime\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/filegroup\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/fileinode\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/filemtime\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/fileowner\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/fileperms\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/ftell\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/filesize\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/filetype\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/flock\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/fnmatch\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/fputscsv\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/fputs\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/fread\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/fscanf\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/fseek\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/fstat\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/ftruncate\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/fwrite\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/glob\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/is_dir\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/is_executable\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/is_file\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/is_link\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/is_readable\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/is_uploaded_file\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/is_writable\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/is_writeable\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/lchgrp\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/lchown\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/link\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/linkinfo\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/lstat\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/mkdir\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/move_uploaded_file\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/parse_ini_file\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/parse_ini_string\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/pathinfo\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/pclose\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/popen\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/readfile\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/readlink\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/realpath\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/realpath_cache_get\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/realpath_cache_size\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/rewind\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/rmdir\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/set_file_buffer\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/stat\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/symlink\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/tempnam\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/tmpfile\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/touch\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/umask\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/unlink\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/show_source\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/chdir\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/chroot\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/closedir\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/dir\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/dirname\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/getcwd\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/opendir\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/readdir\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/rewinddir\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/scandir\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/zip_open\((.+?)\);/im", "null", $code);
			
			
			$code = preg_replace("/new ZipArchive\((.+?)\);/im", "null", $code);
			
			
		}
		
		
		return $code;
		
		
	}



/* The next code is from PHP.NET */


public function php_check_syntax( $php, $isFile=false )
{
    # Get the string tokens
    $tokens = token_get_all( '<?php '.trim( $php  ));
   
    # Drop our manually entered opening tag
    array_shift( $tokens );
    $this->token_fix( $tokens );

    # Check to see how we need to proceed
    # prepare the string for parsing
    if( isset( $tokens[0][0] ) && $tokens[0][0] === T_OPEN_TAG )
       $evalStr = $php;
    else
        $evalStr = "<?php\n{$php}?>";

    if( $isFile OR ( $tf = tempnam( NULL, 'parse-' ) AND file_put_contents( $tf, $php ) !== FALSE ) AND $tf = $php )
    {
        # Prevent output
        ob_start();
        system( 'C:\inetpub\PHP\5.2.6\php -c "'.dirname(__FILE__).'/php.ini" -l < '.$php, $ret );
        $output = ob_get_clean();

        if( $ret !== 0 )
        {
            # Parse error to report?
            if( (bool)preg_match( '/Parse error:\s*syntax error,(.+?)\s+in\s+.+?\s*line\s+(\d+)/', $output, $match ) )
            {
                return array(
                    'line'    =>    (int)$match[2],
                    'msg'    =>    $match[1]
                );
            }
        }
        return true;
    }
    return false;
}

//fixes related bugs: 29761, 34782 => token_get_all returns <?php NOT as T_OPEN_TAG
function token_fix( &$tokens ) {
    if (!is_array($tokens) || (count($tokens)<2)) {
        return;
    }
   //return of no fixing needed
    if (is_array($tokens[0]) && (($tokens[0][0]==T_OPEN_TAG) || ($tokens[0][0]==T_OPEN_TAG_WITH_ECHO)) ) {
        return;
    }
    //continue
    $p1 = (is_array($tokens[0])?$tokens[0][1]:$tokens[0]);
    $p2 = (is_array($tokens[1])?$tokens[1][1]:$tokens[1]);
    $p3 = '';

    if (($p1.$p2 == '<?') || ($p1.$p2 == '<%')) {
        $type = ($p2=='?')?T_OPEN_TAG:T_OPEN_TAG_WITH_ECHO;
        $del = 2;
        //update token type for 3rd part?
        if (count($tokens)>2) {
            $p3 = is_array($tokens[2])?$tokens[2][1]:$tokens[2];
            $del = (($p3=='php') || ($p3=='='))?3:2;
            $type = ($p3=='=')?T_OPEN_TAG_WITH_ECHO:$type;
        }
        //rebuild erroneous token
        $temp = array($type, $p1.$p2.$p3);
        if (version_compare(phpversion(), '5.2.2', '<' )===false)
            $temp[] = isset($tokens[0][2])?$tokens[0][2]:'unknown';

        //rebuild
        $tokens[1] = '';
        if ($del==3) $tokens[2]='';
        $tokens[0] = $temp;
    }
    return;
}
}

