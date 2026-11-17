import { useState } from 'react';
import { Link } from '@inertiajs/react';
import PublicLayout from '@/layouts/public-layout';
import {
    Calendar,
    MapPin,
    ArrowRight,
    Play,
    Download,
    Newspaper,
    Video as VideoIcon,
    FileImage,
    CheckCircle2,
    Ticket,
    Building2,
    Users,
    Layers,
    Presentation,
    Handshake,
    GraduationCap,
    Award,
    Network,
    Cpu,
    ExternalLink,
    Clock,
    X,
    Filter,
} from 'lucide-react';
import {
    FUTURISE_CONFIG,
    EVENT_PROGRAMS,
    STUDENT_PROJECTS,
    NEWS_ARTICLES,
    VIDEOS,
    FLYERS,
    WEST_JAVA_CAMPUSES,
} from '@/data/mock-data';
import { StudentProject, VideoItem, ProjectCategory } from '@/types/futurise';

export default function Welcome() {
    const [selectedCategory, setSelectedCategory] = useState<ProjectCategory>('Semua Bidang');
    const [activeProjectModal, setActiveProjectModal] = useState<StudentProject | null>(null);
    const [activeVideoModal, setActiveVideoModal] = useState<VideoItem | null>(null);
    const [downloadSuccess, setDownloadSuccess] = useState<string | null>(null);
    const [ticketRegistrationOpen, setTicketRegistrationOpen] = useState(false);

    // Filter projects
    const filteredProjects =
        selectedCategory === 'Semua Bidang'
            ? STUDENT_PROJECTS
            : STUDENT_PROJECTS.filter((p) => p.category === selectedCategory);

    const categories: ProjectCategory[] = [
        'Semua Bidang',
        'Kecerdasan Buatan (AI) & Data',
        'IoT & Smart City',
        'HealthTech & Rekam Medis',
        'AgriTech & Ketahanan Pangan',
        'FinTech, Logistik & E-Gov',
        'EduTech & Aksesibilitas',
    ];

    const handleSimulatedDownload = (flyerTitle: string) => {
        setDownloadSuccess(flyerTitle);
        setTimeout(() => setDownloadSuccess(null), 3500);
    };

    return (
        <PublicLayout
            title="FUTURiSE — West Java Digital Innovation Festival"
            description="Temukan karya inovasi mahasiswa, coba solusi teknologi, dan bangun peluang kolaborasi bersama kampus, industri, serta mitra di Jawa Barat. Diselenggarakan oleh APTIKOM Jawa Barat."
            activeNav="home"
        >
            {/* Toast Notification Download */}
            {downloadSuccess && (
                <div className="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-950 text-white border border-blue-800 shadow-xl animate-in slide-in-from-bottom-5 duration-200">
                    <CheckCircle2 className="w-5 h-5 text-blue-300" />
                    <div>
                        <div className="text-xs font-bold">Dokumen Siap Diunduh</div>
                        <div className="text-[11px] text-blue-200 line-clamp-1">
                            {downloadSuccess}
                        </div>
                    </div>
                </div>
            )}

            {/* HERO SECTION - Biru Tua & Putih, Natural, Authentic Event Identity */}
            <section className="bg-white dark:bg-[#070e22] border-b border-slate-200 dark:border-blue-950/80 py-12 lg:py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                        {/* Kolom Kiri: Informasi Acara */}
                        <div className="lg:col-span-7 space-y-6 text-left">
                            <div className="inline-flex items-center gap-2 px-3 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-950 dark:bg-blue-950 dark:text-blue-200 border border-blue-200 dark:border-blue-800">
                                <span className="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                                <span>APTIKOM WILAYAH IV JAWA BARAT</span>
                            </div>

                            <div className="space-y-3">
                                <h1 className="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-blue-950 dark:text-white tracking-tight leading-tight">
                                    FUTUR<span className="text-blue-600 dark:text-blue-400">i</span>SE
                                    <span className="block text-xl sm:text-2xl lg:text-3xl font-bold text-slate-700 dark:text-slate-200 mt-1">
                                        West Java Digital Innovation Festival
                                    </span>
                                </h1>
                                <p className="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed max-w-2xl">
                                    Temukan karya mahasiswa, coba solusi teknologi, dan bangun peluang kolaborasi bersama kampus, industri, serta mitra di Jawa Barat. Diselenggarakan oleh <strong>APTIKOM Jawa Barat</strong>.
                                </p>
                            </div>

                            {/* Info Box: Waktu & Venue Sabuga ITB */}
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 rounded-xl bg-slate-50 dark:bg-[#0a1636] border border-slate-200 dark:border-blue-900/60 text-xs">
                                <div className="flex items-start gap-2.5">
                                    <Calendar className="w-4 h-4 text-blue-700 dark:text-blue-400 shrink-0 mt-0.5" />
                                    <div>
                                        <span className="font-semibold text-slate-900 dark:text-white block">
                                            {FUTURISE_CONFIG.dates}
                                        </span>
                                        <span className="text-slate-500 dark:text-blue-300 text-[11px]">
                                            Pukul {FUTURISE_CONFIG.time}
                                        </span>
                                    </div>
                                </div>
                                <div className="flex items-start gap-2.5">
                                    <MapPin className="w-4 h-4 text-blue-700 dark:text-blue-400 shrink-0 mt-0.5" />
                                    <div>
                                        <span className="font-semibold text-slate-900 dark:text-white block">
                                            Sasana Budaya Ganesha (Sabuga)
                                        </span>
                                        <span className="text-slate-500 dark:text-blue-300 text-[11px]">
                                            Institut Teknologi Bandung (ITB)
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {/* Tombol Aksi Utama Sesuai Spesifikasi Dokumen */}
                            <div className="flex flex-wrap items-center gap-3 pt-1">
                                <button
                                    onClick={() => setTicketRegistrationOpen(true)}
                                    className="px-5 py-3 rounded-xl text-xs sm:text-sm font-bold bg-blue-950 text-white dark:bg-white dark:text-blue-950 hover:bg-blue-900 dark:hover:bg-slate-100 transition-all shadow-sm flex items-center gap-2"
                                >
                                    <Ticket className="w-4 h-4" />
                                    <span>Daftar Pengunjung (Gratis)</span>
                                </button>
                                <Link
                                    href="/flyer"
                                    className="px-4 py-3 rounded-xl text-xs sm:text-sm font-semibold text-blue-950 dark:text-white bg-slate-100 dark:bg-blue-900/60 hover:bg-slate-200 dark:hover:bg-blue-800 border border-slate-300 dark:border-blue-700 transition-colors flex items-center gap-2"
                                >
                                    <FileImage className="w-4 h-4 text-blue-600 dark:text-blue-300" />
                                    <span>Brosur & Panduan Kampus</span>
                                </Link>
                                <a
                                    href="#showcase"
                                    className="px-3 py-3 text-xs sm:text-sm font-medium text-slate-600 dark:text-blue-300 hover:text-blue-950 dark:hover:text-white underline underline-offset-4"
                                >
                                    Eksplorasi 60 Karya ↓
                                </a>
                            </div>
                        </div>

                        {/* Kolom Kanan: Highlight Banner Festival */}
                        <div className="lg:col-span-5">
                            <div className="relative rounded-2xl overflow-hidden bg-blue-950 text-white border border-blue-900 shadow-xl p-6 space-y-5">
                                <div className="flex items-center justify-between border-b border-blue-900/80 pb-4">
                                    <div className="space-y-0.5">
                                        <span className="text-[10px] font-bold tracking-widest text-blue-300 uppercase">
                                            Status Festival
                                        </span>
                                        <h3 className="text-base font-extrabold text-white">
                                            Tahap Kurasi & Registrasi
                                        </h3>
                                    </div>
                                    <span className="px-2.5 py-1 rounded text-[11px] font-bold bg-blue-900 text-blue-200 border border-blue-800">
                                        Sabuga ITB
                                    </span>
                                </div>

                                {/* Cakupan Parameter Awal Sesuai Halaman 1 Dokumen Acara */}
                                <div className="grid grid-cols-2 gap-3 text-left">
                                    <div className="p-3 rounded-xl bg-blue-900/50 border border-blue-800/80">
                                        <span className="text-[11px] text-blue-300 block">Kampus Partisipan</span>
                                        <span className="text-xl font-black text-white">30 Kampus</span>
                                        <span className="text-[10px] text-blue-200 block mt-0.5">Se-Jawa Barat</span>
                                    </div>
                                    <div className="p-3 rounded-xl bg-blue-900/50 border border-blue-800/80">
                                        <span className="text-[11px] text-blue-300 block">Karya Mahasiswa</span>
                                        <span className="text-xl font-black text-white">60 Prototipe</span>
                                        <span className="text-[10px] text-blue-200 block mt-0.5">2 Karya per Stan</span>
                                    </div>
                                    <div className="p-3 rounded-xl bg-blue-900/50 border border-blue-800/80">
                                        <span className="text-[11px] text-blue-300 block">Total Booth Expo</span>
                                        <span className="text-xl font-black text-white">45 Booth</span>
                                        <span className="text-[10px] text-blue-200 block mt-0.5">30 Kampus, 10 Industri, 5 Mitra</span>
                                    </div>
                                    <div className="p-3 rounded-xl bg-blue-900/50 border border-blue-800/80">
                                        <span className="text-[11px] text-blue-300 block">Finalis Pitching</span>
                                        <span className="text-xl font-black text-white">10 Tim</span>
                                        <span className="text-[10px] text-blue-200 block mt-0.5">Target 30 Matchmaking</span>
                                    </div>
                                </div>

                                <div className="pt-2 border-t border-blue-900/80 flex items-center justify-between text-xs text-blue-200">
                                    <span className="flex items-center gap-1.5">
                                        <Users className="w-3.5 h-3.5 text-blue-300" />
                                        <span>Target 1.000 Pengunjung Unik</span>
                                    </span>
                                    <Link
                                        href="/berita"
                                        className="text-white hover:text-blue-300 font-semibold flex items-center gap-1"
                                    >
                                        Warta Acara
                                        <ArrowRight className="w-3.5 h-3.5" />
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* SEKSI 1: TENTANG FUTURiSE (Latar Belakang, Penyelenggara & 3 Pilar) */}
            <section className="py-12 bg-slate-50 dark:bg-[#060e22] border-b border-slate-200 dark:border-blue-950/80">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="max-w-3xl mx-auto text-center space-y-3 mb-10">
                        <span className="text-xs font-bold uppercase tracking-wider text-blue-700 dark:text-blue-300">
                            Latar Belakang & Visi Penyelenggara
                        </span>
                        <h2 className="text-2xl sm:text-3xl font-extrabold text-blue-950 dark:text-white tracking-tight">
                            Menjembatani Riset Perguruan Tinggi ke Ekosistem Nyata Jawa Barat
                        </h2>
                        <p className="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                            APTIKOM Wilayah IV Jawa Barat menginisiasi <strong>FUTURiSE</strong> sebagai wadah strategis akselerasi karya mahasiswa bidang informatika dan komputer. Acara ini memastikan setiap inovasi tidak hanya berakhir sebagai laporan tugas akhir, melainkan diuji langsung oleh pengguna dan disambut oleh kebutuhan industri.
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div className="p-6 rounded-2xl bg-white dark:bg-[#0a1636] border border-slate-200 dark:border-blue-900/60 space-y-3">
                            <div className="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/60 text-blue-950 dark:text-white flex items-center justify-center font-bold">
                                01
                            </div>
                            <h3 className="text-base font-bold text-slate-900 dark:text-white">
                                Standar Kesiapan Teruji
                            </h3>
                            <p className="text-xs text-slate-600 dark:text-blue-200/80 leading-relaxed">
                                Seluruh 60 karya yang tampil telah lolos verifikasi kurator independen berdasarkan 5 indikator: relevansi masalah, fungsi prototipe, kebaruan, potensi dampak, dan komunikasi demo.
                            </p>
                        </div>

                        <div className="p-6 rounded-2xl bg-white dark:bg-[#0a1636] border border-slate-200 dark:border-blue-900/60 space-y-3">
                            <div className="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/60 text-blue-950 dark:text-white flex items-center justify-center font-bold">
                                02
                            </div>
                            <h3 className="text-base font-bold text-slate-900 dark:text-white">
                                Matchmaking Industri Terjadwal
                            </h3>
                            <p className="text-xs text-slate-600 dark:text-blue-200/80 leading-relaxed">
                                Memfasilitasi pertemuan empat mata terjadwal antara 10 industri mitra dengan tim pengembang kampus di Meja Kolaborasi Sabuga untuk tindak lanjut lisensi, magang, dan adopsi produk hingga H+30.
                            </p>
                        </div>

                        <div className="p-6 rounded-2xl bg-white dark:bg-[#0a1636] border border-slate-200 dark:border-blue-900/60 space-y-3">
                            <div className="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/60 text-blue-950 dark:text-white flex items-center justify-center font-bold">
                                03
                            </div>
                            <h3 className="text-base font-bold text-slate-900 dark:text-white">
                                Edu-Tour Talenta Muda
                            </h3>
                            <p className="text-xs text-slate-600 dark:text-blue-200/80 leading-relaxed">
                                Membuka akses bagi 15 rombongan sekolah menengah kejuruan (SMK) dan SMA se-Jawa Barat untuk mencoba langsung simulator perangkat cerdas dan mengenal program studi informatika.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {/* SEKSI 2: 7 PROGRAM & AGENDA UTAMA (Sesuai Spesifikasi Dokumen Acara) */}
            <section className="py-12 bg-white dark:bg-[#070e22] border-b border-slate-200 dark:border-blue-950/80">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
                        <div>
                            <span className="text-xs font-bold uppercase tracking-wider text-blue-700 dark:text-blue-300">
                                Struktur Agenda Acara
                            </span>
                            <h2 className="text-2xl sm:text-3xl font-extrabold text-blue-950 dark:text-white tracking-tight mt-1">
                                7 Program Unggulan FUTURiSE 2026
                            </h2>
                            <p className="text-xs sm:text-sm text-slate-500 dark:text-blue-200/80 mt-1 max-w-xl">
                                Rangkaian agenda festival selama 3 hari di Sasana Budaya Ganesha (Sabuga ITB), Bandung.
                            </p>
                        </div>
                        <Link
                            href="/flyer"
                            className="inline-flex items-center gap-1.5 text-xs font-bold text-blue-900 dark:text-blue-300 hover:underline"
                        >
                            Unduh Rundown Acara Lengkap (PDF)
                            <ArrowRight className="w-3.5 h-3.5" />
                        </Link>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        {EVENT_PROGRAMS.map((prog) => (
                            <div
                                key={prog.id}
                                className="p-5 rounded-2xl bg-slate-50 dark:bg-[#091533] border border-slate-200 dark:border-blue-900/60 hover:border-blue-700 transition-all space-y-3 flex flex-col justify-between"
                            >
                                <div className="space-y-2.5">
                                    <div className="flex items-center justify-between">
                                        <span className="w-7 h-7 rounded-lg bg-blue-950 text-white dark:bg-white dark:text-blue-950 font-bold text-xs flex items-center justify-center">
                                            {prog.id}
                                        </span>
                                        <span className="text-[11px] font-semibold px-2 py-0.5 rounded bg-blue-100 text-blue-900 dark:bg-blue-950 dark:text-blue-200 border border-blue-200 dark:border-blue-800">
                                            {prog.location}
                                        </span>
                                    </div>
                                    <h3 className="text-sm font-bold text-slate-900 dark:text-white leading-snug">
                                        {prog.title}
                                    </h3>
                                    <p className="text-[11px] text-blue-700 dark:text-blue-300 font-medium">
                                        {prog.subtitle}
                                    </p>
                                    <p className="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                        {prog.description}
                                    </p>
                                </div>

                                <div className="pt-3 border-t border-slate-200 dark:border-blue-900/60 flex items-center justify-between text-[11px] text-slate-500 dark:text-blue-300">
                                    <span className="flex items-center gap-1">
                                        <Clock className="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" />
                                        <span>{prog.time}</span>
                                    </span>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* SEKSI 3: SHOWCASE 60 KARYA MAHASISWA (Fitur Utama Pameran) */}
            <section id="showcase" className="py-12 bg-slate-50 dark:bg-[#060e22] border-b border-slate-200 dark:border-blue-950/80">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="space-y-2 mb-6">
                        <div className="flex items-center gap-2">
                            <span className="text-xs font-bold uppercase tracking-wider text-blue-700 dark:text-blue-300">
                                Katalog Karya Pameran
                            </span>
                            <span className="text-[10px] font-bold px-2 py-0.5 rounded bg-blue-900 text-white">
                                60 Prototipe Terkurasi
                            </span>
                        </div>
                        <h2 className="text-2xl sm:text-3xl font-extrabold text-blue-950 dark:text-white tracking-tight">
                            Eksplorasi Karya Inovasi Mahasiswa Informatika Jawa Barat
                        </h2>
                        <p className="text-xs sm:text-sm text-slate-500 dark:text-blue-200/80 max-w-2xl">
                            Dua karya inovasi terbaik dari masing-masing kampus berbagi 1 stan booth (ukuran 3x2 m) di Exhibition Hall Sabuga ITB.
                        </p>
                    </div>

                    {/* Filter Kategori Bidang */}
                    <div className="flex items-center gap-1.5 overflow-x-auto pb-3 scrollbar-none mb-6">
                        <Filter className="w-4 h-4 text-slate-400 shrink-0 mr-1" />
                        {categories.map((cat) => (
                            <button
                                key={cat}
                                onClick={() => setSelectedCategory(cat)}
                                className={`px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition-colors shrink-0 ${
                                    selectedCategory === cat
                                        ? 'bg-blue-950 text-white dark:bg-white dark:text-blue-950 font-bold shadow-sm'
                                        : 'bg-white text-slate-700 dark:bg-blue-950 dark:text-blue-200 hover:bg-slate-100 dark:hover:bg-blue-900 border border-slate-200 dark:border-blue-900'
                                }`}
                            >
                                {cat}
                            </button>
                        ))}
                    </div>

                    {/* Grid Kartu Karya Mahasiswa */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {filteredProjects.map((project) => (
                            <div
                                key={project.id}
                                className="group rounded-2xl bg-white dark:bg-[#091533] border border-slate-200 dark:border-blue-900/60 overflow-hidden shadow-sm hover:shadow-md hover:border-blue-700 transition-all flex flex-col justify-between"
                            >
                                {/* Thumbnail Image */}
                                <div className="relative aspect-video w-full overflow-hidden bg-slate-100 dark:bg-blue-950">
                                    <img
                                        src={project.image}
                                        alt={project.title}
                                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    />
                                    <div className="absolute inset-0 bg-gradient-to-t from-blue-950/80 via-transparent to-transparent"></div>

                                    {/* Badges */}
                                    <div className="absolute top-3 left-3 flex flex-wrap gap-1.5">
                                        <span className="px-2 py-0.5 rounded text-[10px] font-bold bg-white text-blue-950 shadow-sm">
                                            {project.campusShort}
                                        </span>
                                        <span className="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-950/90 text-blue-200 border border-blue-800">
                                            {project.boothNumber}
                                        </span>
                                    </div>

                                    {project.isPitchFinalist && (
                                        <div className="absolute bottom-3 left-3 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500 text-blue-950 flex items-center gap-1 shadow-sm">
                                            <Award className="w-3 h-3" />
                                            <span>Finalis Pitching</span>
                                        </div>
                                    )}
                                </div>

                                {/* Body Card */}
                                <div className="p-5 space-y-3 flex-1 flex flex-col justify-between">
                                    <div className="space-y-2">
                                        <div className="text-[11px] font-semibold text-blue-700 dark:text-blue-300">
                                            {project.category}
                                        </div>
                                        <h3 className="text-sm font-bold text-slate-900 dark:text-white leading-snug line-clamp-2">
                                            {project.title}
                                        </h3>
                                        <p className="text-xs text-slate-600 dark:text-slate-300 line-clamp-2">
                                            {project.solution}
                                        </p>
                                    </div>

                                    <div className="space-y-2.5 pt-3 border-t border-slate-100 dark:border-blue-900/60">
                                        {/* Tech Badges */}
                                        <div className="flex flex-wrap gap-1">
                                            {project.technology.slice(0, 3).map((tech, idx) => (
                                                <span
                                                    key={idx}
                                                    className="px-1.5 py-0.5 rounded text-[10px] bg-slate-100 dark:bg-blue-950 text-slate-700 dark:text-blue-200"
                                                >
                                                    {tech}
                                                </span>
                                            ))}
                                            {project.technology.length > 3 && (
                                                <span className="px-1.5 py-0.5 rounded text-[10px] bg-slate-100 dark:bg-blue-950 text-slate-500 dark:text-blue-300">
                                                    +{project.technology.length - 3}
                                                </span>
                                            )}
                                        </div>

                                        <div className="flex items-center justify-between pt-1 text-[11px]">
                                            <span className="text-slate-500 dark:text-blue-300 text-[10px]">
                                                {project.readinessLevel.split(' (')[0]}
                                            </span>
                                            <button
                                                onClick={() => setActiveProjectModal(project)}
                                                className="font-bold text-blue-950 dark:text-white hover:text-blue-700 dark:hover:text-blue-300 flex items-center gap-1"
                                            >
                                                <span>Detail Karya</span>
                                                <ArrowRight className="w-3.5 h-3.5" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* SEKSI 4: BERITA & PENGUMUMAN RESMI FUTURiSE (Terkait Berita) */}
            <section className="py-12 bg-white dark:bg-[#070e22] border-b border-slate-200 dark:border-blue-950/80">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                        <div>
                            <span className="text-xs font-bold uppercase tracking-wider text-blue-700 dark:text-blue-300">
                                Warta Resmi Acara
                            </span>
                            <h2 className="text-2xl sm:text-3xl font-extrabold text-blue-950 dark:text-white tracking-tight mt-1">
                                Pengumuman & Berita Terkini FUTURiSE
                            </h2>
                            <p className="text-xs sm:text-sm text-slate-500 dark:text-blue-200/80 mt-1">
                                Informasi tahapan kurasi, panduan teknis stan booth, dan jadwal pitching.
                            </p>
                        </div>
                        <Link
                            href="/berita"
                            className="inline-flex items-center gap-1.5 text-xs font-bold text-blue-950 dark:text-white hover:text-blue-700 dark:hover:text-blue-300"
                        >
                            <span>Semua Berita</span>
                            <ArrowRight className="w-4 h-4" />
                        </Link>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {NEWS_ARTICLES.slice(0, 3).map((article) => (
                            <Link
                                key={article.id}
                                href="/berita"
                                className="group rounded-2xl bg-slate-50 dark:bg-[#091533] border border-slate-200 dark:border-blue-900/60 overflow-hidden hover:border-blue-700 transition-all flex flex-col"
                            >
                                <div className="aspect-video w-full overflow-hidden bg-slate-200 dark:bg-blue-950">
                                    <img
                                        src={article.image}
                                        alt={article.title}
                                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    />
                                </div>
                                <div className="p-5 space-y-2.5 flex-1 flex flex-col justify-between">
                                    <div className="space-y-2">
                                        <div className="flex items-center gap-2 text-[11px] text-slate-500 dark:text-blue-300">
                                            <span className="font-semibold text-blue-700 dark:text-blue-300">
                                                {article.category}
                                            </span>
                                            <span>•</span>
                                            <span>{article.publishedAt}</span>
                                        </div>
                                        <h3 className="text-sm font-bold text-slate-900 dark:text-white leading-snug group-hover:text-blue-600 dark:group-hover:text-blue-300 transition-colors">
                                            {article.title}
                                        </h3>
                                        <p className="text-xs text-slate-600 dark:text-slate-300 line-clamp-2">
                                            {article.excerpt}
                                        </p>
                                    </div>
                                    <div className="pt-2 text-[11px] font-bold text-blue-950 dark:text-white flex items-center gap-1">
                                        <span>Baca Selengkapnya</span>
                                        <ArrowRight className="w-3 h-3 text-blue-600 dark:text-blue-300" />
                                    </div>
                                </div>
                            </Link>
                        ))}
                    </div>
                </div>
            </section>

            {/* SEKSI 5: VIDEO DOKUMENTASI & TEASER (Seksi Video) */}
            <section className="py-12 bg-slate-50 dark:bg-[#060e22] border-b border-slate-200 dark:border-blue-950/80">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                        <div>
                            <span className="text-xs font-bold uppercase tracking-wider text-blue-700 dark:text-blue-300">
                                Dokumentasi & Audiovisual
                            </span>
                            <h2 className="text-2xl sm:text-3xl font-extrabold text-blue-950 dark:text-white tracking-tight mt-1">
                                Video Liputan & Teaser Acara
                            </h2>
                            <p className="text-xs sm:text-sm text-slate-500 dark:text-blue-200/80 mt-1">
                                Rekaman pengantar festival, sambutan ketua pelaksana, dan preview prototipe.
                            </p>
                        </div>
                        <Link
                            href="/video"
                            className="inline-flex items-center gap-1.5 text-xs font-bold text-blue-950 dark:text-white hover:text-blue-700 dark:hover:text-blue-300"
                        >
                            <span>Galeri Video Lengkap</span>
                            <ArrowRight className="w-4 h-4" />
                        </Link>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {VIDEOS.slice(0, 3).map((video) => (
                            <div
                                key={video.id}
                                className="group rounded-2xl bg-white dark:bg-[#091533] border border-slate-200 dark:border-blue-900/60 overflow-hidden hover:border-blue-700 transition-all flex flex-col justify-between"
                            >
                                <div
                                    onClick={() => setActiveVideoModal(video)}
                                    className="relative aspect-video w-full overflow-hidden bg-slate-900 cursor-pointer"
                                >
                                    <img
                                        src={video.thumbnail}
                                        alt={video.title}
                                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 opacity-90"
                                    />
                                    <div className="absolute inset-0 bg-blue-950/30 flex items-center justify-center">
                                        <div className="w-11 h-11 rounded-full bg-white text-blue-950 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                            <Play className="w-5 h-5 fill-current ml-0.5 text-blue-950" />
                                        </div>
                                    </div>
                                    <div className="absolute bottom-2.5 right-2.5 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-950/90 text-white">
                                        {video.duration}
                                    </div>
                                </div>
                                <div className="p-4 space-y-2">
                                    <span className="text-[10px] font-bold uppercase tracking-wider text-blue-700 dark:text-blue-300">
                                        {video.category}
                                    </span>
                                    <h3
                                        onClick={() => setActiveVideoModal(video)}
                                        className="text-xs font-bold text-slate-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-300 cursor-pointer line-clamp-2"
                                    >
                                        {video.title}
                                    </h3>
                                    <p className="text-[11px] text-slate-500 dark:text-blue-200/80 line-clamp-2">
                                        {video.description}
                                    </p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* SEKSI 6: FLYER & DOKUMEN UNDUHAN RESMI (Seksi Flyer) */}
            <section className="py-12 bg-white dark:bg-[#070e22] border-b border-slate-200 dark:border-blue-950/80">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                        <div>
                            <span className="text-xs font-bold uppercase tracking-wider text-blue-700 dark:text-blue-300">
                                Publikasi & Lembar Kerja
                            </span>
                            <h2 className="text-2xl sm:text-3xl font-extrabold text-blue-950 dark:text-white tracking-tight mt-1">
                                Brosur, Flyer & Panduan Resmi Acara
                            </h2>
                            <p className="text-xs sm:text-sm text-slate-500 dark:text-blue-200/80 mt-1">
                                Unduh materi poster resmi, petunjuk pendaftaran PIC kampus, dan proposal sponsor.
                            </p>
                        </div>
                        <Link
                            href="/flyer"
                            className="inline-flex items-center gap-1.5 text-xs font-bold text-blue-950 dark:text-white hover:text-blue-700 dark:hover:text-blue-300"
                        >
                            <span>Semua Flyer & Booklet</span>
                            <ArrowRight className="w-4 h-4" />
                        </Link>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {FLYERS.slice(0, 3).map((flyer) => (
                            <div
                                key={flyer.id}
                                className="rounded-2xl bg-slate-50 dark:bg-[#091533] border border-slate-200 dark:border-blue-900/60 p-5 space-y-4 flex flex-col justify-between"
                            >
                                <div className="space-y-3">
                                    <div className="flex items-center justify-between">
                                        <span className="text-[10px] font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-900 dark:bg-blue-950 dark:text-blue-200 border border-blue-200 dark:border-blue-800">
                                            {flyer.category}
                                        </span>
                                        <span className="text-[10px] text-slate-500 dark:text-blue-300">
                                            {flyer.fileSize}
                                        </span>
                                    </div>
                                    <h3 className="text-sm font-bold text-slate-900 dark:text-white leading-snug">
                                        {flyer.title}
                                    </h3>
                                    <p className="text-xs text-slate-600 dark:text-slate-300 line-clamp-2">
                                        {flyer.description}
                                    </p>
                                </div>

                                <div className="pt-3 border-t border-slate-200 dark:border-blue-900/60 flex items-center justify-between">
                                    <span className="text-[11px] text-slate-500 dark:text-blue-300 font-medium">
                                        {flyer.fileFormat.split(' ')[0]}
                                    </span>
                                    <button
                                        onClick={() => handleSimulatedDownload(flyer.title)}
                                        className="px-3 py-1.5 rounded-lg text-xs font-bold bg-blue-950 text-white dark:bg-white dark:text-blue-950 hover:bg-blue-900 dark:hover:bg-slate-100 transition-colors flex items-center gap-1.5"
                                    >
                                        <Download className="w-3.5 h-3.5" />
                                        <span>Unduh PDF</span>
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* SEKSI 7: VENUE SABUGA & PANDUAN PENGUNJUNG */}
            <section className="py-12 bg-slate-50 dark:bg-[#060e22]">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="p-8 rounded-3xl bg-blue-950 text-white border border-blue-900 shadow-xl space-y-6">
                        <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                            <div className="lg:col-span-8 space-y-3">
                                <span className="text-xs font-bold uppercase tracking-wider text-blue-300">
                                    Venue & Fasilitas Sabuga ITB
                                </span>
                                <h2 className="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                                    Akses Mudah di Jantung Kota Bandung
                                </h2>
                                <p className="text-xs sm:text-sm text-blue-200/90 leading-relaxed max-w-2xl">
                                    Gedung Sasana Budaya Ganesha (Sabuga ITB) terletak di Jl. Tamansari No. 73, Bandung. Dilengkapi area pameran terpadu 45 booth, auditorium berkapasitas 800 kursi untuk pitching day, ruang matchmaking berpendingin, serta area parkir luas khusus bus rombongan sekolah.
                                </p>

                                <div className="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 text-xs">
                                    <div className="p-3 rounded-xl bg-blue-900/60 border border-blue-800">
                                        <p className="font-bold text-white">Kereta Cepat Whoosh</p>
                                        <p className="text-[11px] text-blue-300 mt-0.5">Stasiun Padalarang dilanjutkan Feeder ke Stasiun Bandung</p>
                                    </div>
                                    <div className="p-3 rounded-xl bg-blue-900/60 border border-blue-800">
                                        <p className="font-bold text-white">Akses Tol Pasteur</p>
                                        <p className="text-[11px] text-blue-300 mt-0.5">15 menit via Jl. Dr. Djunjunan & Flyover Pasupati</p>
                                    </div>
                                    <div className="p-3 rounded-xl bg-blue-900/60 border border-blue-800">
                                        <p className="font-bold text-white">Registrasi QR Check-in</p>
                                        <p className="text-[11px] text-blue-300 mt-0.5">Pindai token tiket di loket lobby utama</p>
                                    </div>
                                </div>
                            </div>

                            <div className="lg:col-span-4 flex flex-col items-center justify-center p-6 rounded-2xl bg-blue-900/40 border border-blue-800 space-y-3 text-center">
                                <Building2 className="w-10 h-10 text-white" />
                                <h3 className="text-sm font-bold text-white">Ingin Berkunjung ke FUTURiSE?</h3>
                                <p className="text-xs text-blue-200">
                                    Dapatkan tiket pengunjung gratis Anda sekarang dan nikmati pameran 60 inovasi karya mahasiswa.
                                </p>
                                <button
                                    onClick={() => setTicketRegistrationOpen(true)}
                                    className="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-white text-blue-950 hover:bg-slate-100 transition-colors shadow-sm"
                                >
                                    Daftar Tiket Pengunjung
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* MODAL DETAIL KARYA MAHASISWA (Sesuai Section 3 Detail Karya) */}
            {activeProjectModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-blue-950/80 backdrop-blur-sm animate-in fade-in duration-200">
                    <div
                        className="w-full max-w-2xl bg-white dark:bg-[#0a1636] rounded-2xl shadow-2xl border border-blue-900 overflow-hidden flex flex-col max-h-[90vh]"
                        onClick={(e) => e.stopPropagation()}
                    >
                        <div className="px-6 py-4 bg-blue-950 text-white flex items-center justify-between border-b border-blue-900">
                            <div>
                                <span className="text-[10px] font-bold uppercase tracking-wider text-blue-300">
                                    {activeProjectModal.campus} • {activeProjectModal.boothNumber}
                                </span>
                                <h3 className="text-sm font-bold">{activeProjectModal.title}</h3>
                            </div>
                            <button
                                onClick={() => setActiveProjectModal(null)}
                                className="p-1 rounded-lg text-blue-300 hover:text-white hover:bg-blue-900"
                            >
                                <X className="w-5 h-5" />
                            </button>
                        </div>

                        <div className="p-6 overflow-y-auto space-y-5 text-xs text-slate-700 dark:text-slate-300">
                            <div className="aspect-video w-full rounded-xl overflow-hidden bg-slate-100">
                                <img
                                    src={activeProjectModal.image}
                                    alt={activeProjectModal.title}
                                    className="w-full h-full object-cover"
                                />
                            </div>

                            <div className="grid grid-cols-2 gap-3 p-3.5 rounded-xl bg-slate-50 dark:bg-blue-950/70 border border-slate-200 dark:border-blue-900">
                                <div>
                                    <span className="text-[10px] text-slate-500 dark:text-blue-300 block">Ketua Tim Pengembang</span>
                                    <span className="font-bold text-slate-900 dark:text-white">{activeProjectModal.teamLead}</span>
                                </div>
                                <div>
                                    <span className="text-[10px] text-slate-500 dark:text-blue-300 block">Dosen Pembimbing</span>
                                    <span className="font-bold text-slate-900 dark:text-white">{activeProjectModal.advisor}</span>
                                </div>
                            </div>

                            <div className="space-y-1.5">
                                <h4 className="font-bold text-slate-900 dark:text-white uppercase text-[11px] tracking-wider text-blue-900 dark:text-blue-300">
                                    Latar Belakang Masalah
                                </h4>
                                <p className="leading-relaxed bg-slate-50 dark:bg-blue-950/40 p-3 rounded-lg border border-slate-100 dark:border-blue-900/60">
                                    {activeProjectModal.problem}
                                </p>
                            </div>

                            <div className="space-y-1.5">
                                <h4 className="font-bold text-slate-900 dark:text-white uppercase text-[11px] tracking-wider text-blue-900 dark:text-blue-300">
                                    Solusi & Prototipe yang Dikembangkan
                                </h4>
                                <p className="leading-relaxed bg-slate-50 dark:bg-blue-950/40 p-3 rounded-lg border border-slate-100 dark:border-blue-900/60">
                                    {activeProjectModal.solution}
                                </p>
                            </div>

                            <div className="space-y-2">
                                <h4 className="font-bold text-slate-900 dark:text-white uppercase text-[11px] tracking-wider text-blue-900 dark:text-blue-300">
                                    Teknologi yang Digunakan
                                </h4>
                                <div className="flex flex-wrap gap-1.5">
                                    {activeProjectModal.technology.map((tech, idx) => (
                                        <span
                                            key={idx}
                                            className="px-2 py-1 rounded bg-blue-100 dark:bg-blue-900 text-blue-950 dark:text-blue-100 font-medium"
                                        >
                                            {tech}
                                        </span>
                                    ))}
                                </div>
                            </div>
                        </div>

                        <div className="px-6 py-3 bg-slate-50 dark:bg-blue-950/90 border-t border-slate-200 dark:border-blue-900 flex items-center justify-between">
                            <span className="text-[11px] text-slate-500 dark:text-blue-300">
                                Tingkat Kesiapan: <strong className="text-slate-800 dark:text-white">{activeProjectModal.readinessLevel}</strong>
                            </span>
                            <button
                                onClick={() => setActiveProjectModal(null)}
                                className="px-4 py-2 rounded-lg text-xs font-semibold bg-blue-950 text-white"
                            >
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            )}

            {/* MODAL VIDEO PLAYER INTERAKTIF */}
            {activeVideoModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-blue-950/80 backdrop-blur-sm animate-in fade-in duration-200">
                    <div
                        className="w-full max-w-2xl bg-white dark:bg-[#0a1636] rounded-2xl shadow-2xl border border-blue-900 overflow-hidden"
                        onClick={(e) => e.stopPropagation()}
                    >
                        <div className="px-5 py-3.5 bg-blue-950 text-white flex items-center justify-between border-b border-blue-900">
                            <h3 className="text-xs font-bold line-clamp-1">{activeVideoModal.title}</h3>
                            <button
                                onClick={() => setActiveVideoModal(null)}
                                className="p-1 rounded text-blue-300 hover:text-white"
                            >
                                <X className="w-4 h-4" />
                            </button>
                        </div>
                        <div className="aspect-video w-full bg-black relative flex items-center justify-center">
                            <img
                                src={activeVideoModal.thumbnail}
                                alt={activeVideoModal.title}
                                className="w-full h-full object-cover opacity-80"
                            />
                            <div className="absolute inset-0 flex flex-col items-center justify-center bg-blue-950/60 p-6 text-center space-y-3">
                                <div className="w-14 h-14 rounded-full bg-white text-blue-950 flex items-center justify-center shadow-2xl animate-pulse">
                                    <Play className="w-6 h-6 fill-current ml-1 text-blue-950" />
                                </div>
                                <p className="text-xs font-semibold text-white">
                                    Pemutar Video Dokumentasi FUTURiSE 2026
                                </p>
                            </div>
                        </div>
                        <div className="p-5 space-y-2 text-xs">
                            <div className="flex items-center gap-2 text-[11px] text-blue-700 dark:text-blue-300 font-semibold">
                                <span>{activeVideoModal.category}</span>
                                <span>•</span>
                                <span>{activeVideoModal.speaker} ({activeVideoModal.institution})</span>
                            </div>
                            <p className="text-slate-600 dark:text-slate-300 leading-relaxed">
                                {activeVideoModal.description}
                            </p>
                        </div>
                    </div>
                </div>
            )}
        </PublicLayout>
    );
}
