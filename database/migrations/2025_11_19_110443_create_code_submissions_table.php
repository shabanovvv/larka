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
         * Заявки пользователей:
         * - загруженный код
         * - описание задачи
         * - язык
         * - статус (draft, waiting, in_review, done)
         * - назначенный ментор
         * Основная сущность воркфлоу.
         */
        Schema::create('code_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status', 32)->default('draft');
            $table->foreignId('mentor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status']);
        });

        /**
         * Файлы, загруженные пользователем:
         * Позволяет хранить исходный код целиком.
         */
        Schema::create('submission_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('code_submission_id')->constrained()->cascadeOnDelete();
            $table->string('filename')->nullable();
            $table->longText('content');
            $table->timestamps();
        });

        /**
         * Связывает код с технологиями
         */
        Schema::create('code_submission_technology', function (Blueprint $table) {
            $table->foreignId('code_submission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('technology_id')->constrained()->cascadeOnDelete();
            $table->unique(
                ['code_submission_id', 'technology_id'],
                'code_sub_tech_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('code_submission_technology');
        Schema::dropIfExists('submission_files');
        Schema::dropIfExists('code_submissions');
    }
};
