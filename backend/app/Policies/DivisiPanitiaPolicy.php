<?php

namespace App\Policies;

class DivisiPanitiaPolicy extends AdminResourcePolicy
{
    protected function viewRoles(): array
    {
        return [
            'super-admin',
            'ketua-pelaksana',
            'sekretariat',
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
