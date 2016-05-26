<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE TABLE IF NOT EXISTS persons (id INT NOT NULL AUTO_INCREMENT,'.
            'name TEXT NOT NULL,name_hash CHAR(32) UNIQUE, PRIMARY KEY (id)) ENGINE=InnoDB '.
            'DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
            ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('persons');
    }
}
