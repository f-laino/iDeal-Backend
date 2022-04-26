<?php

use App\Common\Models\DocumentList;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('contractual_category_id')->unsigned();
            $table->enum('broker', array_keys(DocumentList::getBrokerCodes()));
            $table->bigInteger('document_id')->unsigned();
            $table->string('title')->nullable();
            $table->string('link')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('contractual_category_id')
                    ->references('id')
                    ->on('contractual_categories')
                    ->onDelete('cascade');

            $table->foreign('document_id')
                    ->references('id')
                    ->on('documents')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_list');
    }
}
