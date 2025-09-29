<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $campaignId = DB::table('campaigns')->insertGetId([
            'name' => 'Test Campaign',
            'offer_id' => 1001,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sourceId = DB::table('traffic_sources')->insertGetId([
            'name' => 'Google Ads',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('campaign_stats')->insert([
            'campaign_id' => $campaignId,
            'source_id' => $sourceId,
            'date' => Carbon::today(),
            'impressions' => 1000,
            'clicks' => 123,
            'conversions' => 10,
            'cost' => 50.25,
            'revenue' => 120.75,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
