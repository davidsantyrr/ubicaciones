<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->index();
            $table->unsignedBigInteger('ubicaciones_id');
            $table->unsignedInteger('stock')->default(0);
            $table->string('estatus')->default('disponible');
            $table->foreign('ubicaciones_id')->references('id')->on('ubicaciones')->onDelete('cascade');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
