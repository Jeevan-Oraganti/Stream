<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->dropForeign(['notification_type_id']);

            $table->renameColumn('notification_type_id', 'notice_type_id');

            $table->foreign('notice_type_id')->references('id')->on('notice_types')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->dropForeign(['notice_type_id']);
            $table->renameColumn('notice_type_id', 'notification_type_id');
            $table->foreign('notification_type_id')->references('id')->on('notice_types')->onDelete('cascade');
        });
    }
};
