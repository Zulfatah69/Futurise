<?php

namespace App\Policies;

class PublikasiKaryaPolicy extends AdminResourcePolicy
{
    protected function viewRoles(): array
    {
        return [
            'super-admin',
            'ketua-pelaksana',
            'publikasi',
            'program-kurasi',
            'industri-sponsor',
        ];
    }

    protected function manageRoles(): array
    {
        return [
            'super-admin',
            'ketua-pelaksana',
            'publikasi',
        ];
    }

    protected function deleteRoles(): array
    {
        return [
            'super-admin',
            'ketua-pelaksana',
            'publikasi',
        ];
    }
}
