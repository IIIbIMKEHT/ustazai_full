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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->references('id')->on('subjects')->cascadeOnDelete();
            $table->foreignId('type_id')->references('id')->on('types')->cascadeOnDelete();
            $table->string('title');
            $table->text('content');
            $table->string('class_level');
            $table->string('word_link');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
