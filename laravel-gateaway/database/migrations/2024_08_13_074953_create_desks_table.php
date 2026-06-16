<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('desks', function (Blueprint $table) {
            $table->id();
            $table->integer('deskID')->unique()->nullable();
            $table->boolean('is_occupied')->default(false);
            $table->timestamp('joined_at')->nullable();
            $table->integer('nrOfSeats')->default(0);
            $table->string('description')->nullable();
            $table->double('x');
            $table->double('y');
            $table->string('code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desks');
    }
};
