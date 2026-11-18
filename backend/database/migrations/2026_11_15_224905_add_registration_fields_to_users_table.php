
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Jenis akun: PIC atau publik
            $table->string('account_type', 20)
                  ->nullable()
                  ->index();

            // Status akun
            $table->string('account_status', 30)
                  ->default('active')
                  ->index();

            // Kategori PIC
            $table->string('pic_category')
                  ->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'account_type',
                'account_status',
                'pic_category',
            ]);

        });
    }
};
