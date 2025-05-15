<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul tiket
            $table->text('description'); // Deskripsi masalah
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open'); // Status tiket
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID user yang membuat tiket
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
