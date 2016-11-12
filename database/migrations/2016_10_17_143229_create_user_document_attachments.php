<?php

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDocumentAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('docs_db')->create('user_document_attachments', function (Blueprint $table) {
            $db = DB::connection('mysql')->getDatabaseName();

            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('mime_type');
            $table->integer('user_document_id')->unsigned();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on(new Expression($db . '.users'));
        });
        // Adding blob Data
        DB::connection('docs_db')
            ->statement("ALTER TABLE user_document_attachments ADD data MEDIUMBLOB NOT NULL AFTER user_document_id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('docs_db')->drop('user_document_attachments');
    }
}
