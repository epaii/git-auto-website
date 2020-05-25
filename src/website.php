<?php

namespace epii\git\auto\website;

use epii\server\Tools;
use Exception;

class website{
    public static function bindToGit($gitDir,$webDir){
        try{
            if(!is_dir($gitDir)){
                mkdir($webDir,0777,true);
                self::runCmd("git -C ".$webDir." init --bare");
            }
            self::runCmd("git  clone ".$gitDir." ".$webDir);
            file_put_contents($hook = $gitDir.DIRECTORY_SEPARATOR."hooks".DIRECTORY_SEPARATOR."post-update",
            '#!/bin/sh
            DEPLOY_PATH='.$webDir.'
            unset GIT_DIR
            cd $DEPLOY_PATH
            git fetch --all
            git reset --hard origin/master
            git pull
            exec git update-server-info'
            );
            chmod($hook,0777);
            return true;
        }catch(Exception $e){
            return false;
        }
       
    }
    public static function runCmd($cmd,$echo = false){
        exec($cmd,$output,$return);
        if($return===0){
            $out =  implode(PHP_EOL,$output);
            if($echo)
            echo $out.PHP_EOL;
            return $out;
        }else{
           return false;
        }
    }
}