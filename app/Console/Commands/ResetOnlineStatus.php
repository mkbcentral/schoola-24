<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetOnlineStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:reset-online-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all users online status to false (offline)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Resetting all users online status...');

        $onlineUsers = User::where('is_on_line', true)->get(['id', 'name', 'email']);
        
        if ($onlineUsers->isEmpty()) {
            $this->info('No users are marked as online.');
            return Command::SUCCESS;
        }

        $this->table(
            ['ID', 'Name', 'Email'],
            $onlineUsers->map(fn($user) => [$user->id, $user->name, $user->email])
        );

        if ($this->confirm('Do you want to mark these users as offline?', true)) {
            $count = User::where('is_on_line', true)->update(['is_on_line' => false]);
            $this->info("Successfully reset {$count} user(s) to offline status.");
        } else {
            $this->info('Operation cancelled.');
        }

        return Command::SUCCESS;
    }
}
