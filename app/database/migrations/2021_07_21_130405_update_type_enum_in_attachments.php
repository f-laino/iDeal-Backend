<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTypeEnumInAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE attachments MODIFY `type` ENUM("CUSTOMER", "QUOTATION")');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE attachments MODIFY `type` ENUM("AGENT", "OFFER", "QUOTATION", "CONTRACTUAL_CATEGORY")');
    }
}
