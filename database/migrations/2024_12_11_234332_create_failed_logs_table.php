<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('failed_logs', function (Blueprint $table) {
            $table->id();
            $table->string('level');
            $table->text('message');
            $table->text('project');
            $table->json('context')->nullable();
            $table->string('environment');

            $table->boolean('sent')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('failed_logs');
    }
};
