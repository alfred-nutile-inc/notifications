<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransColumnsToNotificationMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification_messages', function (Blueprint $table) {
            $table->boolean('translatable')->default(false);
            $table->string('trans_function')->default('trans');
            $table->text('trans_params')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification_messages', function (Blueprint $table) {
            $table->dropColumn('translatable');
            $table->dropColumn('trans_function');
            $table->dropColumn('trans_params');
        });
    }
}
