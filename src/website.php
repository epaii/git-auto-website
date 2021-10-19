<?php

namespace epii\git\auto\website;
use Exception;

class website{
    private static $git = "git";
    public static function setGitCommand($command)
    {
        self::$git ="\"" .$command."\"";
    }

    public static function bindToGit($gitDir,$webDir){
        try{
            if(!is_dir($gitDir)){
                echo  self::$git."  init --bare   ".$gitDir;

                self::runCmd(self::$git."  init --bare   ".$gitDir);
            }
            self::runCmd(self::$git."  clone ".$gitDir." ".$webDir);
            file_put_contents($hook = $gitDir.DIRECTORY_SEPARATOR."hooks".DIRECTORY_SEPARATOR."post-update",
            '#!/bin/sh
            if [ -f "/epii/hooks/git.sh" ]; then
                /epii/hooks/git.sh start '.$webDir.'
            fi
            DEPLOY_PATH='.$webDir.'
            unset GIT_DIR
            cd $DEPLOY_PATH
            git fetch --all
            git reset --hard origin/master
            git pull
            if [ -f "/epii/hooks/git.sh" ]; then
                /epii/hooks/git.sh finish '.$webDir.'
            fi
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