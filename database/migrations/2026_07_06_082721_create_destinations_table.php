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
        Schema::create('destinations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name', 200);
            $table->string('slug', 250)->unique();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->text('address')->nullable();
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->text('thumbnail')->nullable();


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
