<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('source_packages', function (Blueprint $table) {
            $table->string('status')->default('uploaded')->change();
            $table->string('exam_session_label')->nullable()->after('name');
            $table->string('paper_path')->nullable()->after('status');
            $table->string('answer_key_path')->nullable()->after('paper_path');
            $table->string('use')->default('practice')->after('answer_key_path'); // 'pretest' or 'practice'
            $table->boolean('is_official')->default(true)->after('use');
            $table->text('notes')->nullable()->after('is_official');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->string('status')->default('published')->change();
            $table->string('correct_answer_code')->nullable()->change();
            $table->string('answer_source')->default('manual')->after('correct_answer_code'); // official_key, model_suggestion, manual
            $table->integer('source_page')->nullable()->after('source_question_number');
            $table->string('explanation_status')->default('none')->after('explanation'); // none, ai_draft, approved
            $table->text('review_note')->nullable()->after('explanation_status');
            $table->unsignedBigInteger('approved_by')->nullable()->after('review_note');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('code_block')->nullable()->after('question_text');
            $table->string('image_path')->nullable()->after('code_block');

            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });

        // Backfill: existing questions become status published, answer_source manual, explanation_status approved if an explanation exists, else none.
        DB::table('questions')->whereNotNull('explanation')->where('explanation', '!=', '')->update([
            'status' => 'published',
            'answer_source' => 'manual',
            'explanation_status' => 'approved',
        ]);

        DB::table('questions')->where(function ($q) {
            $q->whereNull('explanation')->orWhere('explanation', '');
        })->update([
            'status' => 'published',
            'answer_source' => 'manual',
            'explanation_status' => 'none',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'answer_source',
                'source_page',
                'explanation_status',
                'review_note',
                'approved_by',
                'approved_at',
                'code_block',
                'image_path',
            ]);
        });

        Schema::table('source_packages', function (Blueprint $table) {
            $table->dropColumn([
                'exam_session_label',
                'paper_path',
                'answer_key_path',
                'use',
                'is_official',
                'notes',
            ]);
        });
    }
};
