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
        // 1. Table audit_logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action'); // e.g. question.approved, role.changed, rules.updated
            $table->string('target_type')->nullable(); // Model class name or category
            $table->string('target_id')->nullable();
            $table->json('detail')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('action');
            $table->index('target_type');
            $table->index(['target_type', 'target_id']);
            $table->index('created_at');
        });

        // 2. Add is_active and last_active_at to users; convert role to string to allow 'editor'
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('learner')->change();
            $table->boolean('is_active')->default(true)->after('role');
            $table->timestamp('last_active_at')->nullable()->after('is_active');
        });

        // 3. Add archived_at to assessments for pre-test reset
        Schema::table('assessments', function (Blueprint $table) {
            $table->timestamp('archived_at')->nullable()->after('status');
            $table->index('archived_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropIndex(['archived_at']);
            $table->dropColumn('archived_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'last_active_at']);
        });

        Schema::dropIfExists('audit_logs');
    }
};
