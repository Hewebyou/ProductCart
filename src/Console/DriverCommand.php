<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Heesapp\Productcart\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Illuminate\Foundation\Console\ConfigCacheCommand;
use Illuminate\Foundation\Console\ConfigClearCommand;

/**
 * Description of DriverCommand
 *
 * @author hassa
 */
class DriverCommand extends Command {

    /**
     *
     * @var type 
     */
    protected $signature = 'ProductCart:Driver ' .
            '{--drive= :Product Cart assign value of the driver ,Available type: none, session, database}';

    /**
     *
     * @var type 
     */
    protected $description = 'Change Product cart Driver the defalut is a Database Driver';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        if ($this->option('drive') == 'database') {
            $this->updataValue(\Heesapp\Productcart\Controllers\DatabaseController::class);
            $this->info("Driver Changed to Database Driver");
            $this->call('config:clear');
            $this->call('config:cache');
            return;
        }
        if ($this->option('drive') == 'session') {
            $this->updataValue(\Heesapp\Productcart\Controllers\SessionController::class);
            $this->info("Driver Changed to Session Driver");
            $this->call('config:clear');
            $this->call('config:cache');
            return;
        }
        $this->updataValue(\Heesapp\Productcart\Controllers\DatabaseController::class);
        $this->call('config:clear');
        $this->call('config:cache');
        $this->info("Driver default Database Driver");
    }

    /**
     * 
     * @param type $value
     */
    private function updataValue($value) {
        $path = config_path('productcart.php');
        $oldvalue = config('productcart.driver');
        if ($oldvalue === $value) {
            if (!$this->confirm('The Product Cart Driver  already exists. Do you want to replace it?')) {
                return;
            }
        }
        if (file_exists($path)) {
            //replace of the current value with old value
            $file = file_get_contents($path);
            $newfile = str_replace($oldvalue, $value, $file);
            file_put_contents($path, $newfile);
        }
    }

}
