<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionMessagesTable extends Migration
{

    public function up()
    {
        Schema::create('transaction_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // メッセージ送信者
            $table->text('message');
            $table->string('image_path')->nullable();
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamps();
        });

        
    }


    public function down()
    {
        Schema::dropIfExists('transaction_messages');
    }
    
}

