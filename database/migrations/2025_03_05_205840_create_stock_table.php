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
        //TODO VERIFICAR SE A Lógica está certa
        Schema::create('stocks', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer("quantity")->unsigned()->nullable();
            $table->unsignedBigInteger('id_item')->nullable();
            $table->foreign('id_item')->references('id')->on('itens')->onDelete('cascade');
            $table->unsignedBigInteger('id_category')->nullable();
            $table->foreign('id_category')->references('id')->on('category')->onDelete('cascade');
            $table->unsignedBigInteger('id_address')->nullable();
            $table->foreign('id_address')->references('id')->on('address')->onDelete('cascade');
            $table->unsignedBigInteger('create_by')->nullable();
            $table->foreign('create_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock');
    }
};
