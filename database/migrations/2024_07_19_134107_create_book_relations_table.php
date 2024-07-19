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
        Schema::create('book_relations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('book_id')->unsigned()->index();
            $table->text('related_books_id')->nullable();
            $table->timestamps();

            $table->foreign('book_id')->references('id')
            ->on('books')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_relations');
    }
};
