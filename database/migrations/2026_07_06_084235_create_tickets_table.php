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
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('code')->unique();

            $table->uuid('destination_id');
            $table->uuid('affiliate_id')->nullable();

            $table->string('customer_name', 100);
            $table->string('customer_phone', 20);
            $table->string('customer_email', 150)->nullable();

            $table->date('visit_date')->nullable();
            $table->date('departure_date')->nullable();
            $table->string('referral_source', 255)->nullable();

            $table->decimal('ticket_price', 15, 2);

            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded']);
            $table->enum('ticket_status', [
                'active',
                'checked_in',
                'expired',
                'cancelled',
            ])->default('active');

            $table->timestamp('payment_verified_at')->nullable();
            $table->uuid('payment_verified_by')->nullable();

            $table->timestamp('checked_in_at')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            //FKs
            $table->foreign('destination_id')
                ->references('id')
                ->on('destinations')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('affiliate_id')
                ->references('id')
                ->on('affiliates')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('payment_verified_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            //IDXs
            $table->index('code');
            $table->index(['payment_status', 'ticket_status']);
            $table->index('visit_date');
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
