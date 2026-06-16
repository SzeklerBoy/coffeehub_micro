<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('desk_id')->nullable()->constrained();
            $table->foreignId('group_id')->nullable()->constrained();
            $table->timestamp('ordered_at');
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('waiter_id')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['ordered', 'pending', 'served', 'completed', 'cancelled'])->default('ordered');
            $table->double('totalPrepTime')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
