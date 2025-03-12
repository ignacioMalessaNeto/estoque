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
        Schema::create('moviments', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer("quantity")->nullable();

            $table->string("type_moviment", 7)->nullable(); // 'entrada' ou 'saida'

            $table->unsignedBigInteger('id_entrie')->nullable();
            $table->foreign('id_entrie')->references('id')->on('stocks');

            $table->unsignedBigInteger('id_out')->nullable();
            $table->foreign('id_out')->references('id')->on('outs')->onDelete('cascade');

            $table->unsignedBigInteger('updated', 0)->nullable()->length(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moviments');
    }
};
