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
 * Description of WitchListCommand
 *
 * @author Hassan Elasied
 */
class WitchListCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ProductCart:WitchListTable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a migration for the Product Witch List tables';

    /**
     * The file system instance.
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

        $this->CreateWithcListTable();

        $this->info('Migration  WitchList table created successfully!');

        $this->composer->dumpAutoloads();

    }

    /**
     * put witch list table to migration in file witch list created 
     * 
     * @return void 
     */
    protected function CreateWithcListTable() {

        $fullpath = $this->MigrationWithListTable();

        $witchlist = $this->files->get(__DIR__ . '/stub/witchlist.stub');

        $this->files->put($fullpath, $witchlist);
    }



    /**
     * create migration witch list items table in migrations file
     * 
     * @return string
     */
    protected function MigrationWithListTable() {

        $name = 'create_witchlists_table';

        $path = $this->laravel->databasePath() . '/migrations';

        return $this->laravel['migration.creator']->create($name, $path);
    }



}
