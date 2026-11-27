<?php

namespace App\Policies;

class FestivalPolicy extends AdminResourcePolicy
{
    protected function viewRoles(): array
    {
        return [
            'super-admin',
            'ketua-pelaksana',
            'sekretariat',
            'program-kurasi',
            'publikasi',
            'venue-teknis',
            'industri-sponsor',
            'keuangan',
        ];
    }

    protected function createRoles(): array
    {
        return [
            'super-admin',
            'ketua-pelaksana',
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
