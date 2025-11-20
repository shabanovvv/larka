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
         * Дополнительные данные о менторе (1:1 к users)
         * - описание
         * - опыт
         * - рейтинг
         * - активность
         * Используется для карточек менторов и подбора.
         */
        Schema::create('mentor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('experience_years')->default(0);
            $table->decimal('rate', 3, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        /**
         * Список изучаемых и доступных технологий.
         * Пример: php, laravel, vue, python.
         */
        Schema::create('technologies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        /**
         * Связывает профиль ментора с технологиями, которыми он владеет.
         */
        Schema::create('mentor_technology', function (Blueprint $table) {
            $table->foreignId('mentor_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('technology_id')->constrained()->cascadeOnDelete();
            $table->unique(['mentor_profile_id', 'technology_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentor_technology');
        Schema::dropIfExists('technologies');
        Schema::dropIfExists('mentor_profiles');
    }
};
