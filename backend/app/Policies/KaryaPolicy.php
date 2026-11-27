<?php

namespace App\Policies;

class KaryaPolicy extends AdminResourcePolicy
{
    protected function viewRoles(): array
    {
        return [
            'super-admin',
            'ketua-pelaksana',
            'program-kurasi',
            'sekretariat',
            'publikasi',
            'kampus-sekolah',
            'registrasi-layanan',
            'venue-teknis',
        ];
    }

    protected function manageRoles(): array
    {
        return [
            'super-admin',
            'ketua-pelaksana',
            'program-kurasi',
        ];
    }
}
