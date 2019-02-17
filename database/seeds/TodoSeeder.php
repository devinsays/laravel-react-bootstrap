<?php
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\User;
use App\Todo;

class TodoSeeder extends Seeder
{
    /**
     * Generate Todo data.
     *
     * @return void
     */
    public function run()
    {
        $users = User::get();
        $faker = Faker\Factory::create();

        // Create 30 days of entries for each user.
        foreach ($users as $user) {
            $todos = array();
            $timestamp = Carbon::now();
            for ($d = 0; $d <= 30; $d++) {
                $todos[] = array(
                    'created_at' => $timestamp->format('Y-m-d H:i:s'),
                    'updated_at' => $timestamp->format('Y-m-d H:i:s'),
                    'user_id' => $user->id,
                    'value' => $faker->sentence(6),
                    'status' => $faker->randomElement(['open', 'closed'])
                );
                $timestamp = $timestamp->subDay();
            }

            // Bulk insert generated todo data for each user.
            DB::table('todos')->insert($todos);
        }

        $this->command->info('Todos table seeded.');
    }
}
