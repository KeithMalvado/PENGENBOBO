<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('ticket_id');
            $table->string('order_id')->unique();
            $table->integer('quantity');
            $table->decimal('total_amount', 10, 2);
            $table->string('status'); // contoh: pending, paid, failed
            $table->string('payment_method')->nullable(); // e.g., bank_transfer, gopay
            $table->string('transaction_id')->nullable(); // dari payment gateway
            $table->text('snap_token')->nullable(); // token dari Midtrans (misal)
            $table->json('payment_info')->nullable(); // informasi tambahan
            $table->timestamps();

            // foreign key opsional
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
