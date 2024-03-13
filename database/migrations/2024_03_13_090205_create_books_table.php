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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('ISBN');
            $table->string('name');
            $table->text('image')->nullable();
            $table->string('author');
            $table->bigInteger('category_id')->unsigned()->index();
            $table->date('release_date')->nullable();
            $table->bigInteger('publisher_id')->unsigned()->index();
            $table->text('description')->nullable();
            $table->string('translator')->nullable();
            $table->integer('page_count')->nullable();
            $table->integer('status')->default(1);
            $table->string('LCC')->nullable();
            $table->string('DDC')->nullable();
            $table->string('ISBN_period')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')
            ->on('book_categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('publisher_id')->references('id')
            ->on('publishers')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
