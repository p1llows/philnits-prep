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
        Schema::create('choices', function (Blueprint $table) {
            $table->id();
            
            // Question reference
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            
            // Choice metadata
            $table->string('code'); // A, B, C, D, etc.
            $table->text('text'); // The actual choice text
            
            // Ordering
            $table->integer('display_order')->default(0);
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index(['question_id', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('choices');
    }
};
