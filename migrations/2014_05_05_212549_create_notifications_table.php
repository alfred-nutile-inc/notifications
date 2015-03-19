<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function(Blueprint $table) {
            $table->string('id', 36);
            $table->string('from_id', 36);
            $table->string('from_type');
            $table->string('to_id', 36);
            $table->string('to_type');
            $table->string('notification_category_id');
            $table->string('notification_message_id');
            $table->tinyInteger('read')->default(0);
            $table->timestamps();

            $table->index('from_id');
            $table->index('from_type');
            $table->index('to_id');
            $table->index('to_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }

}
