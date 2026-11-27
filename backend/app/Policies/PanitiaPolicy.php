<?php

namespace App\Policies;

class PanitiaPolicy extends AdminResourcePolicy
{
    protected function viewRoles(): array
    {
        return [
            'super-admin',
            'ketua-pelaksana',
            'sekretariat',
            'registrasi-layanan',
            'venue-teknis',
        ];
    }

    protected function manageRoles(): array
    {
        return [
            'super-admin',
            'ketua-pelaksana',
            'sekretariat',
        ];
    }

    protected function deleteRoles(): array
    {
        return [
            'super-admin',
            'ketua-pelaksana',
            'sekretariat',
        ];
    }
}
