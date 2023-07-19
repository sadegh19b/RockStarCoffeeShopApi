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
        Schema::create('option_values', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('option_value_assignments', function (Blueprint $table) {
            $table->foreignId('option_id')->constrained()->cascadeOnDelete();
            $table->foreignId('option_value_id')->constrained()->cascadeOnDelete();

            $table->primary(['option_id', 'option_value_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('option_value_assignments');
        Schema::dropIfExists('option_values');
    }
};
