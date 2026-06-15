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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('type'); // 'banjir', 'infrastruktur'
            $table->string('title');
            $table->text('description');
            $table->double('latitude');
            $table->double('longitude');
            $table->string('photo')->nullable();
            $table->integer('water_level')->default(0); // in cm
            $table->string('status')->default('pending'); // 'pending', 'verified', 'ditangani', 'selesai', 'rejected'
            $table->integer('priority_score')->default(0);
            $table->integer('duplicate_count')->default(0);
            $table->integer('upvote_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
