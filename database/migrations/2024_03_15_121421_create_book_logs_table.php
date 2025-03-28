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
        Schema::create('book_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('book_id')->unsigned()->index();
            $table->integer('want_to_read')->default(0);
            $table->integer('reading')->default(0);
            $table->integer('already_read')->default(0);
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
        Schema::dropIfExists('book_logs');
    }
};
