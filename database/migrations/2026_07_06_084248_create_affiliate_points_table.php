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
        Schema::create('affiliate_points', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('affiliate_id');
            $table->uuid('ticket_id');

            $table->integer('points');

            $table->text('description')->nullable();

            $table->timestamp('created_at')->useCurrent();

            //FKs
            $table->foreign('affiliate_id')
                ->references('id')
                ->on('affiliates')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('ticket_id')
                ->references('id')
                ->on('tickets')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            //IDXs
            $table->index('affiliate_id');
            $table->index('ticket_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_points');
    }
};
