<?php
use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Generate Users.
     *
     * @return void
     */
    public function run()
    {
        // Create primary user account for testing.
        User::create([
            'name' => 'User Test',
            'email' => 'user@test.dev',
            'password' => bcrypt('password')
        ]);

        // Create another five user accounts.
        User::factory(5)->create();

        $this->command->info('Users table seeded.');
    }
}
