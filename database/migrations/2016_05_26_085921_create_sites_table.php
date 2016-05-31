<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('SET GLOBAL innodb_file_format = BARRACUDA;SET GLOBAL innodb_large_prefix = ON;CREATE TABLE IF NOT EXISTS sites (id INT NOT NULL AUTO_INCREMENT,name VARCHAR(256) NOT NULL UNIQUE,PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sites');
    }
}
