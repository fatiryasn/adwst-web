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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('code', 20)->unique();
            $table->string('full_name', 100);
            $table->string('email', 100)->unique();
            $table->string('phone_number', 20);

            $table->string('promotion_channels', 255)->nullable();
            $table->text('join_reason')->nullable();

            $table->integer('total_points')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
