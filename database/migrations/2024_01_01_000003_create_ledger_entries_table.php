<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgerEntriesTable extends Migration
{
    public function up()
    {
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('restrict');
            $table->foreignId('transfer_id')->nullable()->constrained('transfers')->onDelete('set null');
            $table->bigInteger('amount');
            $table->integer('type')->comment('0=DEBIT, 1=CREDIT');
            $table->bigInteger('balance_after')->comment('Account balance after this entry');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('account_id');
            $table->index('transfer_id');
            $table->index('type');
            $table->index('created_at');
            $table->index(['account_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ledger_entries');
    }
}
