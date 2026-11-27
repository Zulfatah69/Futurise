<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

abstract class AdminResourcePolicy
{
    /**
     * Role yang diizinkan melihat menu & daftar record resource ini.
     *
     * @return array<int, string>
     */
    abstract protected function viewRoles(): array;

    /**
     * Role yang diizinkan membuat, mengubah, dan menjalankan aksi workflow resource ini.
     *
     * @return array<int, string>
     */
    abstract protected function manageRoles(): array;

    /**
     * @return array<int, string>
     */
    protected function createRoles(): array
    {
        return $this->manageRoles();
    }

    /**
     * @return array<int, string>
     */
    protected function deleteRoles(): array
    {
        return ['super-admin', 'ketua-pelaksana'];
    }

    protected function isActiveAdmin(User $user): bool
    {
        return $user->account_status === 'active'
            && $user->hasAnyRole(User::ADMIN_ROLES);
    }

    public function viewAny(User $user): bool
    {
        return $this->isActiveAdmin($user) && $user->hasAnyRole($this->viewRoles());
    }

    public function view(User $user, Model $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->isActiveAdmin($user) && $user->hasAnyRole($this->createRoles());
    }

    public function update(User $user, Model $record): bool
    {
        return $this->isActiveAdmin($user) && $user->hasAnyRole($this->manageRoles());
    }

    public function delete(User $user, Model $record): bool
    {
        return $this->isActiveAdmin($user) && $user->hasAnyRole($this->deleteRoles());
    }

    public function deleteAny(User $user): bool
    {
        return $this->isActiveAdmin($user) && $user->hasAnyRole($this->deleteRoles());
    }
}
