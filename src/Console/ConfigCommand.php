<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Heesapp\Productcart\Console;

use Illuminate\Console\Command;

/**
 * Description of Commands
 *
 * @author hassa
 */
class ConfigCommand extends Command {

    /**
     *
     * @var type 
     */
    protected $signature = 'ProductCart:Config';

    /**
     *
     * @var type 
     */
    protected $description = 'Create a Config File';

    /**
     *
     * @var type 
     */
    protected $package_path = __DIR__ . '/../../';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        $this->exportConfig();
        $this->info('File Created complete.');
    }

    /**
     * create configuration file
     * @return type
     */
    protected function exportConfig() {
        if (file_exists(config_path('productcart.php'))) {
            if (!$this->confirm('The Product Cart configuration file already exists. Do you want to replace it?')) {
                return;
            }
        }
        copy(
                $this->packagePath('config/ProductCartConfig.php'),
                config_path('productcart.php')
        );
    }

    /**
     * get path
     * @param type $path
     * @return type
     */
    public function packagePath($path) {

        return $this->package_path . $path;
    }

}
