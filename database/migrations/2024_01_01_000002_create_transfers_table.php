<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTransfersTable extends Migration
{
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_account_id')->constrained('accounts')->onDelete('restrict');
            $table->foreignId('destination_account_id')->constrained('accounts')->onDelete('restrict');
            $table->bigInteger('amount');
            $table->integer('status')->default(0)->comment('0=PENDING, 1=SUCCESS, 2=FAILED');
            $table->text('failure_reason')->nullable();
            $table->string('reference', 100)->unique()->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index('source_account_id');
            $table->index('destination_account_id');
            $table->index('status');
            $table->index('created_at');
            $table->index('reference');
        });

     
    }

    public function down()
    {
        Schema::dropIfExists('transfers');
    }
}
