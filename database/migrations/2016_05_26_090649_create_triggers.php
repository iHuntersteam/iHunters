<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER Persons_BeforeInsert '.
            'BEFORE INSERT ON persons FOR EACH ROW BEGIN '.
            'SET NEW.name_hash = MD5(NEW.name); END
        ');

        DB::unprepared('CREATE TRIGGER Persons_BeforeUpdate
        BEFORE UPDATE ON persons
        FOR EACH ROW
        BEGIN
            SET NEW.name_hash = MD5(NEW.name);
        END');

        DB::unprepared('CREATE TRIGGER Keywords_BeforeInsert
        BEFORE INSERT ON keywords
        FOR EACH ROW
        BEGIN
            SET NEW.name_hash = MD5(NEW.name);
        END');

        DB::unprepared('CREATE TRIGGER Keywords_BeforeUpdate
        BEFORE UPDATE ON keywords
        FOR EACH ROW
        BEGIN
            SET NEW.name_hash = MD5(NEW.name);
        END');

        DB::unprepared('CREATE TRIGGER Pages_BeforeInsert
        BEFORE INSERT ON pages
        FOR EACH ROW
        BEGIN
            SET NEW.url_hash = MD5(NEW.url);
        END');

        DB::unprepared('CREATE TRIGGER Pages_BeforeUpdate
        BEFORE UPDATE ON pages
        FOR EACH ROW
        BEGIN
            SET NEW.url_hash = MD5(NEW.url);
        END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
