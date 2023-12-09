<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserPreference;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'make:admin {email} {password}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new admin user';

    /**
     * Execute the console command.
     */
  public function handle()
{
    $email = $this->argument('email');
    $password = $this->argument('password');

    User::factory(1)
            ->sequence(
                ['email' => $email, 'password' => bcrypt($password)]
            )
            ->admins()
            ->create()
            ->each(function ($user, $index) {
                $user->userPreference()->save(UserPreference::factory()->make());
            });

    $this->info('Admin user created successfully!');

}

}
