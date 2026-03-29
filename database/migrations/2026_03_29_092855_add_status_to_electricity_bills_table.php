<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('electricity_bills', function (Blueprint $table) {
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid')->after('amount');
        });
    }

    public function down()
    {
        Schema::table('electricity_bills', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};