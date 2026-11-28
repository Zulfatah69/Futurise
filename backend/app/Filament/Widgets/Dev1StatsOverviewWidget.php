<?php

namespace App\Filament\Widgets;

use App\Models\Karya;
use App\Models\Peserta;
use App\Models\PublikasiKarya;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Dev1StatsOverviewWidget extends StatsOverviewWidget
{
    protected static ?bool $isLazyLoaded = false;

    protected ?string $heading = 'Ringkasan Operasional Dev 1';

    protected ?string $description = 'Metrik akun, peserta, kurasi karya, dan publikasi Showcase.';

    public static function canView(): bool
    {
        $user = auth()->user();

        return $user !== null
            && $user->account_status === 'active'
            && $user->hasAnyRole(User::ADMIN_ROLES);
    }

    protected function getStats(): array
    {
        $pendingPicCount = User::query()
            ->where('account_type', 'pic')
            ->where('account_status', 'pending_approval')
            ->count();

        $totalPeserta = Peserta::query()->count();
        $totalInstitusi = Peserta::query()
            ->whereNotNull('institusi')
            ->whereRaw("TRIM(institusi) != ''")
            ->selectRaw('COUNT(DISTINCT LOWER(TRIM(institusi))) as aggregate')
            ->value('aggregate') ?? 0;

        $totalKarya = Karya::query()->count();
        $karyaLolos = Karya::query()->where('status', 'lolos')->count();
        $karyaMenungguKurasi = Karya::query()->whereIn('status', ['diajukan', 'dalam_pemeriksaan'])->count();

        $showcaseTerbit = PublikasiKarya::published()->count();
        $showcaseMenunggu = PublikasiKarya::query()->where('status', 'menunggu_persetujuan')->count();

        return [
            Stat::make('Akun PIC Pending', (string) $pendingPicCount)
                ->description('Menunggu persetujuan aktivasi admin')
                ->color($pendingPicCount > 0 ? 'warning' : 'success'),

            Stat::make('Peserta Terdaftar', (string) $totalPeserta)
                ->description("{$totalInstitusi} kampus / institusi unik")
                ->color('info'),

            Stat::make('Total Karya Inovasi', (string) $totalKarya)
                ->description("{$karyaLolos} lolos kurasi · {$karyaMenungguKurasi} menunggu tinjau")
                ->color('primary'),

            Stat::make('Showcase Terbit', (string) $showcaseTerbit)
                ->description("{$showcaseMenunggu} pengajuan menunggu publikasi")
                ->color('success'),
        ];
    }
}
