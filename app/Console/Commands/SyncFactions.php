<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncFactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:syncFactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync factions table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(\App\Esi\Manager $esi)
    {
        parent::__construct();
        $this->esi = $esi;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->esi->syncFactions();
    }
}
