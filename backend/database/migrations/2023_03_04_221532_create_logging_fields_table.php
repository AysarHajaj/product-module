<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('logging_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('logging_id')->nullable();
            $table->foreign('logging_id')->references('id')->on('logging');
            $table->string('field_name')->nullable();
            $table->string('field_old_value')->nullable();
            $table->string('field_new_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logging_fields', function (Blueprint $table) {
            $table->dropForeign(['logging_id']);
        });

        Schema::dropIfExists('logging_fields');
    }
};
