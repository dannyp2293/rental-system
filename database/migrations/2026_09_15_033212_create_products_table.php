<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();

            $table->decimal('price_per_day', 15, 2)->default(0);
            $table->decimal('price_per_hour', 15, 2)->default(0);

            $table->unsignedInteger('stock')->default(0);

            $table->enum('status', [
                'available',
                'rented',
                'maintenance'
            ])->default('available');

            $table->string('image')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};