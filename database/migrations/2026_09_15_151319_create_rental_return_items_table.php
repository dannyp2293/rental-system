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
        Schema::create('rental_return_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rental_return_id')
                ->constrained('rental_returns')
                ->cascadeOnDelete();

            $table->foreignId('rental_item_id')
                ->constrained('rental_items')
                ->restrictOnDelete();

            $table->enum('condition', [
                'good',
                'damaged',
                'lost',
            ])->default('good');

            $table->decimal('damage_fee', 15, 2)->default(0);

            $table->decimal('lost_fee', 15, 2)->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique([
                'rental_return_id',
                'rental_item_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_return_items');
    }
};