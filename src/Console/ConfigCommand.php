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

    protected $signature = 'ProductCart:config';
    protected $description = 'work a Product cart';
//paths
    protected $package_path = __DIR__ . '/../../';

    public function handle() {
        $this->exportConfig();
        $this->info('File Created complete.');
    }

    /**
     * Install the config file.
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

        $this->comment('productcart.php create successfully.');
    }

    public function packagePath($path) {

        return $this->package_path . $path;
    }

}
