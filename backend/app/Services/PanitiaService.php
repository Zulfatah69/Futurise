<?php

namespace App\Services;

use App\Models\DivisiPanitia;
use App\Models\Festival;
use App\Models\Panitia;
use App\Models\PanitiaFestival;
use App\Models\PanitiaTugas;
use App\Models\PeranAkses;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PanitiaService
{
    /**
     * Dapatkan daftar panitia dengan filter & paginasi.
     */
    public function listPanitia(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Panitia::query()->with(['user']);

        if (!empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', $search)
                  ->orWhere('email', 'like', $search)
                  ->orWhere('no_telepon', 'like', $search)
                  ->orWhere('institusi', 'like', $search);
            });
        }

        if (!empty($filters['jenis'])) {
            $query->where('jenis', $filters['jenis']);
        }

        if (isset($filters['aktif'])) {
            $query->where('aktif', filter_var($filters['aktif'], FILTER_VALIDATE_BOOLEAN));
        }

        return $query->latest('id')->paginate($perPage);
    }

    /**
     * Daftarkan data panitia baru.
     */
    public function createPanitia(array $data): Panitia
    {
        if (!empty($data['password'])) {
            $data['password_hash'] = Hash::make($data['password']);
            unset($data['password']);
        }

        return Panitia::create($data);
    }

    /**
     * Perbarui data panitia.
     */
    public function updatePanitia(Panitia $panitia, array $data): Panitia
    {
        if (!empty($data['password'])) {
            $data['password_hash'] = Hash::make($data['password']);
            unset($data['password']);
        }

        $panitia->update($data);
        return $panitia->fresh();
    }

    /**
     * Hapus data panitia.
     */
    public function deletePanitia(Panitia $panitia): bool
    {
        return DB::transaction(function () use ($panitia) {
            return $panitia->delete();
        });
    }

    /**
     * Dapatkan daftar divisi untuk festival tertentu.
     */
    public function listDivisions(Festival $festival): Collection
    {
        return $festival->divisions()
            ->withCount('assignments')
            ->orderBy('urutan')
            ->get();
    }

    /**
     * Tambah divisi baru pada festival.
     */
    public function createDivision(Festival $festival, array $data): DivisiPanitia
    {
        $data['festival_id'] = $festival->id;
        return DivisiPanitia::create($data);
    }

    /**
     * Perbarui divisi.
     */
    public function updateDivision(DivisiPanitia $division, array $data): DivisiPanitia
    {
        $division->update($data);
        return $division->fresh();
    }

    /**
     * Hapus divisi panitia.
     */
    public function deleteDivision(DivisiPanitia $division): bool
    {
        return $division->delete();
    }

    /**
     * Tugaskan panitia ke edisi festival tertentu.
     */
    public function assignToFestival(Festival $festival, Panitia $panitia, array $data): PanitiaFestival
    {
        return DB::transaction(function () use ($festival, $panitia, $data) {
            // Cek apakah panitia sudah terdaftar di festival ini
            $exists = PanitiaFestival::where('festival_id', $festival->id)
                ->where('panitia_id', $panitia->id)
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'panitia_id' => ['Panitia ini sudah terdaftar pada edisi festival yang dipilih.'],
                ]);
            }

            // Validasi kuota divisi jika divisi dipilih
            if (!empty($data['divisi_id'])) {
                $division = DivisiPanitia::where('festival_id', $festival->id)
                    ->findOrFail($data['divisi_id']);

                if ($division->kuota_anggota !== null) {
                    $currentCount = PanitiaFestival::where('divisi_id', $division->id)
                        ->whereIn('status', ['aktif', 'diterima'])
                        ->count();

                    if ($currentCount >= $division->kuota_anggota) {
                        throw ValidationException::withMessages([
                            'divisi_id' => ["Kuota anggota divisi '{$division->nama}' telah penuh ({$division->kuota_anggota} orang)."],
                        ]);
                    }
                }
            }

            return PanitiaFestival::create([
                'festival_id'       => $festival->id,
                'panitia_id'        => $panitia->id,
                'divisi_id'         => $data['divisi_id'] ?? null,
                'jabatan'           => $data['jabatan'] ?? 'anggota',
                'atasan_id'         => $data['atasan_id'] ?? null,
                'status'            => $data['status'] ?? 'diterima',
                'tanggal_bergabung' => $data['tanggal_bergabung'] ?? now()->toDateString(),
                'tanggal_selesai'   => $data['tanggal_selesai'] ?? null,
                'catatan'           => $data['catatan'] ?? null,
            ]);
        });
    }

    /**
     * Perbarui penugasan panitia festival.
     */
    public function updateAssignment(PanitiaFestival $assignment, array $data): PanitiaFestival
    {
        $assignment->update($data);
        return $assignment->fresh(['panitia', 'divisi', 'atasan']);
    }

    /**
     * Dapatkan daftar shift tugas panitia.
     */
    public function listTasks(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = PanitiaTugas::query()->with(['panitiaFestival.panitia', 'panitiaFestival.divisi']);

        if (!empty($filters['panitia_festival_id'])) {
            $query->where('panitia_festival_id', $filters['panitia_festival_id']);
        }

        if (!empty($filters['festival_id'])) {
            $query->whereHas('panitiaFestival', function ($q) use ($filters) {
                $q->where('festival_id', $filters['festival_id']);
            });
        }

        if (!empty($filters['tanggal'])) {
            $query->where('tanggal', $filters['tanggal']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('tanggal')->orderBy('jam_mulai')->paginate($perPage);
    }

    /**
     * Buat penugasan jadwal/shift baru untuk anggota panitia.
     */
    public function createTask(PanitiaFestival $assignment, array $data): PanitiaTugas
    {
        // Validasi waktu jam selesai harus setelah jam mulai
        if (isset($data['jam_mulai'], $data['jam_selesai'])) {
            $start = Carbon::createFromFormat('H:i', substr($data['jam_mulai'], 0, 5));
            $end = Carbon::createFromFormat('H:i', substr($data['jam_selesai'], 0, 5));

            if ($end->lessThanOrEqualTo($start)) {
                throw ValidationException::withMessages([
                    'jam_selesai' => ['Jam selesai tugas harus lebih akhir dari jam mulai.'],
                ]);
            }
        }

        $data['panitia_festival_id'] = $assignment->id;
        $data['status'] = $data['status'] ?? 'terjadwal';

        return PanitiaTugas::create($data);
    }

    /**
     * Presensi check-in penugasan panitia.
     */
    public function checkInTask(PanitiaTugas $task, ?string $catatan = null): PanitiaTugas
    {
        $update = [
            'check_in_pada' => Carbon::now(),
            'status'        => 'bertugas',
        ];

        if ($catatan) {
            $update['catatan'] = $task->catatan ? $task->catatan . ' | ' . $catatan : $catatan;
        }

        $task->update($update);
        return $task->fresh(['panitiaFestival.panitia']);
    }

    /**
     * Selesaikan shift tugas panitia.
     */
    public function completeTask(PanitiaTugas $task, ?string $catatan = null): PanitiaTugas
    {
        $update = [
            'status' => 'selesai',
        ];

        if ($catatan) {
            $update['catatan'] = $task->catatan ? $task->catatan . ' | ' . $catatan : $catatan;
        }

        $task->update($update);
        return $task->fresh(['panitiaFestival.panitia']);
    }

    /**
     * Daftar penugasan panitia pada edisi festival tertentu.
     */
    public function listFestivalAssignments(Festival $festival, array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = PanitiaFestival::query()
            ->where('festival_id', $festival->id)
            ->with(['panitia', 'divisi', 'atasan.panitia']);

        if (! empty($filters['divisi_id'])) {
            $query->where('divisi_id', $filters['divisi_id']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['jabatan'])) {
            $query->where('jabatan', $filters['jabatan']);
        }

        return $query->orderBy('jabatan')->orderBy('id')->paginate($perPage);
    }

    /**
     * Daftar seluruh peran akses kepanitiaan.
     */
    public function listAccessRoles(): Collection
    {
        return PeranAkses::orderBy('id')->get();
    }

    /**
     * Tambah definisi peran akses kepanitiaan baru.
     */
    public function createAccessRole(array $data): PeranAkses
    {
        return PeranAkses::create($data);
    }

    /**
     * Berikan peran akses ke panitia (idempotent pada pasangan panitia + peran).
     */
    public function assignAccessRole(
        Panitia $panitia,
        PeranAkses $role,
        ?int $festivalId = null,
        ?int $grantedByPanitiaId = null
    ): Panitia {
        return DB::transaction(function () use ($panitia, $role, $festivalId, $grantedByPanitiaId) {
            $pivotData = [
                'festival_id'    => $festivalId,
                'diberikan_oleh' => $grantedByPanitiaId,
            ];

            if ($panitia->peranAkses()->where('peran_id', $role->id)->exists()) {
                $panitia->peranAkses()->updateExistingPivot($role->id, $pivotData);
            } else {
                $panitia->peranAkses()->attach($role->id, $pivotData);
            }

            return $panitia->fresh(['peranAkses']);
        });
    }
}
