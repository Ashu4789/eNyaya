<?php

namespace Database\Seeders;

use App\Models\CaseNotification;
use App\Models\Hearing;
use App\Models\LegalCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = $this->seedRoles();
        $users = $this->seedUsers($roles);
        $cases = $this->seedCases($users);

        $this->seedHearings($cases, $users['courtAdmins'][0]);
        $this->seedNotifications($cases, $users);
    }

    private function seedRoles()
    {
        return collect([
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full platform access including reports, all cases, hearings, users, audit records, and system settings.',
            ],
            [
                'name' => 'Court Administrator',
                'slug' => 'court-admin',
                'description' => 'Court operations access for case filing, judge allocation, hearing scheduling, cause lists, notifications, and reports.',
            ],
            [
                'name' => 'Judge',
                'slug' => 'judge',
                'description' => 'Judicial access to assigned cases, hearing history, evidence records, cause lists, and judicial reports.',
            ],
            [
                'name' => 'Advocate/Lawyer',
                'slug' => 'advocate',
                'description' => 'Representative access to client matters, hearing dates, evidence uploads, vakalatnama, and case updates.',
            ],
            [
                'name' => 'Client/User',
                'slug' => 'client',
                'description' => 'Party access to own cases, hearing reminders, notifications, and uploaded documents.',
            ],
        ])->mapWithKeys(fn ($role) => [
            $role['slug'] => Role::updateOrCreate(['slug' => $role['slug']], $role),
        ]);
    }

    private function seedUsers($roles): array
    {
        $password = Hash::make('password');

        $groups = [
            'admins' => [
                ['Super Admin', 'admin@enyaya.local', '9000000001', 'eNyaya State Data Centre, New Delhi', 'super-admin'],
            ],
            'courtAdmins' => [
                ['Court Administrator', 'court@enyaya.local', '9000000002', 'District Court Administration Block, Delhi', 'court-admin'],
                ['Registry Officer Kavita Nair', 'registry@enyaya.local', '9000000003', 'Filing Counter, District Court Complex, Delhi', 'court-admin'],
            ],
            'judges' => [
                ['Justice A. Sharma', 'judge@enyaya.local', '9000000011', 'Courtroom 1, District and Sessions Court, Delhi', 'judge'],
                ['Justice Nandita Rao', 'judge.rao@enyaya.local', '9000000012', 'Courtroom 2, Family Court Wing, Delhi', 'judge'],
                ['Justice Vikram Menon', 'judge.menon@enyaya.local', '9000000013', 'Courtroom 3, Criminal Court Wing, Delhi', 'judge'],
                ['Justice Farah Qureshi', 'judge.qureshi@enyaya.local', '9000000014', 'Courtroom 4, Consumer and Cyber Cell, Delhi', 'judge'],
            ],
            'advocates' => [
                ['Advocate Meera Rao', 'advocate@enyaya.local', '9000000021', 'Chamber 18, District Court Complex, Delhi', 'advocate'],
                ['Advocate Arjun Sethi', 'advocate.sethi@enyaya.local', '9000000022', 'Chamber 41, District Court Complex, Delhi', 'advocate'],
                ['Advocate Priya Iyer', 'advocate.iyer@enyaya.local', '9000000023', 'Legal Aid Cell, Delhi', 'advocate'],
                ['Advocate Imran Khan', 'advocate.khan@enyaya.local', '9000000024', 'Cyber Law Practice, Nehru Place, Delhi', 'advocate'],
                ['Advocate Ritu Malhotra', 'advocate.malhotra@enyaya.local', '9000000025', 'Family Court Bar Association, Delhi', 'advocate'],
            ],
            'clients' => [
                ['Ravi Kumar', 'client@enyaya.local', '9876543210', 'Lajpat Nagar, New Delhi', 'client'],
                ['Sunita Devi', 'sunita.devi@example.local', '9876543211', 'Rohini, Delhi', 'client'],
                ['Amit Verma', 'amit.verma@example.local', '9876543212', 'Karol Bagh, Delhi', 'client'],
                ['Neha Bansal', 'neha.bansal@example.local', '9876543213', 'Dwarka, Delhi', 'client'],
                ['Farhan Ali', 'farhan.ali@example.local', '9876543214', 'Jamia Nagar, Delhi', 'client'],
                ['Lakshmi Traders', 'lakshmi.traders@example.local', '9876543215', 'Chandni Chowk, Delhi', 'client'],
                ['Kiran Joshi', 'kiran.joshi@example.local', '9876543216', 'Saket, Delhi', 'client'],
                ['Sanjay Gupta', 'sanjay.gupta@example.local', '9876543217', 'Pitampura, Delhi', 'client'],
                ['Pooja Mehra', 'pooja.mehra@example.local', '9876543218', 'Janakpuri, Delhi', 'client'],
                ['Ramesh Chandra', 'ramesh.chandra@example.local', '9876543219', 'Mayur Vihar, Delhi', 'client'],
            ],
        ];

        return collect($groups)->map(function ($users) use ($roles, $password) {
            return collect($users)->map(function ($user) use ($roles, $password) {
                return User::updateOrCreate(['email' => $user[1]], [
                    'name' => $user[0],
                    'phone' => $user[2],
                    'address' => $user[3],
                    'role_id' => $roles[$user[4]]->id,
                    'is_active' => true,
                    'email_verified_at' => now(),
                    'password' => $password,
                ]);
            })->values();
        })->all();
    }

    private function seedCases(array $users)
    {
        $clients = $users['clients'];
        $advocates = $users['advocates'];
        $judges = $users['judges'];

        $caseRows = [
            [
                'ENY-2026-00001',
                'Ravi Kumar vs State Electricity Board',
                'Civil',
                'Ravi Kumar',
                '9876543210',
                'State Electricity Board',
                '1912',
                now()->subDays(42),
                now()->addDays(8)->setTime(11, 0),
                'hearing_scheduled',
                'high',
                0,
                0,
                0,
                'Billing dispute and restoration of domestic electricity connection.',
                'verified',
            ],
            [
                'ENY-2026-00002',
                'State vs Amit Verma',
                'Bail',
                'State',
                null,
                'Amit Verma',
                '9876543212',
                now()->subDays(6),
                now()->addDay()->setTime(10, 30),
                'hearing_scheduled',
                'urgent',
                2,
                2,
                2,
                'Urgent bail application under consideration after remand proceedings.',
                'verified',
            ],
            [
                'ENY-2026-00003',
                'Sunita Devi vs Rajesh Kumar',
                'Family',
                'Sunita Devi',
                '9876543211',
                'Rajesh Kumar',
                '9876500101',
                now()->subDays(95),
                now()->addDays(3)->setTime(12, 0),
                'in_progress',
                'high',
                1,
                4,
                1,
                'Maintenance and custody petition pending before the family court.',
                'pending',
            ],
            [
                'ENY-2026-00004',
                'Lakshmi Traders vs North Delhi Logistics',
                'Consumer',
                'Lakshmi Traders',
                '9876543215',
                'North Delhi Logistics Pvt Ltd',
                '9811100004',
                now()->subDays(62),
                now()->addDays(14)->setTime(14, 15),
                'under_review',
                'normal',
                5,
                1,
                3,
                'Consumer complaint for damaged commercial goods and delayed settlement.',
                'not_uploaded',
            ],
            [
                'ENY-2026-00005',
                'Neha Bansal vs QuickKart Marketplace',
                'Cyber Crime',
                'Neha Bansal',
                '9876543213',
                'QuickKart Marketplace',
                '1800100100',
                now()->subDays(18),
                now()->addDays(2)->setTime(15, 0),
                'hearing_scheduled',
                'urgent',
                3,
                3,
                3,
                'Online payment fraud involving screenshots, bank trail, and marketplace records.',
                'verified',
            ],
            [
                'ENY-2026-00006',
                'Farhan Ali vs Municipal Corporation',
                'Civil',
                'Farhan Ali',
                '9876543214',
                'Municipal Corporation of Delhi',
                '155305',
                now()->subDays(120),
                now()->subDays(7)->setTime(11, 30),
                'in_progress',
                'normal',
                4,
                0,
                0,
                'Challenge to demolition notice after completion of evidence and arguments.',
                'verified',
            ],
            [
                'ENY-2026-00007',
                'State vs Ramesh Chandra',
                'Criminal',
                'State',
                null,
                'Ramesh Chandra',
                '9876543219',
                now()->subDays(210),
                now()->addDays(21)->setTime(10, 0),
                'in_progress',
                'high',
                9,
                2,
                2,
                'Criminal trial pending for prosecution evidence and witness examination.',
                'pending',
            ],
            [
                'ENY-2026-00008',
                'Kiran Joshi vs Sunrise Hospital',
                'Consumer',
                'Kiran Joshi',
                '9876543216',
                'Sunrise Hospital',
                '9811100008',
                now()->subDays(35),
                now()->addDays(9)->setTime(13, 0),
                'under_review',
                'normal',
                6,
                1,
                3,
                'Medical billing dispute and refund claim with supporting invoices.',
                'not_uploaded',
            ],
            [
                'ENY-2026-00009',
                'Pooja Mehra vs Rajiv Mehra',
                'Family',
                'Pooja Mehra',
                '9876543218',
                'Rajiv Mehra',
                '9811100009',
                now()->subDays(50),
                now()->addDays(6)->setTime(12, 30),
                'hearing_scheduled',
                'normal',
                8,
                4,
                1,
                'Mediation-linked matrimonial matter listed for settlement reporting.',
                'verified',
            ],
            [
                'ENY-2026-00010',
                'Sanjay Gupta vs Union Bank',
                'Civil',
                'Sanjay Gupta',
                '9876543217',
                'Union Bank',
                '18002002244',
                now()->subDays(22),
                now()->addDays(7)->setTime(16, 0),
                'under_review',
                'normal',
                7,
                0,
                0,
                'Civil recovery dispute involving loan restructuring correspondence.',
                'pending',
            ],
            [
                'ENY-2026-00011',
                'Amit Verma vs Cyber Cell Delhi',
                'Cyber Crime',
                'Amit Verma',
                '9876543212',
                'Cyber Cell Delhi',
                '1930',
                now()->subDays(12),
                now()->addDay()->setTime(15, 30),
                'hearing_scheduled',
                'urgent',
                2,
                3,
                3,
                'Petition seeking release of frozen account after UPI fraud investigation.',
                'verified',
            ],
            [
                'ENY-2026-00012',
                'Ravi Kumar vs Metro Housing Society',
                'Civil',
                'Ravi Kumar',
                '9876543210',
                'Metro Housing Society',
                '9811100012',
                now()->subDays(310),
                now()->subDays(18)->setTime(10, 0),
                'disposed',
                'low',
                0,
                0,
                0,
                'Disposed civil injunction matter after settlement terms were recorded.',
                'verified',
            ],
            [
                'ENY-2026-00013',
                'Lakshmi Traders vs GST Helpdesk Vendor',
                'Consumer',
                'Lakshmi Traders',
                '9876543215',
                'GST Helpdesk Vendor',
                '9811100013',
                now()->subDays(3),
                null,
                'filed',
                'normal',
                5,
                1,
                3,
                'Fresh consumer complaint regarding failed accounting software service.',
                'not_uploaded',
            ],
            [
                'ENY-2026-00014',
                'State vs Unknown Wallet Holders',
                'Cyber Crime',
                'State',
                null,
                'Unknown Wallet Holders',
                null,
                now()->subDays(9),
                now()->addDays(5)->setTime(10, 45),
                'under_review',
                'high',
                3,
                3,
                3,
                'Cyber fraud matter involving wallet trail, screenshots, and bank nodal records.',
                'pending',
            ],
            [
                'ENY-2026-00015',
                'Sunita Devi vs Delhi Shelter Board',
                'Urgent',
                'Sunita Devi',
                '9876543211',
                'Delhi Shelter Board',
                '1800110093',
                now()->subDay(),
                today()->setTime(14, 0),
                'hearing_scheduled',
                'urgent',
                1,
                2,
                0,
                'Urgent interim relief application concerning threatened eviction.',
                'pending',
            ],
        ];

        return collect($caseRows)->mapWithKeys(function ($row) use ($clients, $advocates, $judges) {
            $case = LegalCase::updateOrCreate(['case_number' => $row[0]], [
                'title' => $row[1],
                'category' => $row[2],
                'petitioner_name' => $row[3],
                'petitioner_contact' => $row[4],
                'respondent_name' => $row[5],
                'respondent_contact' => $row[6],
                'filing_date' => $row[7],
                'next_hearing_date' => $row[8],
                'status' => $row[9],
                'priority' => $row[10],
                'client_id' => $clients[$row[11]]->id,
                'advocate_id' => $advocates[$row[12]]->id,
                'judge_id' => $judges[$row[13]]->id,
                'summary' => $row[14],
                'vakalatnama_status' => $row[15],
                'vakalatnama_verified_at' => $row[15] === 'verified' ? now()->subDays(2) : null,
            ]);

            return [$row[0] => $case];
        });
    }

    private function seedHearings($cases, User $courtAdmin): void
    {
        $hearingRows = [
            ['ENY-2026-00001', now()->subDays(20)->setTime(11, 0), 'Courtroom 1', 6, 'completed', 'Case scrutiny and notice confirmation', 'Respondent sought time for documents.', null, null],
            ['ENY-2026-00001', now()->addDays(8)->setTime(11, 0), 'Courtroom 1', 8, 'scheduled', 'Evidence admission', 'Electricity bills and payment proof to be reviewed.', null, null],
            ['ENY-2026-00002', now()->addDay()->setTime(10, 30), 'Courtroom 3', 1, 'scheduled', 'Urgent bail hearing', 'Medical papers and remand report to be produced.', null, null],
            ['ENY-2026-00003', now()->subDays(44)->setTime(12, 0), 'Courtroom 2', 5, 'adjourned', 'Interim maintenance arguments', 'Respondent counsel unavailable.', 'Respondent', 'Counsel engaged in another court'],
            ['ENY-2026-00003', now()->subDays(18)->setTime(12, 15), 'Courtroom 2', 6, 'adjourned', 'Custody interaction report', 'Report not received from mediation centre.', 'Court', 'Mediation report awaited'],
            ['ENY-2026-00003', now()->addDays(3)->setTime(12, 0), 'Courtroom 2', 4, 'scheduled', 'Maintenance and custody directions', 'Parties directed to remain present.', null, null],
            ['ENY-2026-00004', now()->addDays(14)->setTime(14, 15), 'Courtroom 4', 12, 'scheduled', 'Notice on consumer complaint', 'Damaged consignment records to be filed.', null, null],
            ['ENY-2026-00005', now()->addDays(2)->setTime(15, 0), 'Courtroom 4', 2, 'scheduled', 'Cyber fraud evidence preview', 'Screenshots, bank SMS, and transaction IDs filed.', null, null],
            ['ENY-2026-00006', now()->subDays(7)->setTime(11, 30), 'Courtroom 1', 3, 'completed', 'Final arguments', 'Judgment reserved after both sides concluded.', null, null],
            ['ENY-2026-00007', now()->subDays(90)->setTime(10, 30), 'Courtroom 3', 7, 'adjourned', 'Prosecution evidence', 'Witness summons returned unserved.', 'Prosecution', 'Witness not served'],
            ['ENY-2026-00007', now()->subDays(45)->setTime(10, 15), 'Courtroom 3', 5, 'adjourned', 'Prosecution evidence', 'Investigating officer on official duty.', 'Prosecution', 'Investigating officer unavailable'],
            ['ENY-2026-00007', now()->subDays(12)->setTime(10, 30), 'Courtroom 3', 4, 'adjourned', 'Witness examination', 'Defence sought last opportunity for cross-examination.', 'Defence', 'Cross-examination preparation'],
            ['ENY-2026-00007', now()->addDays(21)->setTime(10, 0), 'Courtroom 3', 9, 'scheduled', 'Witness examination', 'Last opportunity recorded.', null, null],
            ['ENY-2026-00008', now()->addDays(9)->setTime(13, 0), 'Courtroom 4', 9, 'scheduled', 'Admission hearing', 'Hospital invoices and discharge summary to be reviewed.', null, null],
            ['ENY-2026-00009', now()->addDays(6)->setTime(12, 30), 'Courtroom 2', 7, 'rescheduled', 'Mediation settlement report', 'Rescheduled from earlier morning slot.', null, null],
            ['ENY-2026-00010', now()->addDays(7)->setTime(16, 0), 'Courtroom 1', 13, 'scheduled', 'Document scrutiny', 'Loan restructuring emails to be indexed.', null, null],
            ['ENY-2026-00011', now()->addDay()->setTime(15, 30), 'Courtroom 4', 3, 'scheduled', 'Frozen account release petition', 'Bank nodal officer response awaited.', null, null],
            ['ENY-2026-00012', now()->subDays(18)->setTime(10, 0), 'Courtroom 1', 1, 'completed', 'Settlement recording', 'Matter disposed after compromise terms.', null, null],
            ['ENY-2026-00014', now()->addDays(5)->setTime(10, 45), 'Courtroom 4', 5, 'scheduled', 'Cyber cell status report', 'Wallet transaction trail to be produced.', null, null],
            ['ENY-2026-00015', today()->setTime(14, 0), 'Courtroom 1', 1, 'scheduled', 'Urgent interim relief', 'Eviction action stayed until hearing.', null, null],
        ];

        foreach ($hearingRows as $row) {
            Hearing::updateOrCreate(
                ['legal_case_id' => $cases[$row[0]]->id, 'scheduled_at' => Carbon::parse($row[1])],
                [
                    'courtroom' => $row[2],
                    'hearing_sequence' => $row[3],
                    'status' => $row[4],
                    'purpose' => $row[5],
                    'notes' => $row[6],
                    'adjournment_requested_by' => $row[7],
                    'adjournment_reason' => $row[8],
                    'created_by' => $courtAdmin->id,
                ]
            );
        }
    }

    private function seedNotifications($cases, array $users): void
    {
        $notifications = [
            [$users['admins'][0], null, 'Demo court data ready', 'Bulk Indian judicial workflow data has been seeded for evaluation.', 'admin'],
            [$users['courtAdmins'][0], $cases['ENY-2026-00015'], 'Urgent matter listed today', 'Sunita Devi vs Delhi Shelter Board is listed in Courtroom 1 at 02:00 PM.', 'hearing'],
            [$users['courtAdmins'][1], $cases['ENY-2026-00002'], 'Bail application priority', 'State vs Amit Verma must appear at the top of the cause list due to urgent bail priority.', 'alert'],
            [$users['judges'][0], $cases['ENY-2026-00001'], 'Evidence admission pending', 'Electricity billing records are ready for review in the next hearing.', 'case'],
            [$users['judges'][1], $cases['ENY-2026-00003'], 'Repeated adjournments flagged', 'Sunita Devi vs Rajesh Kumar has two adjournments and requires timeline attention.', 'delay'],
            [$users['judges'][2], $cases['ENY-2026-00007'], 'Excessive delay alert', 'State vs Ramesh Chandra has three adjournments before the next witness examination.', 'delay'],
            [$users['judges'][3], $cases['ENY-2026-00005'], 'Digital evidence ready', 'Screenshots and bank trail metadata are expected for the cyber fraud hearing.', 'evidence'],
            [$users['advocates'][0], $cases['ENY-2026-00001'], 'Hearing reminder', 'Your civil matter is listed for evidence admission in Courtroom 1.', 'reminder'],
            [$users['advocates'][2], $cases['ENY-2026-00002'], 'Bail hearing tomorrow', 'Prepare remand papers and medical documents for urgent bail arguments.', 'reminder'],
            [$users['advocates'][3], $cases['ENY-2026-00011'], 'Bank response awaited', 'Follow up with the bank nodal officer before the frozen account hearing.', 'case'],
            [$users['clients'][0], $cases['ENY-2026-00001'], 'Next hearing scheduled', 'Your case is listed on '.$cases['ENY-2026-00001']->next_hearing_date?->format('d M Y h:i A').'.', 'hearing'],
            [$users['clients'][1], $cases['ENY-2026-00015'], 'Urgent relief hearing today', 'Your interim relief application is listed today in Courtroom 1.', 'alert'],
            [$users['clients'][3], $cases['ENY-2026-00005'], 'Cyber evidence update', 'Please keep screenshots, bank SMS, and transaction records available.', 'evidence'],
            [$users['clients'][9], $cases['ENY-2026-00007'], 'Criminal trial update', 'The next witness examination date has been scheduled.', 'hearing'],
        ];

        foreach ($notifications as [$user, $case, $title, $message, $type]) {
            CaseNotification::updateOrCreate(
                ['user_id' => $user->id, 'title' => $title],
                [
                    'legal_case_id' => $case?->id,
                    'message' => $message,
                    'type' => $type,
                    'read_at' => null,
                ]
            );
        }
    }
}
