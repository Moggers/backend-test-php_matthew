<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NestedsetMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('messages', function(Blueprint $table) {
        $table->dropColumn('parent_id');
      });
      Schema::table('messages', function(Blueprint $table) {
        $table->nestedSet();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('messages', function(Blueprint $table) {
        $table->dropNestedSet();
      });
    }
}
