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
        Schema::create('employee_bonus_settings', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | KARYAWAN
            |--------------------------------------------------------------------------
            */

            $table->foreignId('employee_id')
                ->constrained('users')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | TIPE BONUS
            |--------------------------------------------------------------------------
            |
            | percentage      = berdasarkan persentase nilai rental
            | per_transaction = nominal tetap setiap transaksi
            |
            */

            $table->enum('bonus_type', [
                'percentage',
                'per_transaction',
            ]);


            /*
            |--------------------------------------------------------------------------
            | NILAI BONUS
            |--------------------------------------------------------------------------
            |
            | percentage:
            | 5 = 5%
            |
            | per_transaction:
            | 10000 = Rp10.000 / transaksi
            |
            */

            $table->decimal('bonus_value', 15, 2);


            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->boolean('active')
                ->default(true);


            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */

            $table->index('employee_id');
            $table->index('bonus_type');
            $table->index('active');


            /*
            |--------------------------------------------------------------------------
            | SATU SETTING BONUS PER KARYAWAN
            |--------------------------------------------------------------------------
            */

            $table->unique('employee_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_bonus_settings');
    }
};