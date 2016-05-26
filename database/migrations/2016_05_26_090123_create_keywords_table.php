<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE TABLE IF NOT EXISTS keywords (
            id INT NOT NULL AUTO_INCREMENT,
            name TEXT NOT NULL,
            name_hash CHAR(32) UNIQUE,
            person_id INT NOT NULL,
            PRIMARY KEY (id),
            FOREIGN KEY (person_id)
                REFERENCES persons(id)
                ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('keywords');
    }
}
