<?php

namespace Tests\Feature;

use App\Models\Hearing;
use App\Models\LegalCase;
use App\Models\User;
use App\Mail\ClientHearingSummons;
use App\Mail\AdvocateHearingNotification;
use App\Mail\JudgeHearingNotification;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HearingAndDocumentTest extends TestCase
{
    use RefreshDatabase;

    public function test_hearing_scheduling_sends_specialized_emails(): void
    {
        Mail::fake();
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'court@enyaya.local')->firstOrFail();
        $case = LegalCase::first();

        $client = User::whereHas('role', fn ($q) => $q->where('slug', 'client'))->first();
        $advocate = User::whereHas('role', fn ($q) => $q->where('slug', 'advocate'))->first();
        $judge = User::whereHas('role', fn ($q) => $q->where('slug', 'judge'))->first();

        $case->update([
            'client_id' => $client->id,
            'advocate_id' => $advocate->id,
            'judge_id' => $judge->id,
        ]);

        $response = $this->actingAs($admin)->post('/hearings', [
            'legal_case_id' => $case->id,
            'scheduled_at' => now()->addDays(5)->format('Y-m-d\TH:i'),
            'courtroom' => 'Courtroom 99',
            'hearing_sequence' => 10,
            'purpose' => 'Test Agenda Purpose',
            'notes' => 'Some special instructions',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        Mail::assertQueued(ClientHearingSummons::class, function ($mail) use ($client) {
            return $mail->hasTo($client->email);
        });

        Mail::assertQueued(AdvocateHearingNotification::class, function ($mail) use ($advocate) {
            return $mail->hasTo($advocate->email);
        });

        Mail::assertQueued(JudgeHearingNotification::class, function ($mail) use ($judge) {
            return $mail->hasTo($judge->email);
        });
    }

    public function test_cannot_download_documents_outside_case_folder(): void
    {
        Storage::fake('local');
        $this->seed(DatabaseSeeder::class);

        $client = User::where('email', 'client@enyaya.local')->firstOrFail();
        $case = LegalCase::first();

        Storage::disk('local')->put('secret/config.txt', 'sensitive info');

        $response = $this->actingAs($client)->get(route('cases.documents.download', [
            $case,
            'path' => 'secret/config.txt'
        ]));

        $response->assertStatus(403);
    }
}
