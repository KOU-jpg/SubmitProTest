<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('buyer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('condition_id')->constrained();
            $table->string('name');
            $table->text('description');
            $table->string('brand')->nullable();
            $table->unsignedInteger('price');
            $table->timestamp('sold_at')->nullable();
            $table->string('payment_status')->nullable();
            $table->timestamp('payment_expiry')->nullable();
            $table->enum('status', ['trading', 'completed','expired'])->nullable()->default(null);
            $table->unsignedInteger('favorite_count')->default(0);
            $table->unsignedInteger('like_count')->default(0);
            $table->timestamp('last_buyer_access')->nullable();
            $table->timestamp('last_seller_access')->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }

        public function transactionMessages()
    {
        return $this->hasMany(TransactionMessage::class);
    }
}
