<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE legal_cases MODIFY status ENUM('filed','accepted','under_review','hearing_scheduled','in_progress','judgment_reserved','disposed','dismissed') DEFAULT 'filed'");
        }

        Schema::table('legal_cases', function (Blueprint $table) {
            $table->string('vakalatnama_path')->nullable()->after('summary');
            $table->enum('vakalatnama_status', ['not_uploaded', 'pending', 'verified', 'rejected'])->default('not_uploaded')->after('vakalatnama_path');
            $table->timestamp('vakalatnama_verified_at')->nullable()->after('vakalatnama_status');
        });

        Schema::table('hearings', function (Blueprint $table) {
            $table->unsignedInteger('hearing_sequence')->nullable()->after('courtroom');
            $table->string('adjournment_requested_by')->nullable()->after('notes');
            $table->string('adjournment_reason')->nullable()->after('adjournment_requested_by');
        });
    }

    public function down(): void
    {
        Schema::table('hearings', function (Blueprint $table) {
            $table->dropColumn(['hearing_sequence', 'adjournment_requested_by', 'adjournment_reason']);
        });

        Schema::table('legal_cases', function (Blueprint $table) {
            $table->dropColumn(['vakalatnama_path', 'vakalatnama_status', 'vakalatnama_verified_at']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE legal_cases MODIFY status ENUM('filed','under_review','hearing_scheduled','in_progress','disposed','dismissed') DEFAULT 'filed'");
        }
    }
};
