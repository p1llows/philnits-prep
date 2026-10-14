<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            // Official ITPEC IP Field: 'strategy', 'management', 'technology'
            $table->string('field')->default('technology')->after('name');
            $table->index('field');
        });

        Schema::table('assessments', function (Blueprint $table) {
            // Whether both overall (>=60%) and each field (>=30%) requirements are met
            $table->boolean('passed_exam')->nullable()->after('score_percentage');
            // JSON storing scores per field: {"strategy": {"total": 10, "correct": 7, "percentage": 70, "passed": true}, ...}
            $table->json('field_performance')->nullable()->after('passed_exam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropIndex(['field']);
            $table->dropColumn('field');
        });

        Schema::table('assessments', function (Blueprint $table) {
            $table->dropColumn(['passed_exam', 'field_performance']);
        });
    }
};
