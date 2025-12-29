<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bodegas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_bodegas');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bodegas');
    }
};
