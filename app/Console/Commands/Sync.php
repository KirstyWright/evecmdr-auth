<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs your users & groups';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(\App\Eve\Groups $groups, \App\Eve\Discord $discord, \App\Eve\UserService $userService)
    {
        parent::__construct();
        $this->groups = $groups;
        $this->discord = $discord;
        $this->userService = $userService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = \App\User::all();
        foreach ($users as $user) {
            $this->userService->update($user);
            $this->groups->runRules($user);
        }
        $this->discord->syncGroups();
    }
}
