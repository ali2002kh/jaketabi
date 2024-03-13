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
        Schema::create('shelf_books', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shelf_id')->unsigned()->index();
            $table->bigInteger('book_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('shelf_id')->references('id')
            ->on('shelves')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('book_id')->references('id')
            ->on('books')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shelf_books');
    }
};
