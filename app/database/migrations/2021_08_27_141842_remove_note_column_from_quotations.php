<?php

use App\Models\Quotation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveNoteColumnFromQuotations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->text('notes')->nullable();
        });

        $this->fillAddedColumns();
    }

    protected function fillAddedColumns()
    {
        $quotations = Quotation::with('proposal')->get();

        foreach ($quotations as $quotation) {
            $quotation->update([
                'notes' => $quotation->proposal->notes,
            ]);
        }
    }
}
