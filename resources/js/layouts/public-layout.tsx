import { useState, useEffect, ReactNode } from 'react';
import { Head, Link } from '@inertiajs/react';
import { Navbar } from '@/components/navbar';
import { Footer } from '@/components/footer';
import {
    Search,
    X,
    Calendar,
    Newspaper,
    Video,
    FileImage,
    ArrowRight,
    MapPin,
    Tag,
    Ticket,
    CheckCircle2,
    Building2,
    Sparkles,
} from 'lucide-react';
import {
    FUTURISE_CONFIG,
    STUDENT_PROJECTS,
    NEWS_ARTICLES,
    VIDEOS,
    FLYERS,
} from '@/data/mock-data';

interface PublicLayoutProps {
    title?: string;
    description?: string;
    activeNav?: 'home' | 'berita' | 'video' | 'flyer';
    children: ReactNode;
}

export default function PublicLayout({
    title = 'FUTURiSE — West Java Digital Innovation Festival',
    description = 'Temukan karya inovasi mahasiswa, eksplorasi prototipe teknologi, dan bangun kolaborasi strategis bersama kampus, industri, serta mitra di Jawa Barat. Diselenggarakan oleh APTIKOM Jawa Barat.',
    activeNav = 'home',
    children,
}: PublicLayoutProps) {
    const [searchOpen, setSearchOpen] = useState(false);
    const [searchQuery, setSearchQuery] = useState('');
    const [ticketModalOpen, setTicketModalOpen] = useState(false);

    // Form ticket state
    const [ticketForm, setTicketForm] = useState({
        name: '',
        email: '',
        institution: '',
        category: 'Mahasiswa / Akademisi',
    });
    const [ticketGenerated, setTicketGenerated] = useState<null | {
        qrCode: string;
        ticketId: string;
        registeredAt: string;
    }>(null);

    // Global keyboard shortcut: '/' or 'Ctrl+K' / 'Cmd+K' to toggle search
    useEffect(() => {
        const handleKeyDown = (e: KeyboardEvent) => {
            if (
                e.key === '/' &&
                !['INPUT', 'TEXTAREA'].includes((e.target as HTMLElement).tagName)
            ) {
                e.preventDefault();
                setSearchOpen(true);
            }
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                setSearchOpen((prev) => !prev);
            }
            if (e.key === 'Escape') {
                setSearchOpen(false);
                setTicketModalOpen(false);
            }
        };

        window.addEventListener('keydown', handleKeyDown);
        return () => window.removeEventListener('keydown', handleKeyDown);
    }, [searchOpen, ticketModalOpen]);

    const q = searchQuery.toLowerCase().trim();

    const filteredProjects = q
        ? STUDENT_PROJECTS.filter(
              (item) =>
                  item.title.toLowerCase().includes(q) ||
                  item.campus.toLowerCase().includes(q) ||
                  item.campusShort.toLowerCase().includes(q) ||
                  item.category.toLowerCase().includes(q) ||
                  item.problem.toLowerCase().includes(q),
          )
        : [];

    const filteredNews = q
        ? NEWS_ARTICLES.filter(
              (item) =>
                  item.title.toLowerCase().includes(q) ||
                  item.category.toLowerCase().includes(q) ||
                  item.tags.some((t) => t.toLowerCase().includes(q)),
          )
        : [];

    const filteredVideos = q
        ? VIDEOS.filter(
              (item) =>
                  item.title.toLowerCase().includes(q) ||
                  item.category.toLowerCase().includes(q) ||
                  item.description.toLowerCase().includes(q),
          )
        : [];

    const filteredFlyers = q
        ? FLYERS.filter(
              (item) =>
                  item.title.toLowerCase().includes(q) ||
                  item.category.toLowerCase().includes(q) ||
                  item.targetAudience.toLowerCase().includes(q),
          )
        : [];

    const totalResults =
        filteredProjects.length +
        filteredNews.length +
        filteredVideos.length +
        filteredFlyers.length;

    const handleGenerateTicket = (e: React.FormEvent) => {
        e.preventDefault();
        if (!ticketForm.name || !ticketForm.email) return;

        // Token acak tanpa data pribadi langsung sesuai spesifikasi BR-11
        const randomToken =
            'FTR26-' + Math.random().toString(36).substring(2, 8).toUpperCase();
        setTicketGenerated({
            qrCode: randomToken,
            ticketId: randomToken,
            registeredAt: new Date().toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
            }),
        });
    };

    return (
        <div id="top" className="min-h-screen flex flex-col bg-slate-50 dark:bg-[#060e22] text-slate-800 dark:text-slate-100 transition-colors">
            <Head>
                <title>{title}</title>
                <meta name="description" content={description} />
                <meta property="og:title" content={title} />
                <meta property="og:description" content={description} />
                <meta property="og:type" content="website" />
            </Head>

            {/* Top Navigation */}
            <Navbar
                activeNav={activeNav}
                onOpenSearch={() => setSearchOpen(true)}
                onOpenTicketModal={() => setTicketModalOpen(true)}
            />

            {/* Main Content Area */}
            <main className="flex-1 w-full">{children}</main>

            {/* Global Footer */}
            <Footer />

            {/* Universal Search Modal */}
            {searchOpen && (
                <div className="fixed inset-0 z-50 flex items-start justify-center pt-16 sm:pt-24 px-4 bg-blue-950/70 backdrop-blur-sm animate-in fade-in duration-150">
                    <div
                        className="w-full max-w-2xl bg-white dark:bg-[#091533] rounded-2xl shadow-2xl border border-blue-900/40 overflow-hidden flex flex-col max-h-[80vh]"
                        onClick={(e) => e.stopPropagation()}
                    >
                        {/* Search Input Bar */}
                        <div className="relative flex items-center px-4 py-3 border-b border-slate-200 dark:border-blue-900/60 bg-slate-50/50 dark:bg-[#0c1a40]">
                            <Search className="w-5 h-5 text-blue-600 dark:text-blue-400 shrink-0 ml-1" />
                            <input
                                type="text"
                                autoFocus
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                placeholder="Cari karya inovasi kampus, berita acara, video, atau rundown flyer..."
                                className="w-full px-3 py-1.5 text-sm bg-transparent border-0 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-0"
                            />
                            {searchQuery && (
                                <button
                                    onClick={() => setSearchQuery('')}
                                    className="p-1 rounded hover:bg-slate-200 dark:hover:bg-blue-900 text-slate-400 hover:text-slate-600 dark:hover:text-white"
                                >
                                    <X className="w-4 h-4" />
                                </button>
                            )}
                            <button
                                onClick={() => setSearchOpen(false)}
                                className="ml-2 px-2 py-1 text-xs font-medium rounded bg-slate-200 dark:bg-blue-900 text-slate-700 dark:text-blue-200 hover:bg-slate-300 dark:hover:bg-blue-800"
                            >
                                Esc
                            </button>
                        </div>

                        {/* Search Content */}
                        <div className="overflow-y-auto p-4 space-y-4 flex-1">
                            {!searchQuery ? (
                                <div className="py-8 text-center space-y-2 text-slate-500 dark:text-blue-300">
                                    <Search className="w-8 h-8 mx-auto stroke-1 text-slate-400 dark:text-blue-400" />
                                    <p className="text-xs">
                                        Ketik kata kunci untuk mencari:
                                    </p>
                                    <div className="flex flex-wrap justify-center gap-1.5 pt-2">
                                        {['IPB', 'ITB', 'LoRa', 'Suhu Vaksin', 'Pitching', 'Rundown', 'Kurasi', 'Booth'].map(
                                            (tag) => (
                                                <button
                                                    key={tag}
                                                    onClick={() => setSearchQuery(tag)}
                                                    className="px-2 py-1 text-[11px] rounded bg-slate-100 dark:bg-blue-950 text-blue-900 dark:text-blue-200 hover:bg-blue-50 dark:hover:bg-blue-900 border border-slate-200 dark:border-blue-900"
                                                >
                                                    {tag}
                                                </button>
                                            ),
                                        )}
                                    </div>
                                </div>
                            ) : totalResults === 0 ? (
                                <div className="py-12 text-center space-y-2">
                                    <p className="text-sm font-medium text-slate-700 dark:text-slate-200">
                                        Tidak ditemukan hasil untuk "{searchQuery}"
                                    </p>
                                    <p className="text-xs text-slate-500 dark:text-blue-300">
                                        Coba gunakan kata kunci kampus (ITB, IPB, Tel-U) atau topik (AI, IoT, Pitching, Rundown).
                                    </p>
                                </div>
                            ) : (
                                <div className="space-y-4">
                                    {/* Hasil Karya Mahasiswa */}
                                    {filteredProjects.length > 0 && (
                                        <div>
                                            <div className="flex items-center gap-2 pb-2 text-xs font-bold uppercase tracking-wider text-blue-900 dark:text-blue-300 border-b border-slate-100 dark:border-blue-900/60">
                                                <Building2 className="w-3.5 h-3.5" />
                                                <span>Karya Inovasi Mahasiswa ({filteredProjects.length})</span>
                                            </div>
                                            <div className="mt-2 space-y-2">
                                                {filteredProjects.map((p) => (
                                                    <Link
                                                        key={p.id}
                                                        href={`/#showcase`}
                                                        onClick={() => setSearchOpen(false)}
                                                        className="group block p-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-blue-950/80 border border-transparent hover:border-slate-200 dark:hover:border-blue-800 transition-colors"
                                                    >
                                                        <div className="flex items-start justify-between gap-2">
                                                            <div>
                                                                <div className="flex items-center gap-2">
                                                                    <span className="text-[10px] font-semibold px-1.5 py-0.5 rounded bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-200">
                                                                        {p.campusShort}
                                                                    </span>
                                                                    <span className="text-[10px] text-slate-500 dark:text-blue-300">
                                                                        {p.boothNumber}
                                                                    </span>
                                                                </div>
                                                                <h4 className="text-xs font-semibold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-300 transition-colors mt-1">
                                                                    {p.title}
                                                                </h4>
                                                                <p className="text-[11px] text-slate-600 dark:text-blue-200/80 line-clamp-1 mt-0.5">
                                                                    {p.solution}
                                                                </p>
                                                            </div>
                                                            <ArrowRight className="w-3.5 h-3.5 text-slate-400 group-hover:text-blue-600 shrink-0 mt-1" />
                                                        </div>
                                                    </Link>
                                                ))}
                                            </div>
                                        </div>
                                    )}

                                    {/* Hasil Berita */}
                                    {filteredNews.length > 0 && (
                                        <div>
                                            <div className="flex items-center gap-2 pb-2 text-xs font-bold uppercase tracking-wider text-blue-900 dark:text-blue-300 border-b border-slate-100 dark:border-blue-900/60">
                                                <Newspaper className="w-3.5 h-3.5" />
                                                <span>Berita & Pengumuman ({filteredNews.length})</span>
                                            </div>
                                            <div className="mt-2 space-y-2">
                                                {filteredNews.map((n) => (
                                                    <Link
                                                        key={n.id}
                                                        href={`/berita`}
                                                        onClick={() => setSearchOpen(false)}
                                                        className="group block p-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-blue-950/80 border border-transparent hover:border-slate-200 dark:hover:border-blue-800 transition-colors"
                                                    >
                                                        <h4 className="text-xs font-semibold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-300 transition-colors">
                                                            {n.title}
                                                        </h4>
                                                        <p className="text-[11px] text-slate-600 dark:text-blue-200/80 line-clamp-1 mt-0.5">
                                                            {n.excerpt}
                                                        </p>
                                                    </Link>
                                                ))}
                                            </div>
                                        </div>
                                    )}

                                    {/* Hasil Video & Flyer */}
                                    {(filteredVideos.length > 0 || filteredFlyers.length > 0) && (
                                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                                            {filteredVideos.length > 0 && (
                                                <Link
                                                    href="/video"
                                                    onClick={() => setSearchOpen(false)}
                                                    className="p-3 rounded-xl bg-slate-50 dark:bg-blue-950 border border-slate-200 dark:border-blue-900 hover:border-blue-500 transition-colors"
                                                >
                                                    <div className="flex items-center gap-2 text-xs font-semibold text-blue-900 dark:text-white">
                                                        <Video className="w-4 h-4 text-blue-500" />
                                                        <span>Lihat {filteredVideos.length} Video Terkait</span>
                                                    </div>
                                                </Link>
                                            )}
                                            {filteredFlyers.length > 0 && (
                                                <Link
                                                    href="/flyer"
                                                    onClick={() => setSearchOpen(false)}
                                                    className="p-3 rounded-xl bg-slate-50 dark:bg-blue-950 border border-slate-200 dark:border-blue-900 hover:border-blue-500 transition-colors"
                                                >
                                                    <div className="flex items-center gap-2 text-xs font-semibold text-blue-900 dark:text-white">
                                                        <FileImage className="w-4 h-4 text-blue-500" />
                                                        <span>Lihat {filteredFlyers.length} Brosur / Rundown</span>
                                                    </div>
                                                </Link>
                                            )}
                                        </div>
                                    )}
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            )}

            {/* Modal Tiket E-Pass Pengunjung (BR-11: Token Acak Tanpa Data Pribadi Langsung) */}
            {ticketModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-blue-950/80 backdrop-blur-sm animate-in fade-in duration-200">
                    <div
                        className="w-full max-w-md bg-white dark:bg-[#0a1636] rounded-2xl shadow-2xl border border-blue-900/60 overflow-hidden"
                        onClick={(e) => e.stopPropagation()}
                    >
                        {/* Header Modal */}
                        <div className="px-5 py-4 bg-blue-950 text-white flex items-center justify-between border-b border-blue-900">
                            <div className="flex items-center gap-2">
                                <Ticket className="w-5 h-5 text-blue-300" />
                                <div>
                                    <h3 className="text-sm font-bold">E-Pass Pengunjung FUTURiSE</h3>
                                    <p className="text-[11px] text-blue-200">Tiket Akses Masuk Sabuga ITB (Gratis)</p>
                                </div>
                            </div>
                            <button
                                onClick={() => {
                                    setTicketModalOpen(false);
                                    setTicketGenerated(null);
                                }}
                                className="p-1 rounded-lg text-blue-300 hover:text-white hover:bg-blue-900"
                            >
                                <X className="w-4 h-4" />
                            </button>
                        </div>

                        {/* Modal Body */}
                        <div className="p-5">
                            {!ticketGenerated ? (
                                <form onSubmit={handleGenerateTicket} className="space-y-3.5">
                                    <div className="p-3 rounded-xl bg-blue-50 dark:bg-blue-950/70 border border-blue-200 dark:border-blue-900 text-xs text-blue-950 dark:text-blue-200">
                                        Pameran terbuka untuk umum, akademisi, profesional industri, dan siswa. Masuk tidak dipungut biaya.
                                    </div>

                                    <div>
                                        <label className="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                            Nama Lengkap
                                        </label>
                                        <input
                                            type="text"
                                            required
                                            value={ticketForm.name}
                                            onChange={(e) =>
                                                setTicketForm({ ...ticketForm, name: e.target.value })
                                            }
                                            placeholder="Contoh: Amanda Prameswari"
                                            className="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-blue-800 bg-white dark:bg-[#07112c] text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-500"
                                        />
                                    </div>

                                    <div>
                                        <label className="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                            Alamat Email Aktif
                                        </label>
                                        <input
                                            type="email"
                                            required
                                            value={ticketForm.email}
                                            onChange={(e) =>
                                                setTicketForm({ ...ticketForm, email: e.target.value })
                                            }
                                            placeholder="nama@email.com"
                                            className="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-blue-800 bg-white dark:bg-[#07112c] text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-500"
                                        />
                                    </div>

                                    <div className="grid grid-cols-2 gap-2">
                                        <div>
                                            <label className="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                                Kategori
                                            </label>
                                            <select
                                                value={ticketForm.category}
                                                onChange={(e) =>
                                                    setTicketForm({
                                                        ...ticketForm,
                                                        category: e.target.value,
                                                    })
                                                }
                                                className="w-full px-2.5 py-2 text-xs rounded-lg border border-slate-300 dark:border-blue-800 bg-white dark:bg-[#07112c] text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-500"
                                            >
                                                <option>Mahasiswa / Dosen</option>
                                                <option>Praktisi Industri</option>
                                                <option>Siswa / Guru SMA/SMK</option>
                                                <option>Masyarakat Umum</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label className="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                                Institusi / Kampus / Sekolah
                                            </label>
                                            <input
                                                type="text"
                                                value={ticketForm.institution}
                                                onChange={(e) =>
                                                    setTicketForm({
                                                        ...ticketForm,
                                                        institution: e.target.value,
                                                    })
                                                }
                                                placeholder="Contoh: ITB / SMK Telkom"
                                                className="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-blue-800 bg-white dark:bg-[#07112c] text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-500"
                                            />
                                        </div>
                                    </div>

                                    <div className="pt-2">
                                        <button
                                            type="submit"
                                            className="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-white bg-blue-900 hover:bg-blue-800 shadow-sm transition-all"
                                        >
                                            Terbitkan E-Pass Saya
                                        </button>
                                    </div>
                                </form>
                            ) : (
                                <div className="space-y-4 text-center">
                                    <div className="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400">
                                        <CheckCircle2 className="w-6 h-6" />
                                    </div>
                                    <div>
                                        <h4 className="text-sm font-bold text-slate-900 dark:text-white">
                                            E-Pass Berhasil Diterbitkan
                                        </h4>
                                        <p className="text-xs text-slate-500 dark:text-blue-200 mt-0.5">
                                            Tunjukkan kode QR ini ke meja registrasi di Lobby Sabuga ITB.
                                        </p>
                                    </div>

                                    {/* Ticket Card Simulation */}
                                    <div className="p-4 rounded-xl bg-blue-950 text-white border border-blue-900 text-left space-y-3">
                                        <div className="flex items-center justify-between border-b border-blue-900 pb-2">
                                            <div>
                                                <span className="text-[10px] uppercase font-bold text-blue-300">
                                                    Official Visitor Pass
                                                </span>
                                                <p className="text-xs font-bold">{ticketForm.name}</p>
                                            </div>
                                            <span className="text-xs font-mono font-bold bg-blue-900 px-2 py-0.5 rounded text-blue-200">
                                                {ticketGenerated.ticketId}
                                            </span>
                                        </div>

                                        <div className="grid grid-cols-2 gap-2 text-[11px] text-blue-200">
                                            <div>
                                                <p className="text-[10px] text-blue-300">Kategori</p>
                                                <p className="font-semibold text-white">{ticketForm.category}</p>
                                            </div>
                                            <div>
                                                <p className="text-[10px] text-blue-300">Venue</p>
                                                <p className="font-semibold text-white">Sabuga ITB Bandung</p>
                                            </div>
                                        </div>

                                        {/* Simulated QR Code Graphic */}
                                        <div className="pt-2 flex flex-col items-center justify-center p-3 rounded-lg bg-white text-blue-950">
                                            <div className="font-mono text-center text-xs tracking-widest font-black py-2">
                                                [ QR TOKEN: {ticketGenerated.qrCode} ]
                                            </div>
                                            <p className="text-[9px] text-slate-500">
                                                Token acak terenkripsi untuk check-in & hak suara People's Choice
                                            </p>
                                        </div>
                                    </div>

                                    <div className="flex items-center gap-2 pt-1">
                                        <button
                                            type="button"
                                            onClick={() => window.print()}
                                            className="flex-1 py-2 px-3 rounded-lg text-xs font-semibold bg-white dark:bg-blue-900 border border-slate-300 dark:border-blue-700 text-slate-800 dark:text-white"
                                        >
                                            Cetak Tiket
                                        </button>
                                        <button
                                            type="button"
                                            onClick={() => {
                                                setTicketModalOpen(false);
                                                setTicketGenerated(null);
                                            }}
                                            className="flex-1 py-2 px-3 rounded-lg text-xs font-semibold bg-blue-950 text-white"
                                        >
                                            Selesai
                                        </button>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
