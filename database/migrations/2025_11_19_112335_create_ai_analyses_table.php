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
        /**
         * Результат AI-анализа кода:
         * Используется для подготовки менторского ревью.
         */
        Schema::create('ai_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('code_submission_id')->constrained()->cascadeOnDelete();
            $table->string('provider')->nullable();
            $table->text('summary')->nullable();
            $table->longText('suggestions')->nullable();
            $table->unsignedTinyInteger('score')->nullable();
            $table->json('raw_response')->nullable();
            $table->timestamps();
        });

        /**
         * Ревью ментора.
         * Может быть несколько ревью на одну заявку (при доработках).
         */
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('code_submission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mentor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('pending');
            $table->text('comment')->nullable();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('ai_analyses');
    }
};
