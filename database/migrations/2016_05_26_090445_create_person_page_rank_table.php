<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonPageRankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE TABLE IF NOT EXISTS person_page_rank (
            person_id INT NOT NULL,
            page_id INT NOT NULL,
            rank INT NOT NULL,
            FOREIGN KEY (person_id)
                REFERENCES persons(id)
                ON DELETE CASCADE,
            FOREIGN KEY (page_id)
                REFERENCES pages(id)
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
        Schema::drop('person_page_rank');
    }
}
