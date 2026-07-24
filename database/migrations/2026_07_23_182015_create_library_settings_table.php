<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_settings', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('max_borrow_days')->default(14);

            $table->unsignedInteger('max_borrow_limit')->default(5);

            $table->decimal('borrow_charge', 10, 2)->default(20);

            $table->decimal('late_fee_per_day', 10, 2)->default(5);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_settings');
    }
};