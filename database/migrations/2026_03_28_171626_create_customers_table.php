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
    Schema::create('customers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('father_name')->nullable();
        $table->string('lastname')->nullable();
        $table->string('phone')->unique();
        $table->string('whatsapp_number')->nullable();
        $table->decimal('monthly_rent', 10, 2);  // fixed monthly rent amount
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
