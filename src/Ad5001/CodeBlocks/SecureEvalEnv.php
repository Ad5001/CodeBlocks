<?php

namespace Ad5001\CodeBlocks;


use pocketmine\utils\Config;

use pocketmine\Server;


class SecureEvalEnv {

    public $error = null;

    public function __construct(string $code, int $id, array $vars) {
        $code = $this->securityCheck($code);
        file_put_contents(Server::getInstance()->getPluginPath() . "CodeBlocks/tmp/" . $id, $code);
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
        $code = preg_replace("/ini_set\((.+?)\);/igm", "", $code);
        $code = preg_replace("/ini_get\((.+?)\);/igm", "", $code);
        if ($cfg->get("ExecProtection") or $cfg->get("ExecProtection") == "true") {
            $code = preg_replace("/exec\((.+?)\);/igm", "", $code);
            $code = preg_replace("/passthru\((.+?)\);/igm", "", $code);
            $code = preg_replace("/shell_exec\((.+?)\);/igm", "", $code);
            $code = preg_replace("/system\((.+?)\);/igm", "", $code);
            $code = preg_replace("/proc_open\((.+?)\);/igm", "", $code);
            $code = preg_replace("/popen\((.+?)\);/igm", "", $code);
        }
        if ($cfg->get("LoopProtection") or $cfg->get("LoopProtection") == "true") {
            $code = preg_replace("/for\((.+?){0,1}\)[ ]{0,}{(.+?){0,1}}/igm", "", $code);
            $code = preg_replace("/while\((.+?){0,1}\)[ ]{0,}{(.+?){0,1}}/igm", "", $code);
            $code = preg_replace("/do[0]{0,}{(.+?){0,}}[ |\n]{0,}while\((.+?){0,1}\)[ ]{0,1}/igm", "", $code);
            $code = preg_replace("/foreach\((.+?){0,1}\)[ ]{0,}{(.+?){0,1}}/igm", "", $code);
        }
        if ($cfg->get("InternetProtection") or $cfg->get("InternetProtection") == "true") {
            $code = preg_replace("/exec\((.+?)\);/igm", "", $code);
            $code = preg_replace("/passthru\((.+?)\);/igm", "", $code);
            $code = preg_replace("/shell_exec\((.+?)\);/igm", "", $code);
            $code = preg_replace("/system\((.+?)\);/igm", "", $code);
            $code = preg_replace("/proc_open\((.+?)\);/igm", "", $code);
            $code = preg_replace("/popen\((.+?)\);/igm", "", $code);
        }
        if($cfg->get("FileProtection") or $cfg->get("FileProtection") == "true") {
            $code = preg_replace("/basename\((.+?)\);/igm", "", $code);
            $code = preg_replace("/chgrp\((.+?)\);/igm", "", $code);
            $code = preg_replace("/chmod\((.+?)\);/igm", "", $code);
            $code = preg_replace("/chown\((.+?)\);/igm", "", $code);
            $code = preg_replace("/clearstatcache\((.+?)\);/igm", "", $code);
            $code = preg_replace("/copy\((.+?)\);/igm", "", $code);
            $code = preg_replace("/delete\((.+?)\);/igm", "", $code);
            $code = preg_replace("/dirname\((.+?)\);/igm", "", $code);
            $code = preg_replace("/disk_free_space\((.+?)\);/igm", "", $code);
            $code = preg_replace("/disk_total_space\((.+?)\);/igm", "", $code);
            $code = preg_replace("/diskfreespace\((.+?)\);/igm", "", $code);
            $code = preg_replace("/fclose\((.+?)\);/igm", "", $code);
            $code = preg_replace("/feof\((.+?)\);/igm", "", $code);
            $code = preg_replace("/fflush\((.+?)\);/igm", "", $code);
            $code = preg_replace("/fgetc\((.+?)\);/igm", "", $code);
            $code = preg_replace("/fgetcsv\((.+?)\);/igm", "", $code);
            $code = preg_replace("/fgets\((.+?)\);/igm", "", $code);
            $code = preg_replace("/fgetss\((.+?)\);/igm", "", $code);
            $code = preg_replace("/file\((.+?)\);/igm", "", $code);
            $code = preg_replace("/file_exists\((.+?)\);/igm", "", $code);
            $code = preg_replace("/file_get_contents\((.+?)\);/igm", "", $code);
            $code = preg_replace("/file_put_contents\((.+?)\);/igm", "", $code);
            $code = preg_replace("/fileatime\((.+?)\);/igm", "", $code);
            $code = preg_replace("/filegroup\((.+?)\);/igm", "", $code);
            $code = preg_replace("/fileinode\((.+?)\);/igm", "", $code);
            $code = preg_replace("/filemtime\((.+?)\);/igm", "", $code);
            $code = preg_replace("/fileowner\((.+?)\);/igm", "", $code);
            $code = preg_replace("/fileperms\((.+?)\);/igm", "", $code);
            $code = preg_replace("/ftell\((.+?)\);/igm", "", $code);
            $code = preg_replace("/filesize\((.+?)\);/igm", "", $code);
            $code = preg_replace("/filetype\((.+?)\);/igm", "", $code);
            $code = preg_replace("/flock\((.+?)\);/igm", "", $code);
            $code = preg_replace("/fnmatch\((.+?)\);/igm", "", $code);
            $code = preg_replace("/fputscsv\((.+?)\);/igm", "", $code);
            $code = preg_replace("/fputs\((.+?)\);/igm", "", $code);
            $code = preg_replace("/fread\((.+?)\);/igm", "", $code);
            $code = preg_replace("/fscanf\((.+?)\);/igm", "", $code);
            $code = preg_replace("/fseek\((.+?)\);/igm", "", $code);
            $code = preg_replace("/fstat\((.+?)\);/igm", "", $code);
            $code = preg_replace("/ftruncate\((.+?)\);/igm", "", $code);
            $code = preg_replace("/fwrite\((.+?)\);/igm", "", $code);
            $code = preg_replace("/glob\((.+?)\);/igm", "", $code);
            $code = preg_replace("/is_dir\((.+?)\);/igm", "", $code);
            $code = preg_replace("/is_executable\((.+?)\);/igm", "", $code);
            $code = preg_replace("/is_file\((.+?)\);/igm", "", $code);
            $code = preg_replace("/is_link\((.+?)\);/igm", "", $code);
            $code = preg_replace("/is_readable\((.+?)\);/igm", "", $code);
            $code = preg_replace("/is_uploaded_file\((.+?)\);/igm", "", $code);
            $code = preg_replace("/is_writable\((.+?)\);/igm", "", $code);
            $code = preg_replace("/is_writeable\((.+?)\);/igm", "", $code);
            $code = preg_replace("/lchgrp\((.+?)\);/igm", "", $code);
            $code = preg_replace("/lchown\((.+?)\);/igm", "", $code);
            $code = preg_replace("/link\((.+?)\);/igm", "", $code);
            $code = preg_replace("/linkinfo\((.+?)\);/igm", "", $code);
            $code = preg_replace("/lstat\((.+?)\);/igm", "", $code);
            $code = preg_replace("/mkdir\((.+?)\);/igm", "", $code);
            $code = preg_replace("/move_uploaded_file\((.+?)\);/igm", "", $code);
            $code = preg_replace("/parse_ini_file\((.+?)\);/igm", "", $code);
            $code = preg_replace("/parse_ini_string\((.+?)\);/igm", "", $code);
            $code = preg_replace("/pathinfo\((.+?)\);/igm", "", $code);
            $code = preg_replace("/pclose\((.+?)\);/igm", "", $code);
            $code = preg_replace("/popen\((.+?)\);/igm", "", $code);
            $code = preg_replace("/readfile\((.+?)\);/igm", "", $code);
            $code = preg_replace("/readlink\((.+?)\);/igm", "", $code);
            $code = preg_replace("/realpath\((.+?)\);/igm", "", $code);
            $code = preg_replace("/realpath_cache_get\((.+?)\);/igm", "", $code);
            $code = preg_replace("/realpath_cache_size\((.+?)\);/igm", "", $code);
            $code = preg_replace("/rewind\((.+?)\);/igm", "", $code);
            $code = preg_replace("/rmdir\((.+?)\);/igm", "", $code);
            $code = preg_replace("/set_file_buffer\((.+?)\);/igm", "", $code);
            $code = preg_replace("/stat\((.+?)\);/igm", "", $code);
            $code = preg_replace("/symlink\((.+?)\);/igm", "", $code);
            $code = preg_replace("/tempnam\((.+?)\);/igm", "", $code);
            $code = preg_replace("/tmpfile\((.+?)\);/igm", "", $code);
            $code = preg_replace("/touch\((.+?)\);/igm", "", $code);
            $code = preg_replace("/umask\((.+?)\);/igm", "", $code);
            $code = preg_replace("/unlink\((.+?)\);/igm", "", $code);
            $code = preg_replace("/show_source\((.+?)\);/igm", "", $code);
            $code = preg_replace("/chdir\((.+?)\);/igm", "", $code);
            $code = preg_replace("/chroot\((.+?)\);/igm", "", $code);
            $code = preg_replace("/closedir\((.+?)\);/igm", "", $code);
            $code = preg_replace("/dir\((.+?)\);/igm", "", $code);
            $code = preg_replace("/dirname\((.+?)\);/igm", "", $code);
            $code = preg_replace("/getcwd\((.+?)\);/igm", "", $code);
            $code = preg_replace("/opendir\((.+?)\);/igm", "", $code);
            $code = preg_replace("/readdir\((.+?)\);/igm", "", $code);
            $code = preg_replace("/rewinddir\((.+?)\);/igm", "", $code);
            $code = preg_replace("/scandir\((.+?)\);/igm", "", $code);
        }
        return $code;
    }
}