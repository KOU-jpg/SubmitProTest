<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryItemsTable extends Migration
{
    public function up()
    {
        Schema::create('category_items', function (Blueprint $table) {
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->primary(['item_id', 'category_id']); // 複合主キー推奨
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_items');
    }
}
