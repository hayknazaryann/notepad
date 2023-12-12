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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->onUpdate('cascade')->onDelete('cascade')
                ->references('id')->on('users');
            $table->integer('ordering');
            $table->string('key');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign('group_id')
                ->references('id')->on('note_groups');
            $table->string('title');
            $table->longText('text');
            $table->ipAddress('ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('password')->nullable();
            $table->date('expiration_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
