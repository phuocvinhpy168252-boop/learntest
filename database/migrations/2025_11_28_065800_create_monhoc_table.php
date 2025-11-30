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
        Schema::create('monhoc', function (Blueprint $table) {
            $table->string('ma_mon_hoc', 10)->primary();
            $table->string('ten_mon_hoc', 255);
            $table->integer('so_tin_chi');
            $table->text('mo_ta')->nullable();
            $table->enum('trang_thai', ['hoat_dong', 'vo_hieu_hoa'])->default('hoat_dong');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monhoc');
    }
};