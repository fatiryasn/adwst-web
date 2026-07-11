<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cottages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('destination_id');
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('destination_id')
                ->references('id')
                ->on('destinations')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cottages');
    }
};
