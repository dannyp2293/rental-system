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
        Schema::create('rental_returns', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rental_id')
                ->unique()
                ->constrained('rentals')
                ->cascadeOnDelete();

            $table->dateTime('returned_at');

            $table->unsignedInteger('late_hours')->default(0);

            $table->decimal('late_fee', 15, 2)->default(0);

            $table->decimal('damage_fee', 15, 2)->default(0);

            $table->decimal('lost_fee', 15, 2)->default(0);

            $table->decimal('deposit_deduction', 15, 2)->default(0);

            $table->decimal('deposit_return', 15, 2)->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_returns');
    }
};