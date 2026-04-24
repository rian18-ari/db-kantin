<?php

namespace Database\Seeders;

use App\Models\RfidCard;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat user demo dengan saldo awal
        $user = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@kantin.test',
            'password' => Hash::make('password'),
            'balance' => 50000.00,
        ]);

        // Daftarkan kartu RFID untuk user
        RfidCard::create([
            'user_id' => $user->id,
            'rfid_uid' => 'A1B2C3D4',
            'status' => 'active',
        ]);

        // User kedua dengan saldo kosong
        $user2 = User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@kantin.test',
            'password' => Hash::make('password'),
            'balance' => 0.00,
        ]);

        RfidCard::create([
            'user_id' => $user2->id,
            'rfid_uid' => 'E5F6A7B8',
            'status' => 'active',
        ]);

        $this->command->info('✅ Demo users dan RFID cards berhasil dibuat.');
        $this->command->table(
            ['Nama', 'Email', 'RFID UID', 'Saldo'],
            [
                [$user->name, $user->email, 'A1B2C3D4', 'Rp 50.000'],
                [$user2->name, $user2->email, 'E5F6A7B8', 'Rp 0'],
            ]
        );
    }
}
