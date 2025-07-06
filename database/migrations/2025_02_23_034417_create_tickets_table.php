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
            $table->enum('status', [
                'queue',               // Tiket dalam antrean
                'open',                // Sedang dibuka oleh admin
                'waiting_for_answer',  // Menunggu jawaban dari user
                'waiting_for_resolved', // Menunggu konfirmasi selesai dari user
                'on_going',            // Proses penyelesaian (jika dibutuhkan)
                'resolved'             // Tiket selesai
            ])->default('queue');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pembuat tiket
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->integer("admin_rating")->default(0);
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
