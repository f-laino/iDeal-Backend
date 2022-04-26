<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToAgentTokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_tokens', function (Blueprint $table) {
            $table
                ->string('type', 100)
                ->comment('Indica la tipologia di token')
                ->after('agent_id')
                ->default(\App\AgentToken::$DEFAULT_TYPE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent_tokens', function (Blueprint $table) {
            $table->removeColumn('type');
        });
    }
}
