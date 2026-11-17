<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sponsors', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('website_url', 255)->nullable();
            $table->string('tingkat', 20)->nullable();
            $table->integer('urutan')->nullable();
            $table->boolean('aktif')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsors');
    }
};