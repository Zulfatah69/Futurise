import { useState, useEffect } from 'react';
import { usePage, Link } from '@inertiajs/react';
import PublicLayout from '@/layouts/public-layout';
import {
    FileImage,
    Download,
    Eye,
    Calendar,
    MapPin,
    Search,
    Clock,
    Phone,
    Mail,
    CheckCircle2,
    Share2,
    X,
    FileText,
    Ticket,
    QrCode,
    Printer,
} from 'lucide-react';
import {
    FLYERS_DATA,
    CATEGORIES_LIST,
    WEST_JAVA_REGIONS,
} from '@/data/mock-data';
import { FlyerItem } from '@/types/futurise';

export default function FlyerPage() {
    const { url } = usePage();
    const [searchQuery, setSearchQuery] = useState('');
    const [selectedCategory, setSelectedCategory] = useState<string>('Semua');
    const [selectedRegion, setSelectedRegion] = useState<string>('Semua Wilayah');
    const [activeFlyerModal, setActiveFlyerModal] = useState<FlyerItem | null>(null);
    const [downloadingFlyer, setDownloadingFlyer] = useState<string | null>(null);
    const [downloadSuccessToast, setDownloadSuccessToast] = useState<string | null>(null);
    const [copiedShare, setCopiedShare] = useState(false);

    // Ticket registration modal state
    const [ticketModalFlyer, setTicketModalFlyer] = useState<FlyerItem | null>(null);
    const [ticketForm, setTicketForm] = useState({
        name: '',
        email: '',
        phone: '',
        institution: '',
        category: 'Umum / Publik',
    });
    const [ticketGenerated, setTicketGenerated] = useState(false);
    const [ticketCode, setTicketCode] = useState('');

    const handleTicketSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        const randomNum = Math.floor(1000 + Math.random() * 9000);
        setTicketCode(`JBR-${randomNum}`);
        setTicketGenerated(true);
    };

    const handleResetTicketModal = () => {
        setTicketModalFlyer(null);
        setTicketGenerated(false);
    };

    // Read URL query parameter ?q= if user navigated from global search
    useEffect(() => {
        const params = new URLSearchParams(window.location.search);
        const q = params.get('q');
        if (q) {
            setSearchQuery(q);
        }
    }, [url]);

    const filteredFlyers = FLYERS_DATA.filter((flyer) => {
        const matchesCategory =
            selectedCategory === 'Semua' || flyer.category === selectedCategory;
        const matchesRegion =
            selectedRegion === 'Semua Wilayah' || flyer.region === selectedRegion;
        const q = searchQuery.toLowerCase().trim();
        const matchesQuery =
            !q ||
            flyer.title.toLowerCase().includes(q) ||
            flyer.city.toLowerCase().includes(q) ||
            flyer.venue.toLowerCase().includes(q) ||
            flyer.description.toLowerCase().includes(q);

        return matchesCategory && matchesRegion && matchesQuery;
    });

    const handleDownload = (flyer: FlyerItem) => {
        setDownloadingFlyer(flyer.id);
        setTimeout(() => {
            setDownloadingFlyer(null);
            setDownloadSuccessToast(flyer.title);
            setTimeout(() => setDownloadSuccessToast(null), 3000);
        }, 800);
    };

    const handleCopyShare = () => {
        navigator.clipboard.writeText(window.location.href);
        setCopiedShare(true);
        setTimeout(() => setCopiedShare(false), 2500);
    };

    return (
        <PublicLayout
            title="Brosur & Flyer Pameran - Futurise Jawa Barat"
            description="Kumpulan pamflet dan brosur resmi acara pameran di Jawa Barat berformat PDF yang dapat diunduh gratis."
            activeNav="flyer"
        >
            {/* Download Success Toast */}
            {downloadSuccessToast && (
                <div className="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-950 text-white border border-blue-800 shadow-xl animate-in slide-in-from-bottom-5 duration-200">
                    <CheckCircle2 className="w-5 h-5 text-blue-300" />
                    <div>
                        <div className="text-xs font-bold text-white">Brosur Berhasil Diunduh</div>
                        <div className="text-[11px] text-blue-200 line-clamp-1">
                            {downloadSuccessToast}
                        </div>
                    </div>
                </div>
            )}

            {/* Header Hero Banner */}
            <div className="bg-white dark:bg-[#070e22] border-b border-slate-200 dark:border-blue-950 py-10 lg:py-14">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3.5">
                    <div className="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-900 dark:bg-blue-950 dark:text-blue-200 border border-blue-200 dark:border-blue-800">
                        <FileImage className="w-3.5 h-3.5 text-blue-900 dark:text-blue-300" />
                        Brosur & Materi Publikasi
                    </div>

                    <h1 className="text-3xl sm:text-4xl font-extrabold text-blue-950 dark:text-white tracking-tight">
                        Flyer & Brosur Pameran Jawa Barat
                    </h1>

                    <p className="text-sm sm:text-base text-slate-600 dark:text-slate-300 max-w-2xl leading-relaxed">
                        Unduh materi flyer dan brosur pameran dalam format PDF untuk melihat denah stan, jadwal rundown acara harian, dan kontak panitia.
                    </p>

                    {/* Filter & Search Bar */}
                    <div className="pt-3 flex flex-col md:flex-row items-center gap-3">
                        <div className="relative w-full md:w-80">
                            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                            <input
                                type="text"
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                placeholder="Cari nama pamflet atau gedung..."
                                className="w-full pl-9 pr-3 py-2 text-xs rounded-lg bg-white dark:bg-[#0c183a] border border-slate-300 dark:border-blue-900 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-800"
                            />
                        </div>

                        <div className="w-full md:w-auto">
                            <select
                                value={selectedRegion}
                                onChange={(e) => setSelectedRegion(e.target.value)}
                                className="w-full md:w-auto px-3 py-2 text-xs rounded-lg bg-white dark:bg-[#0c183a] border border-slate-300 dark:border-blue-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-800"
                            >
                                {WEST_JAVA_REGIONS.map((region) => (
                                    <option key={region} value={region}>
                                        {region}
                                    </option>
                                ))}
                            </select>
                        </div>

                        {(searchQuery ||
                            selectedCategory !== 'Semua' ||
                            selectedRegion !== 'Semua Wilayah') && (
                            <button
                                type="button"
                                onClick={() => {
                                    setSearchQuery('');
                                    setSelectedCategory('Semua');
                                    setSelectedRegion('Semua Wilayah');
                                }}
                                className="text-xs text-blue-900 dark:text-blue-300 hover:underline px-2 py-1 font-semibold"
                            >
                                Reset Filter
                            </button>
                        )}
                    </div>

                    {/* Category Buttons */}
                    <div className="flex items-center gap-2 overflow-x-auto pt-1 scrollbar-none">
                        {CATEGORIES_LIST.map((cat) => (
                            <button
                                key={cat}
                                type="button"
                                onClick={() => setSelectedCategory(cat)}
                                className={`px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition-colors ${
                                    selectedCategory === cat
                                        ? 'bg-blue-900 text-white dark:bg-blue-700'
                                        : 'bg-white dark:bg-[#0c183a] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-blue-950 border border-slate-200 dark:border-blue-900'
                                }`}
                            >
                                {cat}
                            </button>
                        ))}
                    </div>
                </div>
            </div>

            {/* Main Content Area */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
                <div>
                    <div className="flex items-center justify-between mb-5">
                        <h3 className="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-200">
                            Daftar Brosur & Flyer Acara ({filteredFlyers.length})
                        </h3>
                    </div>

                    {filteredFlyers.length === 0 ? (
                        <div className="text-center py-12 bg-white dark:bg-[#0c183a] rounded-2xl border border-slate-200 dark:border-blue-900 p-8 space-y-2">
                            <FileImage className="w-8 h-8 text-slate-400 mx-auto" />
                            <h4 className="text-sm font-bold text-blue-950 dark:text-white">
                                Tidak ada flyer yang cocok
                            </h4>
                            <p className="text-xs text-slate-500">
                                Coba pilih kota atau kategori lain untuk melihat brosur yang tersedia.
                            </p>
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {filteredFlyers.map((flyer) => (
                                <div
                                    key={flyer.id}
                                    className="flex flex-col rounded-2xl bg-white dark:bg-[#0c183a] border border-slate-200 dark:border-blue-900 overflow-hidden shadow-xs hover:border-blue-700 transition-colors"
                                >
                                    {/* Poster Header */}
                                    <div
                                        onClick={() => setActiveFlyerModal(flyer)}
                                        className="relative h-60 overflow-hidden bg-slate-950 cursor-pointer p-2 flex items-center justify-center"
                                    >
                                        <img
                                            src={flyer.image}
                                            alt={flyer.title}
                                            className="w-full h-full object-cover rounded-xl"
                                        />
                                        <div className="absolute top-4 left-4">
                                            <span className="px-2.5 py-0.5 rounded text-[11px] font-semibold bg-blue-900 text-white">
                                                {flyer.status}
                                            </span>
                                        </div>
                                        <div className="absolute bottom-4 right-4">
                                            <span className="px-2 py-0.5 rounded text-[10px] font-mono bg-black/80 text-white">
                                                {flyer.pdfSize}
                                            </span>
                                        </div>
                                    </div>

                                    {/* Card Content Body */}
                                    <div className="p-4 flex-1 flex flex-col justify-between space-y-3.5">
                                        <div className="space-y-1.5">
                                            <div className="text-[11px] text-blue-900 dark:text-blue-300 font-semibold flex items-center gap-1">
                                                <Calendar className="w-3 h-3" />
                                                <span>{flyer.dateRange}</span>
                                            </div>

                                            <h3
                                                onClick={() => setActiveFlyerModal(flyer)}
                                                className="text-sm font-bold text-blue-950 dark:text-white hover:text-blue-700 dark:hover:text-blue-300 cursor-pointer line-clamp-1"
                                            >
                                                {flyer.title}
                                            </h3>

                                            <div className="text-xs text-slate-500 flex items-center gap-1">
                                                <MapPin className="w-3.5 h-3.5 shrink-0" />
                                                <span className="truncate">{flyer.venue}</span>
                                            </div>

                                            <p className="text-xs text-slate-600 dark:text-slate-400 line-clamp-2 pt-0.5">
                                                {flyer.description}
                                            </p>
                                        </div>

                                        <div className="pt-2.5 border-t border-slate-100 dark:border-blue-950 flex items-center gap-2">
                                            <button
                                                type="button"
                                                onClick={() => handleDownload(flyer)}
                                                disabled={downloadingFlyer === flyer.id}
                                                className="flex-1 py-2 px-3 rounded-lg text-xs font-semibold text-white bg-blue-900 hover:bg-blue-800 dark:bg-blue-700 transition-colors flex items-center justify-center gap-1.5 disabled:opacity-75"
                                            >
                                                <Download className="w-3.5 h-3.5" />
                                                <span>
                                                    {downloadingFlyer === flyer.id
                                                        ? 'Mengunduh...'
                                                        : 'Unduh PDF'}
                                                </span>
                                            </button>

                                            <button
                                                type="button"
                                                onClick={() => setActiveFlyerModal(flyer)}
                                                className="p-2 rounded-lg text-slate-700 dark:text-slate-200 bg-slate-100 hover:bg-slate-200 dark:bg-blue-950 transition-colors"
                                                title="Lihat Rundown Acara"
                                            >
                                                <FileText className="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </div>

            {/* FLYER DETAIL & LIGHTBOX MODAL */}
            {activeFlyerModal && (
                <div
                    className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-xs animate-in fade-in duration-200"
                    onClick={() => setActiveFlyerModal(null)}
                >
                    <div
                        className="w-full max-w-3xl bg-white dark:bg-[#0c183a] rounded-2xl overflow-hidden shadow-xl border border-slate-200 dark:border-blue-900 flex flex-col max-h-[90vh]"
                        onClick={(e) => e.stopPropagation()}
                    >
                        {/* Header */}
                        <div className="p-4 border-b border-slate-200 dark:border-blue-900 flex items-center justify-between bg-slate-50 dark:bg-[#070e22]">
                            <div className="flex items-center gap-2">
                                <span className="px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 dark:bg-blue-950 text-blue-900 dark:text-blue-200">
                                    {activeFlyerModal.status}
                                </span>
                                <span className="text-xs text-slate-500">
                                    {activeFlyerModal.city} • {activeFlyerModal.dateRange}
                                </span>
                            </div>

                            <div className="flex items-center gap-2">
                                <button
                                    type="button"
                                    onClick={handleCopyShare}
                                    className="p-1.5 rounded-lg hover:bg-slate-200 dark:hover:bg-blue-950 text-slate-500 hover:text-slate-900 dark:hover:text-white"
                                    title="Salin Tautan"
                                >
                                    {copiedShare ? (
                                        <CheckCircle2 className="w-4 h-4 text-blue-700" />
                                    ) : (
                                        <Share2 className="w-4 h-4" />
                                    )}
                                </button>
                                <button
                                    type="button"
                                    onClick={() => setActiveFlyerModal(null)}
                                    className="p-1.5 rounded-lg hover:bg-slate-200 dark:hover:bg-blue-950 text-slate-400 hover:text-slate-800 dark:hover:text-white"
                                >
                                    <X className="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        {/* Content Scroll Area */}
                        <div className="p-5 sm:p-6 overflow-y-auto space-y-5 text-xs sm:text-sm">
                            <div className="grid grid-cols-1 md:grid-cols-12 gap-5 items-start">
                                {/* Flyer Preview */}
                                <div className="md:col-span-5 rounded-xl overflow-hidden bg-slate-950">
                                    <img
                                        src={activeFlyerModal.image}
                                        alt={activeFlyerModal.title}
                                        className="w-full h-auto object-cover"
                                    />
                                    <div className="p-2 bg-slate-950 text-center text-[11px] text-slate-400 font-mono">
                                        Ukuran File: {activeFlyerModal.pdfSize}
                                    </div>
                                </div>

                                {/* Event Info */}
                                <div className="md:col-span-7 space-y-4">
                                    <div className="space-y-1">
                                        <div className="text-xs text-slate-400">
                                            Penyelenggara: {activeFlyerModal.organizer}
                                        </div>
                                        <h2 className="text-lg sm:text-xl font-bold text-blue-950 dark:text-white leading-snug">
                                            {activeFlyerModal.title}
                                        </h2>
                                        <div className="text-xs text-slate-600 dark:text-slate-300 space-y-1 pt-1">
                                            <div className="flex items-center gap-1.5 font-semibold text-blue-950 dark:text-white">
                                                <Calendar className="w-3.5 h-3.5 text-blue-800 dark:text-blue-300" />
                                                <span>{activeFlyerModal.dateRange}</span>
                                            </div>
                                            <div className="flex items-center gap-1.5">
                                                <MapPin className="w-3.5 h-3.5 text-slate-400" />
                                                <span>{activeFlyerModal.venue}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <p className="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                        {activeFlyerModal.description}
                                    </p>

                                    {/* Rundown Timetable */}
                                    <div className="space-y-1.5">
                                        <div className="text-xs font-bold text-blue-950 dark:text-white flex items-center gap-1">
                                            <Clock className="w-3.5 h-3.5 text-blue-800 dark:text-blue-300" />
                                            <span>Rundown Jadwal Kegiatan:</span>
                                        </div>
                                        <div className="space-y-1.5">
                                            {activeFlyerModal.rundown.map((item, idx) => (
                                                <div
                                                    key={idx}
                                                    className="p-2 rounded-lg bg-slate-100 dark:bg-[#070e22] flex items-start gap-2.5 text-xs"
                                                >
                                                    <span className="font-mono font-bold text-blue-900 dark:text-blue-300 shrink-0">
                                                        {item.time}
                                                    </span>
                                                    <span className="text-slate-700 dark:text-slate-200">
                                                        {item.activity}
                                                    </span>
                                                </div>
                                            ))}
                                        </div>
                                    </div>

                                    {/* Contact Secretary */}
                                    <div className="pt-2 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-xs text-slate-500 border-t border-slate-100 dark:border-blue-950">
                                        <div className="flex items-center gap-1">
                                            <Phone className="w-3.5 h-3.5 text-blue-800 dark:text-blue-300" />
                                            <span>{activeFlyerModal.contactPhone}</span>
                                        </div>
                                        <div className="flex items-center gap-1">
                                            <Mail className="w-3.5 h-3.5 text-blue-800 dark:text-blue-300" />
                                            <span>{activeFlyerModal.contactEmail}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Modal Action Footer */}
                        <div className="p-3.5 border-t border-slate-200 dark:border-blue-900 flex items-center justify-between bg-slate-50 dark:bg-[#070e22]">
                            <span className="text-xs text-slate-500 hidden sm:inline">
                                Flyer resmi dapat dicetak untuk keperluan informasi.
                            </span>

                            <div className="flex items-center gap-2 w-full sm:w-auto">
                                <button
                                    type="button"
                                    onClick={() => setActiveFlyerModal(null)}
                                    className="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-700 dark:text-slate-300 bg-slate-200 dark:bg-blue-950 hover:bg-slate-300 dark:hover:bg-blue-900"
                                >
                                    Tutup
                                </button>
                                <button
                                    type="button"
                                    onClick={() => {
                                        const target = activeFlyerModal;
                                        setActiveFlyerModal(null);
                                        setTicketModalFlyer(target);
                                    }}
                                    className="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-blue-950 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:text-white flex items-center gap-1"
                                >
                                    <Ticket className="w-3.5 h-3.5" />
                                    <span>Daftar Masuk</span>
                                </button>
                                <button
                                    type="button"
                                    onClick={() => handleDownload(activeFlyerModal)}
                                    className="px-4 py-1.5 rounded-lg text-xs font-semibold text-white bg-blue-900 hover:bg-blue-800 dark:bg-blue-700 flex items-center gap-1.5"
                                >
                                    <Download className="w-3.5 h-3.5" />
                                    <span>Unduh PDF</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}

            {/* TICKET REGISTRATION IN FLYER PAGE */}
            {ticketModalFlyer && (
                <div
                    className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-xs animate-in fade-in duration-200"
                    onClick={handleResetTicketModal}
                >
                    <div
                        className="w-full max-w-md bg-white dark:bg-[#0c183a] rounded-2xl overflow-hidden shadow-xl border border-slate-200 dark:border-blue-900 flex flex-col max-h-[90vh]"
                        onClick={(e) => e.stopPropagation()}
                    >
                        {/* Header */}
                        <div className="p-4 border-b border-slate-200 dark:border-blue-900 flex items-center justify-between bg-slate-50 dark:bg-[#070e22]">
                            <div className="flex items-center gap-2">
                                <Ticket className="w-4 h-4 text-blue-900 dark:text-blue-300" />
                                <span className="text-xs font-bold text-blue-950 dark:text-white">
                                    Pendaftaran Pengunjung Pameran
                                </span>
                            </div>
                            <button
                                type="button"
                                onClick={handleResetTicketModal}
                                className="p-1 rounded-lg hover:bg-slate-200 dark:hover:bg-blue-950 text-slate-400 hover:text-slate-700 dark:hover:text-white"
                            >
                                <X className="w-4 h-4" />
                            </button>
                        </div>

                        {/* Content Area */}
                        <div className="p-5 overflow-y-auto space-y-4">
                            {!ticketGenerated ? (
                                <form onSubmit={handleTicketSubmit} className="space-y-3.5">
                                    <div className="p-3 rounded-xl bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-xs space-y-0.5">
                                        <div className="font-bold text-blue-950 dark:text-white">
                                            {ticketModalFlyer.title}
                                        </div>
                                        <div className="text-slate-500 dark:text-blue-200/80">
                                            {ticketModalFlyer.venue} • {ticketModalFlyer.dateRange}
                                        </div>
                                    </div>

                                    <div className="space-y-1">
                                        <label className="text-xs font-medium text-slate-700 dark:text-slate-300">
                                            Nama Lengkap
                                        </label>
                                        <input
                                            type="text"
                                            required
                                            value={ticketForm.name}
                                            onChange={(e) =>
                                                setTicketForm({ ...ticketForm, name: e.target.value })
                                            }
                                            placeholder="Nama pengunjung"
                                            className="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-blue-800 bg-white dark:bg-[#070e22] text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-800"
                                        />
                                    </div>

                                    <div className="grid grid-cols-2 gap-2">
                                        <div className="space-y-1">
                                            <label className="text-xs font-medium text-slate-700 dark:text-slate-300">
                                                Email
                                            </label>
                                            <input
                                                type="email"
                                                required
                                                value={ticketForm.email}
                                                onChange={(e) =>
                                                    setTicketForm({ ...ticketForm, email: e.target.value })
                                                }
                                                placeholder="nama@email.com"
                                                className="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-blue-800 bg-white dark:bg-[#070e22] text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-800"
                                            />
                                        </div>
                                        <div className="space-y-1">
                                            <label className="text-xs font-medium text-slate-700 dark:text-slate-300">
                                                No. WhatsApp
                                            </label>
                                            <input
                                                type="tel"
                                                required
                                                value={ticketForm.phone}
                                                onChange={(e) =>
                                                    setTicketForm({ ...ticketForm, phone: e.target.value })
                                                }
                                                placeholder="08123456789"
                                                className="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-blue-800 bg-white dark:bg-[#070e22] text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-800"
                                            />
                                        </div>
                                    </div>

                                    <div className="space-y-1">
                                        <label className="text-xs font-medium text-slate-700 dark:text-slate-300">
                                            Instansi / Asal Kota
                                        </label>
                                        <input
                                            type="text"
                                            required
                                            value={ticketForm.institution}
                                            onChange={(e) =>
                                                setTicketForm({ ...ticketForm, institution: e.target.value })
                                            }
                                            placeholder="Contoh: Bandung / Mahasiswa Unpad"
                                            className="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 dark:border-blue-800 bg-white dark:bg-[#070e22] text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-800"
                                        />
                                    </div>

                                    <div className="pt-2">
                                        <button
                                            type="submit"
                                            className="w-full py-2.5 rounded-xl text-xs font-semibold text-white bg-blue-900 hover:bg-blue-800 dark:bg-blue-700 transition-colors"
                                        >
                                            Simpan Tiket Masuk
                                        </button>
                                    </div>
                                </form>
                            ) : (
                                <div className="space-y-4 text-center">
                                    <div className="p-4 rounded-xl border border-blue-900 bg-blue-950 text-white text-left space-y-3">
                                        <div className="flex items-center justify-between border-b border-blue-800 pb-2">
                                            <span className="font-bold text-xs tracking-wider">FUTURISE JABAR</span>
                                            <span className="text-[10px] bg-white text-blue-950 font-bold px-1.5 py-0.5 rounded">
                                                TIKET MASUK
                                            </span>
                                        </div>

                                        <div className="text-xs space-y-0.5">
                                            <div className="text-blue-300 text-[11px]">Nama Pengunjung:</div>
                                            <div className="font-bold text-sm text-white">{ticketForm.name}</div>
                                            <div className="text-slate-300 text-[11px]">{ticketForm.institution}</div>
                                        </div>

                                        <div className="text-xs border-t border-blue-800 pt-2 space-y-0.5">
                                            <div className="font-semibold text-white">{ticketModalFlyer.title}</div>
                                            <div className="text-blue-200 text-[11px]">{ticketModalFlyer.venue}</div>
                                            <div className="text-blue-300 text-[11px] font-mono">{ticketModalFlyer.dateRange}</div>
                                        </div>

                                        <div className="flex items-center justify-between pt-2 border-t border-blue-800">
                                            <div>
                                                <div className="text-[10px] text-blue-300">Nomor Registrasi</div>
                                                <div className="font-mono text-base font-bold text-white">
                                                    {ticketCode}
                                                </div>
                                            </div>
                                            <div className="w-12 h-12 bg-white rounded p-1 flex items-center justify-center">
                                                <QrCode className="w-full h-full text-blue-950" />
                                            </div>
                                        </div>
                                    </div>

                                    <p className="text-xs text-slate-500">
                                        Simpan atau tangkap layar tiket ini untuk ditunjukkan kepada petugas di lokasi acara.
                                    </p>

                                    <div className="flex items-center gap-2">
                                        <button
                                            type="button"
                                            onClick={handleResetTicketModal}
                                            className="flex-1 py-2 rounded-lg text-xs font-medium text-slate-700 dark:text-slate-300 bg-slate-100 hover:bg-slate-200 dark:bg-blue-950"
                                        >
                                            Tutup
                                        </button>
                                        <button
                                            type="button"
                                            onClick={() => window.print()}
                                            className="flex-1 py-2 rounded-lg text-xs font-semibold text-white bg-blue-900 hover:bg-blue-800 dark:bg-blue-700 flex items-center justify-center gap-1.5"
                                        >
                                            <Printer className="w-3.5 h-3.5" />
                                            <span>Cetak Tiket</span>
                                        </button>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            )}
        </PublicLayout>
    );
}
