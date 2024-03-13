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
        Schema::create('translates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('original_id')->unsigned()->index();
            $table->bigInteger('translated_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('original_id')->references('id')
            ->on('books')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('translated_id')->references('id')
            ->on('books')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translates');
    }
};
