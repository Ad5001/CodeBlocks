<?php

namespace Ad5001\CodeBlocks;


use pocketmine\utils\Config;

use pocketmine\Server;


class SecureEvalEnv {

    const INTERACT = 0;

    const CLICK = 0;

    const REDSTONE = 1;

    const WALK = 2;

    const BREAK = 3;

    public $error = null;

    public function __construct(string $code, int $id, array $vars) {
        $code = $this->securityCheck($code);
        file_put_contents(Server::getInstance()->getPluginPath() . "CodeBlocks/tmp/" . $id, $code);
        $resource = fread(popen(Server::getInstance()->getFilePath() . "bin/php/php -l " . Server::getInstance()->getPluginPath() . "CodeBlocks/tmp/" . $id, "w"), 8192);
        if(preg_match("/No syntax errors detected in/im", $resource) <= 0) {
            $this->error = $resources;
            return $this->error;
        }
        $this->code = $code;
        $this->vars = $vars;
    }


    public function execute() {
        foreach($this->vars as $varname => $value) {
            ${$varname} = $value;
        }
        return eval($this->code);
    }


    public function securityCheck(string $code) {
        $cfg = new Config(Server::getInstance()->getFilePath() . "plugins/CodeBlocks/config.yml");
        $code = preg_replace("/ini_set\((.+?)\);/im", "", $code);
        $code = preg_replace("/ini_get\((.+?)\);/im", "", $code);
        if ($cfg->get("ExecProtection") or $cfg->get("ExecProtection") == "true") {
            $code = preg_replace("/exec\((.+?)\);/im", "", $code);
            $code = preg_replace("/passthru\((.+?)\);/im", "", $code);
            $code = preg_replace("/shell_exec\((.+?)\);/im", "", $code);
            $code = preg_replace("/system\((.+?)\);/im", "", $code);
            $code = preg_replace("/proc_open\((.+?)\);/im", "", $code);
            $code = preg_replace("/popen\((.+?)\);/im", "", $code);
        }
        if ($cfg->get("LoopProtection") or $cfg->get("LoopProtection") == "true") {
            $code = preg_replace("/for\((.+?){0,1}\)[ ]{0,}{(.+?){0,1}}/im", "", $code);
            $code = preg_replace("/while\((.+?){0,1}\)[ ]{0,}{(.+?){0,1}}/im", "", $code);
            $code = preg_replace("/do[0]{0,}{(.+?){0,}}[ |\n]{0,}while\((.+?){0,1}\)[ ]{0,1}/im", "", $code);
            $code = preg_replace("/foreach\((.+?){0,1}\)[ ]{0,}{(.+?){0,1}}/im", "", $code);
        }
        if ($cfg->get("InternetProtection") or $cfg->get("InternetProtection") == "true") {
            $code = preg_replace("/curl_(.+?)*\((.+?)\);/im", "", $code);
            $code = preg_replace("/ftp_(.+?)*\((.+?)\);/im", "", $code);
            $code = preg_replace("/mysqli_(.+?)*\((.+?)\);/im", "", $code);
            $code = preg_replace("/mysql_(.+?)*\((.+?)\);/im", "", $code);
            $code = preg_replace("/new mysqli\((.+?){0,}\);/im", "", $code);
            $code = preg_replace("/\\pocketmine\\utils\\Utils::(.+?)*url\((.+?)\);/im", "", $code);
            $code = preg_replace("/Utils::(.+?)*url\((.+?)\);/im", "", $code);
            $code = preg_replace("/mail\((.+?)\);/im", "", $code);
        }
        if ($cfg->get("ExitProtection") or $cfg->get("ExitProtection") == "true") {
            $code = preg_replace("/exit\((.+?){0,}\);/im", "", $code);
            $code = preg_replace("/or die\((.+?){0,}\);/im", "", $code);
            $code = preg_replace("/die(.+?)*\((.+?){0,}\);/im", "", $code);
        }
        if ($cfg->get("DispatchCommandProtection") or $cfg->get("DispatchCommandProtection") == "true") {
            $code = preg_replace("/->dispatchcommand\((.+?){0,}\);/im", "", $code);
        }
        if ($cfg->get("ServerProtection") or $cfg->get("ServerProtection") == "true") {
            $code = preg_replace("/Server::getInstance\(\);/im", "", $code);
            $code = preg_replace("/->getServer\(\);/im", "", $code);
            $code = preg_replace("/->server\(\);/im", "", $code);
        }
        if($cfg->get("FileProtection") or $cfg->get("FileProtection") == "true") {
            $code = preg_replace("/basename\((.+?)\);/im", "", $code);
            $code = preg_replace("/chgrp\((.+?)\);/im", "", $code);
            $code = preg_replace("/chmod\((.+?)\);/im", "", $code);
            $code = preg_replace("/chown\((.+?)\);/im", "", $code);
            $code = preg_replace("/clearstatcache\((.+?)\);/im", "", $code);
            $code = preg_replace("/copy\((.+?)\);/im", "", $code);
            $code = preg_replace("/delete\((.+?)\);/im", "", $code);
            $code = preg_replace("/dirname\((.+?)\);/im", "", $code);
            $code = preg_replace("/disk_free_space\((.+?)\);/im", "", $code);
            $code = preg_replace("/disk_total_space\((.+?)\);/im", "", $code);
            $code = preg_replace("/diskfreespace\((.+?)\);/im", "", $code);
            $code = preg_replace("/fclose\((.+?)\);/im", "", $code);
            $code = preg_replace("/feof\((.+?)\);/im", "", $code);
            $code = preg_replace("/fflush\((.+?)\);/im", "", $code);
            $code = preg_replace("/fgetc\((.+?)\);/im", "", $code);
            $code = preg_replace("/fgetcsv\((.+?)\);/im", "", $code);
            $code = preg_replace("/fgets\((.+?)\);/im", "", $code);
            $code = preg_replace("/fgetss\((.+?)\);/im", "", $code);
            $code = preg_replace("/file\((.+?)\);/im", "", $code);
            $code = preg_replace("/file_exists\((.+?)\);/im", "", $code);
            $code = preg_replace("/file_get_contents\((.+?)\);/im", "", $code);
            $code = preg_replace("/file_put_contents\((.+?)\);/im", "", $code);
            $code = preg_replace("/fileatime\((.+?)\);/im", "", $code);
            $code = preg_replace("/filegroup\((.+?)\);/im", "", $code);
            $code = preg_replace("/fileinode\((.+?)\);/im", "", $code);
            $code = preg_replace("/filemtime\((.+?)\);/im", "", $code);
            $code = preg_replace("/fileowner\((.+?)\);/im", "", $code);
            $code = preg_replace("/fileperms\((.+?)\);/im", "", $code);
            $code = preg_replace("/ftell\((.+?)\);/im", "", $code);
            $code = preg_replace("/filesize\((.+?)\);/im", "", $code);
            $code = preg_replace("/filetype\((.+?)\);/im", "", $code);
            $code = preg_replace("/flock\((.+?)\);/im", "", $code);
            $code = preg_replace("/fnmatch\((.+?)\);/im", "", $code);
            $code = preg_replace("/fputscsv\((.+?)\);/im", "", $code);
            $code = preg_replace("/fputs\((.+?)\);/im", "", $code);
            $code = preg_replace("/fread\((.+?)\);/im", "", $code);
            $code = preg_replace("/fscanf\((.+?)\);/im", "", $code);
            $code = preg_replace("/fseek\((.+?)\);/im", "", $code);
            $code = preg_replace("/fstat\((.+?)\);/im", "", $code);
            $code = preg_replace("/ftruncate\((.+?)\);/im", "", $code);
            $code = preg_replace("/fwrite\((.+?)\);/im", "", $code);
            $code = preg_replace("/glob\((.+?)\);/im", "", $code);
            $code = preg_replace("/is_dir\((.+?)\);/im", "", $code);
            $code = preg_replace("/is_executable\((.+?)\);/im", "", $code);
            $code = preg_replace("/is_file\((.+?)\);/im", "", $code);
            $code = preg_replace("/is_link\((.+?)\);/im", "", $code);
            $code = preg_replace("/is_readable\((.+?)\);/im", "", $code);
            $code = preg_replace("/is_uploaded_file\((.+?)\);/im", "", $code);
            $code = preg_replace("/is_writable\((.+?)\);/im", "", $code);
            $code = preg_replace("/is_writeable\((.+?)\);/im", "", $code);
            $code = preg_replace("/lchgrp\((.+?)\);/im", "", $code);
            $code = preg_replace("/lchown\((.+?)\);/im", "", $code);
            $code = preg_replace("/link\((.+?)\);/im", "", $code);
            $code = preg_replace("/linkinfo\((.+?)\);/im", "", $code);
            $code = preg_replace("/lstat\((.+?)\);/im", "", $code);
            $code = preg_replace("/mkdir\((.+?)\);/im", "", $code);
            $code = preg_replace("/move_uploaded_file\((.+?)\);/im", "", $code);
            $code = preg_replace("/parse_ini_file\((.+?)\);/im", "", $code);
            $code = preg_replace("/parse_ini_string\((.+?)\);/im", "", $code);
            $code = preg_replace("/pathinfo\((.+?)\);/im", "", $code);
            $code = preg_replace("/pclose\((.+?)\);/im", "", $code);
            $code = preg_replace("/popen\((.+?)\);/im", "", $code);
            $code = preg_replace("/readfile\((.+?)\);/im", "", $code);
            $code = preg_replace("/readlink\((.+?)\);/im", "", $code);
            $code = preg_replace("/realpath\((.+?)\);/im", "", $code);
            $code = preg_replace("/realpath_cache_get\((.+?)\);/im", "", $code);
            $code = preg_replace("/realpath_cache_size\((.+?)\);/im", "", $code);
            $code = preg_replace("/rewind\((.+?)\);/im", "", $code);
            $code = preg_replace("/rmdir\((.+?)\);/im", "", $code);
            $code = preg_replace("/set_file_buffer\((.+?)\);/im", "", $code);
            $code = preg_replace("/stat\((.+?)\);/im", "", $code);
            $code = preg_replace("/symlink\((.+?)\);/im", "", $code);
            $code = preg_replace("/tempnam\((.+?)\);/im", "", $code);
            $code = preg_replace("/tmpfile\((.+?)\);/im", "", $code);
            $code = preg_replace("/touch\((.+?)\);/im", "", $code);
            $code = preg_replace("/umask\((.+?)\);/im", "", $code);
            $code = preg_replace("/unlink\((.+?)\);/im", "", $code);
            $code = preg_replace("/show_source\((.+?)\);/im", "", $code);
            $code = preg_replace("/chdir\((.+?)\);/im", "", $code);
            $code = preg_replace("/chroot\((.+?)\);/im", "", $code);
            $code = preg_replace("/closedir\((.+?)\);/im", "", $code);
            $code = preg_replace("/dir\((.+?)\);/im", "", $code);
            $code = preg_replace("/dirname\((.+?)\);/im", "", $code);
            $code = preg_replace("/getcwd\((.+?)\);/im", "", $code);
            $code = preg_replace("/opendir\((.+?)\);/im", "", $code);
            $code = preg_replace("/readdir\((.+?)\);/im", "", $code);
            $code = preg_replace("/rewinddir\((.+?)\);/im", "", $code);
            $code = preg_replace("/scandir\((.+?)\);/im", "", $code);
            $code = preg_replace("/zip_open\((.+?)\);/im", "", $code);
            $code = preg_replace("/new ZipArchive\((.+?)\);/im", "", $code);
        }
        return $code;
    }
}