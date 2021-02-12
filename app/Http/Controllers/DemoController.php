<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DemoController extends Controller
{

    public function makeMigrationFile()
    {
        Artisan::call('make:migration my_table');
    }

    public function runMigration()
    {
        Artisan::call('migrate');
    }

    public function appCacheClear()
    {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
    }

    public function setEnvValue($envKey, $envValue)
    {
        $envFilePath = app()->environmentFilePath();
        $stringEnv = file_get_contents($envFilePath);

        $stringEnv .= "\n";

        $keyStartPosition = strpos($stringEnv, "{$envKey}=");
        $keyEndPosition = strpos($stringEnv, "\n", $keyStartPosition);

        $oldLine =  substr($stringEnv, $keyStartPosition, $keyEndPosition - $keyStartPosition);



        if (!$keyStartPosition || !$keyEndPosition || !$oldLine) {
            $stringEnv .= "{$envKey}={$envValue}\n";
        } else {
            $stringEnv = str_replace($oldLine, "{$envKey}={$envValue}", $stringEnv);
        }


        $stringEnv = substr($stringEnv, 0, -1);
        $changeResults = file_put_contents($envFilePath, $stringEnv);

        if (!$changeResults) {
            return false;
        } else {
            return true;
        }
    }


    public function envConfig()
    {
        $this->setEnvValue('DB_USERNAME', 'myUsername');
        $this->setEnvValue('DB_PASSWORD', 'my_password');
    }

    public function serverConfigCheck()
    {
        $phpVersion = phpversion();
        $bcMath = extension_loaded('bcmath');
        $ctype = extension_loaded('ctype');
        $fileInfo = extension_loaded("FileInfo");
        $json = extension_loaded('json');
        $mbString = extension_loaded('mbstring');
        $openSSL = extension_loaded('openssl');
        $tokenizer = extension_loaded('tokenizer');
        $xml = extension_loaded('xml');
        $pdo = defined('PDO::ATTR_DRIVER_NAME');

        if($phpVersion>=7.2 && $bcMath==true && $ctype==true && $fileInfo==true && $mbString==true && $openSSL==true && $tokenizer==true && $xml==true && $pdo==true){
            return "Laravel 7x Supported";
        }else{
            return "Laravel 7x Not Supported";
        }

    }
}
