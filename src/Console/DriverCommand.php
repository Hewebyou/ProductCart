<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Heesapp\Productcart\Console;

use Illuminate\Console\Command;

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
            return;
        }
        if ($this->option('drive') == 'session') {
            $this->updataValue(\Heesapp\Productcart\Controllers\SessionController::class);
            $this->info("Driver Changed to Session Driver");
            return;
        }
        $this->updataValue(\Heesapp\Productcart\Controllers\DatabaseController::class);
        $this->info("Driver default Database Driver");
    }

    /**
     * 
     * @param type $value
     */
    private function updataValue($value) {
        $path = config_path('productcart.php');
        $oldvalue = config('productcart.driver');
        $this->info($oldvalue);
        $this->info($value);
        $this->info($path);
        if ($oldvalue === $value) {
            return;
        }
        if (file_exists($path)) {
            //replace of the current value with old value
            $file = file_get_contents($path);
            file_put_contents($path, str_replace('driver'.'='.$oldvalue, 'driver'.'='. $value, $file));
            $this->info($file);
        }
    }

}
