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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['pending', 'in-progress', 'completed'])->default('pending');
            // Create a user_id column in the current table (e.g., tasks table).
            // This column will store the id of the user who owns the task.
            // It is defined as unsignedBigInteger because user ids are positive large numbers.
            $table->unsignedBigInteger('user_id');
            // Define a foreign key relationship for the user_id column.
            // References the id column in the users table to establish a link between tasks and users.
            // 'onDelete("cascade")' ensures that if a user is deleted, all their tasks are also deleted.
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
