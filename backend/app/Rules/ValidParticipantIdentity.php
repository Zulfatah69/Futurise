<?php

namespace App\Rules;

use App\Models\Peserta;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidParticipantIdentity implements ValidationRule
{
    public function __construct(
        protected ?string $jenisPeserta,
        protected ?string $institusi,
        protected ?int $ignorePesertaId = null
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $normalized = Peserta::normalizeIdentityNumber(is_string($value) ? $value : null);
        if ($normalized === null) {
            return;
        }

        $jenis = mb_strtolower(trim((string) $this->jenisPeserta));

        if (in_array($jenis, ['siswa', 'siswa_smk'], true) && ! preg_match('/^\d{10}$/', $normalized)) {
            $fail('NISN harus terdiri dari tepat 10 digit angka.');
            return;
        }

        if ($jenis === 'umum' && ! preg_match('/^\d{16}$/', $normalized)) {
            $fail('NIK KTP harus terdiri dari tepat 16 digit angka.');
            return;
        }

        $uniqueKey = Peserta::buildIdentityUniqueKey($jenis, $this->institusi, $normalized);
        if ($uniqueKey === null) {
            return;
        }

        $query = Peserta::query()->where('identity_unique_key', $uniqueKey);
        if ($this->ignorePesertaId !== null) {
            $query->where('id', '!=', $this->ignorePesertaId);
        }

        if ($query->exists()) {
            $message = match ($jenis) {
                'siswa', 'siswa_smk' => 'NISN ini sudah terdaftar pada sistem.',
                'umum'               => 'NIK KTP ini sudah terdaftar pada sistem.',
                default              => 'NIM / nomor identitas ini sudah terdaftar untuk institusi tersebut.',
            };

            $fail($message);
        }
    }
}
