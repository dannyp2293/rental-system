<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('customer_code')->unique();
            $table->string('name');
            $table->text('address')->nullable();

            $table->string('whatsapp', 30);

            $table->enum('guarantee_type', [
                'ktp',
                'sim',
                'passport',
                'other'
            ])->nullable();

            $table->string('guarantee_number')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};