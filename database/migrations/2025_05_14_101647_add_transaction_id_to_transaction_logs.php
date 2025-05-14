<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('transaction_logs', function (Blueprint $table) {
        $table->string('transaction_id')->nullable();
    });
}

public function down()
{
    Schema::table('transaction_logs', function (Blueprint $table) {
        $table->dropColumn('transaction_id');
    });
}
};
