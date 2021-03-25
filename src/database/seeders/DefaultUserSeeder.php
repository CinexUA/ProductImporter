<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Creating default super admin');
        $email = 'admin@app.com';

        $user = User::updateOrCreate(['email' => $email], [
            'name' => 'Admin',
            'email' => $email,
            'email_verified_at' => now(),
            'password' => '12345678', //don't need to crypt password, because working mutator
            'remember_token' => Str::random(10),
        ]);
        $this->command->info('Default user has been created');
    }
}
