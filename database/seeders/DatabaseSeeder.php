<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Report;
use App\Models\Assignment;
use App\Models\Upvote;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users
        $admin = User::create([
            'name' => 'Admin BPBD Palembang',
            'email' => 'admin@sigap.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $pimpinan = User::create([
            'name' => 'Kepala BPBD Palembang',
            'email' => 'pimpinan@sigap.go.id',
            'password' => Hash::make('password123'),
            'role' => 'pimpinan',
        ]);

        $petugas1 = User::create([
            'name' => 'Ahmad (Petugas Lapangan 1)',
            'email' => 'petugas1@sigap.go.id',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
        ]);

        $petugas2 = User::create([
            'name' => 'Budi (Petugas Lapangan 2)',
            'email' => 'petugas2@sigap.go.id',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
        ]);

        $masyarakat1 = User::create([
            'name' => 'Rian Hidayat',
            'email' => 'masyarakat1@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'masyarakat',
        ]);

        $masyarakat2 = User::create([
            'name' => 'Dewi Lestari',
            'email' => 'masyarakat2@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'masyarakat',
        ]);

        // 2. Create Reports
        // New formula: (0.40 * WaterLevel) + (0.25 * UpvoteCount) + (0.20 * DuplicateCount) + (0.15 * WeatherScore)
        // Using dummy weather score of 70 (Hujan Sedang) for seeded data

        $report1 = Report::create([
            'user_id' => $masyarakat1->id,
            'type' => 'banjir',
            'title' => 'Banjir Genangan Tinggi di Jalan Mayor Ruslan',
            'description' => 'Genangan air menutupi jalan raya setinggi 40 cm setelah hujan deras selama 3 jam. Lalu lintas macet total.',
            'latitude' => -2.977212,
            'longitude' => 104.764512,
            'photo' => null,
            'water_level' => 40,
            'status' => 'masuk',
            'duplicate_count' => 0,
            'upvote_count' => 0,
            'priority_score' => 27, // (0.40*40)+(0.25*0)+(0.20*0)+(0.15*70) = 16+0+0+10.5 = 26.5 ≈ 27
            'created_at' => Carbon::now()->subHours(2),
        ]);

        $report2 = Report::create([
            'user_id' => $masyarakat2->id,
            'type' => 'banjir',
            'title' => 'Banjir Parah di Pemukiman Kawasan Kertapati',
            'description' => 'Air meluap dari anak sungai Musi memasuki rumah warga setinggi 80 cm. Warga membutuhkan bantuan evakuasi.',
            'latitude' => -3.011234,
            'longitude' => 104.744567,
            'photo' => null,
            'water_level' => 80,
            'status' => 'diverifikasi',
            'duplicate_count' => 1,
            'upvote_count' => 2,
            'priority_score' => 43, // (0.40*80)+(0.25*2)+(0.20*1)+(0.15*70) = 32+0.5+0.2+10.5 = 43.2 ≈ 43
            'created_at' => Carbon::now()->subHours(5),
        ]);

        $report3 = Report::create([
            'user_id' => $masyarakat1->id,
            'type' => 'infrastruktur',
            'title' => 'Dinding Penahan Air Jebol di Samping Sungai Sekanak',
            'description' => 'Beton penahan air sungai roboh sepanjang 5 meter akibat tergerus arus kencang. Berpotensi meluap ke pemukiman.',
            'latitude' => -2.991201,
            'longitude' => 104.755432,
            'photo' => null,
            'water_level' => 0,
            'status' => 'ditangani',
            'duplicate_count' => 0,
            'upvote_count' => 3,
            'priority_score' => 11, // (0.40*0)+(0.25*3)+(0.20*0)+(0.15*70) = 0+0.75+0+10.5 = 11.25 ≈ 11
            'created_at' => Carbon::now()->subHours(12),
        ]);

        $report4 = Report::create([
            'user_id' => $masyarakat2->id,
            'type' => 'banjir',
            'title' => 'Banjir Jalan Protokol Sudirman (Depan IP Mall)',
            'description' => 'Genangan banjir akibat drainase tersumbat setinggi 50 cm. Kendaraan roda dua mogok.',
            'latitude' => -2.988012,
            'longitude' => 104.756045,
            'photo' => null,
            'water_level' => 50,
            'status' => 'selesai',
            'duplicate_count' => 0,
            'upvote_count' => 1,
            'priority_score' => 31, // (0.40*50)+(0.25*1)+(0.20*0)+(0.15*70) = 20+0.25+0+10.5 = 30.75 ≈ 31
            'created_at' => Carbon::now()->subDays(2),
        ]);

        $report5 = Report::create([
            'user_id' => $masyarakat1->id,
            'type' => 'infrastruktur',
            'title' => 'Saluran Drainase Amblas di Sako Kenten',
            'description' => 'Saluran pembuangan air utama amblas sehingga aliran air tersumbat total dan meluap ke halaman rumah warga.',
            'latitude' => -2.934522,
            'longitude' => 104.782012,
            'photo' => null,
            'water_level' => 0,
            'status' => 'ditugaskan',
            'duplicate_count' => 0,
            'upvote_count' => 0,
            'priority_score' => 11, // (0.40*0)+(0.25*0)+(0.20*0)+(0.15*70) = 10.5 ≈ 11
            'created_at' => Carbon::now()->subHours(18),
        ]);

        // 3. Seed Upvotes
        Upvote::create(['report_id' => $report2->id, 'user_id' => $masyarakat1->id]);
        Upvote::create(['report_id' => $report2->id, 'user_id' => $masyarakat2->id]);

        Upvote::create(['report_id' => $report3->id, 'user_id' => $masyarakat1->id]);
        Upvote::create(['report_id' => $report3->id, 'user_id' => $masyarakat2->id]);
        Upvote::create(['report_id' => $report3->id, 'user_id' => $admin->id]);

        Upvote::create(['report_id' => $report4->id, 'user_id' => $masyarakat1->id]);

        // 4. Seed Assignments
        // Report 3 (Sekanak) is 'ditangani' by Petugas 1 (Ahmad)
        Assignment::create([
            'report_id' => $report3->id,
            'officer_id' => $petugas1->id,
            'assigned_at' => Carbon::now()->subHours(10),
        ]);

        // Report 4 (Sudirman) is 'selesai', was assigned to Petugas 2 (Budi)
        Assignment::create([
            'report_id' => $report4->id,
            'officer_id' => $petugas2->id,
            'assigned_at' => Carbon::now()->subDays(2),
        ]);

        // Report 5 (Sako) is 'ditugaskan' to Petugas 1 (Ahmad)
        Assignment::create([
            'report_id' => $report5->id,
            'officer_id' => $petugas1->id,
            'assigned_at' => Carbon::now()->subHours(16),
        ]);
    }
}
