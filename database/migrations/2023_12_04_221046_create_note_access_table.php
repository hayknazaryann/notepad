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
        Schema::create('note_users', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('note_id');
            $table->foreign('note_id')
                ->onUpdate('cascade')->onDelete('cascade')
                ->references('id')->on('notes');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->onUpdate('cascade')->onDelete('cascade')
                ->references('id')->on('users');

            $table->string('access');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_users');
    }
};
