<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Heesapp\Productcart\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;

/**
 * Description of CartTableCommands
 *
 * @author hassa
 */
class CartTableCommands extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ProductCart:CartTables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a migration for the Product Cart tables';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * Create a new notifications table command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  \Illuminate\Support\Composer  $composer
     * @return void
     */
    public function __construct(Filesystem $files, Composer $composer) {
        parent::__construct();

        $this->files = $files;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {

        $this->createCartsTable();

        $this->info('Migration  Cart table created successfully!');

        $this->createItemsTable();

        $this->info('Migration  Cart Items table created successfully!');

        $this->composer->dumpAutoloads();
    }

    /**
     * create cart table
     */
    protected function createCartsTable() {

        $fullPath = $this->createCartBaseMigration();

        $cartsmigration = $this->files->get(__DIR__ . '/stub/carts.stub');

        $this->files->put($fullPath, $cartsmigration);
    }

    protected function createItemsTable() {

        $fullpath = $this->createItemsCartBaseMigration();

        $cartitemsmigration = $this->files->get(__DIR__ . '/stub/items_cart.stub');

        $this->files->put($fullpath, $cartitemsmigration);
    }

    /**
     * Create a base migration file for the notifications.
     *
     * @return string
     */
    protected function createCartBaseMigration() {
        $name = 'create_carts_table';

        $path = $this->laravel->databasePath() . '/migrations';

        return $this->laravel['migration.creator']->create($name, $path);
    }

    /**
     * Create a base migration file for the notifications.
     *
     * @return string
     */
    protected function createItemsCartBaseMigration() {
        $name = 'create_items_cart_table';

        $path = $this->laravel->databasePath() . '/migrations';

        return $this->laravel['migration.creator']->create($name, $path);
    }

}
