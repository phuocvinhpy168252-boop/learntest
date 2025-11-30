<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('giangvien', function (Blueprint $table) {
            // Xóa cột cũ
            $table->dropColumn('ma_giangvien');
        });
        
        Schema::table('giangvien', function (Blueprint $table) {
            // Thêm cột mới với kiểu string
            $table->string('ma_giangvien', 20)->primary()->first();
        });
    }

    public function down()
    {
        Schema::table('giangvien', function (Blueprint $table) {
            $table->dropColumn('ma_giangvien');
        });
        
        Schema::table('giangvien', function (Blueprint $table) {
            $table->bigIncrements('ma_giangvien')->first();
        });
    }
};