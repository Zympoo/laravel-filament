<?php
namespace Database\Seeders;
use App\Models\User; // user model
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // wachtwoord hashen
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'warre.neirinck@gmail.com', // unieke sleutel om duplicaten te vermijden
            ],
            [
                'name' => 'Warre Admin', // naam van de admin
                'password' => Hash::make('1234567890'), // wachtwoord gehashed opslaan
                'email_verified_at' => now(), // meteen als verified markeren
            ]
        );
    }
}
