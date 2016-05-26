<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE TABLE IF NOT EXISTS pages (
            id INT NOT NULL AUTO_INCREMENT,
            url TEXT NOT NULL,
            site_id INT NOT NULL,
            found_date_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            last_scan_date TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            url_hash CHAR(32) UNIQUE,
            PRIMARY KEY (id),
            FOREIGN KEY (site_id)
                REFERENCES sites(id)
                ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pages');
    }
}
