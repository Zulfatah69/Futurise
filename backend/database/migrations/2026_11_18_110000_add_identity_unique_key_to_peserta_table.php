<?php

use App\Models\Peserta;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peserta', function (Blueprint $table): void {
            $table->string('identity_unique_key', 255)
                ->nullable()
                ->unique()
                ->after('nomor_identitas');
        });

        // Backfill kunci unik identitas untuk data peserta yang sudah ada
        DB::table('peserta')
            ->select(['id', 'jenis_peserta', 'institusi', 'nomor_identitas'])
            ->orderBy('id')
            ->chunkById(500, function ($rows): void {
                foreach ($rows as $row) {
                    $normalizedNumber = Peserta::normalizeIdentityNumber($row->nomor_identitas);
                    $uniqueKey = Peserta::buildIdentityUniqueKey(
                        $row->jenis_peserta,
                        $row->institusi,
                        $normalizedNumber
                    );

                    DB::table('peserta')
                        ->where('id', $row->id)
                        ->update([
                            'nomor_identitas'     => $normalizedNumber,
                            'identity_unique_key' => $uniqueKey,
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('peserta', function (Blueprint $table): void {
            $table->dropUnique(['identity_unique_key']);
            $table->dropColumn('identity_unique_key');
        });
    }
};
