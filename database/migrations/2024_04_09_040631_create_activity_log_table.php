<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogTable extends Migration
{
    public function up()
    {
        Schema::create('activity_log', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->nullableMorphs('subject', 'subject');
            $table->nullableMorphs('causer', 'causer');
            $table->json('properties')->nullable();
            $table->timestamps();
            $table->index('log_name');
    
            // Add the event column
            $table->string('event')->nullable();
    
            // Add the batch_uuid column (if not already added)
            $table->uuid('batch_uuid')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_log');
    }
}
