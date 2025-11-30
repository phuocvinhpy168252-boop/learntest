<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
                Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('ten');
                $table->string('sdt')->unique();
                $table->string('email')->unique();
                $table->string('diachi')->nullable();
                $table->string('loai_taikhoan'); // lưu 'giangvien' hoặc 'sinhvien'
                $table->timestamp('ngaytao')->useCurrent();
                $table->string('matkhau');
                $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
