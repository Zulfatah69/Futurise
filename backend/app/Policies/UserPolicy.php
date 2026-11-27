<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserPolicy extends AdminResourcePolicy
{
    protected function viewRoles(): array
    {
        return [
            'super-admin',
            'ketua-pelaksana',
            'sekretariat',
            'kampus-sekolah',
            'registrasi-layanan',
            'keuangan',
        ];
    }

    protected function createRoles(): array
    {
        return [
            'super-admin',
            'sekretariat',
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

    protected function deleteRoles(): array
    {
        return ['super-admin'];
    }

    public function update(User $user, Model $record): bool
    {
        if (! parent::update($user, $record)) {
            return false;
        }

        // Hanya super-admin yang boleh mengubah akun super-admin lain.
        if ($record instanceof User && $record->hasRole('super-admin') && ! $user->hasRole('super-admin')) {
            return false;
        }

        return true;
    }

    public function delete(User $user, Model $record): bool
    {
        return parent::delete($user, $record) && $user->id !== $record->getKey();
    }
}
