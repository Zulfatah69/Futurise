<?php

namespace App\Policies;

class PesertaPolicy extends AdminResourcePolicy
{
    protected function viewRoles(): array
    {
        return [
            'super-admin',
            'ketua-pelaksana',
            'sekretariat',
            'kampus-sekolah',
            'program-kurasi',
            'registrasi-layanan',
        ];
    }

    protected function manageRoles(): array
    {
        return [
            'super-admin',
            'ketua-pelaksana',
            'sekretariat',
            'kampus-sekolah',
            'registrasi-layanan',
        ];
    }
}
