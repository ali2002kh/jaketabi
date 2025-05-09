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
        Schema::create('genre_books', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('genre_id')->unsigned()->index();
            $table->bigInteger('book_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('genre_id')->references('id')
            ->on('genres')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('book_id')->references('id')
            ->on('books')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genre_books');
    }
};
