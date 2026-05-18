<?php

namespace Database\Seeders;

use App\Models\CaseNotification;
use App\Models\Hearing;
use App\Models\LegalCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = collect([
            ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Full system access'],
            ['name' => 'Court Administrator', 'slug' => 'court-admin', 'description' => 'Court operations and case allocation'],
            ['name' => 'Judge', 'slug' => 'judge', 'description' => 'Assigned case and hearing authority'],
            ['name' => 'Advocate/Lawyer', 'slug' => 'advocate', 'description' => 'Legal representative access'],
            ['name' => 'Client/User', 'slug' => 'client', 'description' => 'Case party access'],
        ])->map(fn ($role) => Role::updateOrCreate(['slug' => $role['slug']], $role));

        $admin = User::updateOrCreate(['email' => 'admin@enyaya.local'], [
            'name' => 'Super Admin',
            'role_id' => $roles->firstWhere('slug', 'super-admin')->id,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        $courtAdmin = User::updateOrCreate(['email' => 'court@enyaya.local'], [
            'name' => 'Court Administrator',
            'role_id' => $roles->firstWhere('slug', 'court-admin')->id,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        $judge = User::updateOrCreate(['email' => 'judge@enyaya.local'], [
            'name' => 'Justice A. Sharma',
            'role_id' => $roles->firstWhere('slug', 'judge')->id,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        $advocate = User::updateOrCreate(['email' => 'advocate@enyaya.local'], [
            'name' => 'Advocate Meera Rao',
            'role_id' => $roles->firstWhere('slug', 'advocate')->id,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        $client = User::updateOrCreate(['email' => 'client@enyaya.local'], [
            'name' => 'Ravi Kumar',
            'role_id' => $roles->firstWhere('slug', 'client')->id,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        $case = LegalCase::updateOrCreate(['case_number' => 'ENY-2026-00001'], [
            'title' => 'Ravi Kumar vs State Electricity Board',
            'category' => 'Civil',
            'petitioner_name' => 'Ravi Kumar',
            'petitioner_contact' => '9876543210',
            'respondent_name' => 'State Electricity Board',
            'filing_date' => now()->subDays(20),
            'next_hearing_date' => now()->addDays(8)->setTime(11, 0),
            'status' => 'hearing_scheduled',
            'priority' => 'high',
            'client_id' => $client->id,
            'advocate_id' => $advocate->id,
            'judge_id' => $judge->id,
            'summary' => 'Dispute related to billing and service restoration.',
        ]);

        Hearing::updateOrCreate(['legal_case_id' => $case->id, 'scheduled_at' => $case->next_hearing_date], [
            'courtroom' => 'Courtroom 2',
            'status' => 'scheduled',
            'purpose' => 'Initial case management hearing',
            'created_by' => $courtAdmin->id,
        ]);

        CaseNotification::updateOrCreate(['user_id' => $admin->id, 'title' => 'System initialized'], [
            'legal_case_id' => $case->id,
            'message' => 'eNyaya demo data has been seeded.',
            'type' => 'admin',
        ]);
    }
}
