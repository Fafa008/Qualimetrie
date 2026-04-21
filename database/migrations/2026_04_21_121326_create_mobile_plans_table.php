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
    Schema::create('mobile_plans', function (Blueprint $table) {
        $table->id();
        $table->integer('dataUsed');
        $table->integer('dataNonEU_MB');
        $table->integer('anciennete');
        $table->boolean('isEtudiant');
        $table->float('total')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_plans');
    }
};
