import { useState } from 'react';
import { Link } from '@inertiajs/react';
import {
    Mail,
    Phone,
    MapPin,
    Calendar,
    Newspaper,
    Video,
    FileImage,
    CheckCircle2,
    Send,
    ExternalLink,
    Building2,
    ShieldCheck,
} from 'lucide-react';
import { FUTURISE_CONFIG, WEST_JAVA_CAMPUSES } from '@/data/mock-data';

export function Footer() {
    const [newsletterEmail, setNewsletterEmail] = useState('');
    const [subscribed, setSubscribed] = useState(false);

    const handleSubscribe = (e: React.FormEvent) => {
        e.preventDefault();
        if (newsletterEmail) {
            setSubscribed(true);
            setTimeout(() => {
                setSubscribed(false);
                setNewsletterEmail('');
            }, 3000);
        }
    };

    return (
        <footer className="w-full bg-[#060e22] text-slate-300 border-t border-blue-950">
            {/* Newsletter Strip */}
            <div className="border-b border-blue-950 bg-[#091533] py-8 px-4 sm:px-6 lg:px-8">
                <div className="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
                    <div className="space-y-1 text-center md:text-left">
                        <div className="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-[11px] font-semibold bg-blue-900 text-blue-200 border border-blue-800">
                            Pemberitahuan Resmi FUTURiSE
                        </div>
                        <h3 className="text-lg font-bold text-white tracking-tight">
                            Dapatkan Pembaruan Jadwal Sesi & Pengumuman Kurasi Karya
                        </h3>
                        <p className="text-xs text-blue-200/80 max-w-xl">
                            Daftarkan email Anda untuk menerima notifikasi pengumuman 10 finalis pitching, jadwal technical meeting, dan panduan kunjungan rombongan.
                        </p>
                    </div>

                    <form
                        onSubmit={handleSubscribe}
                        className="w-full md:w-auto flex flex-col sm:flex-row items-center gap-2 max-w-md"
                    >
                        {subscribed ? (
                            <div className="flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-900 border border-blue-700 text-white text-xs font-medium">
                                <CheckCircle2 className="w-4 h-4 text-white" />
                                Email Anda telah terdaftar dalam buletin resmi acara.
                            </div>
                        ) : (
                            <>
                                <div className="relative w-full sm:w-64">
                                    <Mail className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-blue-300" />
                                    <input
                                        type="email"
                                        required
                                        value={newsletterEmail}
                                        onChange={(e) => setNewsletterEmail(e.target.value)}
                                        placeholder="Alamat email aktif Anda"
                                        className="w-full pl-9 pr-3 py-2 text-xs rounded-lg bg-[#0e1d44] border border-blue-800 text-white placeholder-slate-400 focus:outline-none focus:border-white"
                                    />
                                </div>
                                <button
                                    type="submit"
                                    className="w-full sm:w-auto px-4 py-2 rounded-lg text-xs font-semibold text-blue-950 bg-white hover:bg-slate-100 transition-colors"
                                >
                                    Berlangganan
                                </button>
                            </>
                        )}
                    </form>
                </div>
            </div>

            {/* Main Links */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-12">
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
                    {/* Brand Column */}
                    <div className="lg:col-span-2 space-y-3.5">
                        <Link href="/" className="flex items-center gap-2">
                            <div className="flex items-center justify-center w-8 h-8 rounded-lg bg-white text-blue-950 font-extrabold text-base">
                                F
                            </div>
                            <span className="font-extrabold text-xl text-white">FUTURiSE</span>
                            <span className="text-[10px] font-bold px-1.5 py-0.5 rounded bg-blue-900 text-blue-200 border border-blue-800">
                                2026
                            </span>
                        </Link>
                        <p className="text-xs leading-relaxed text-blue-200/80 max-w-sm">
                            <strong className="text-white">FUTURiSE</strong> (West Java Digital Innovation Festival) adalah perhelatan pameran dan kolaborasi teknologi yang mempertemukan 60 karya inovasi mahasiswa dari 30 kampus informatika se-Jawa Barat dengan ekosistem industri dan masyarakat.
                        </p>
                        <div className="space-y-1.5 text-xs text-blue-200/80 pt-1">
                            <div className="flex items-start gap-2">
                                <Building2 className="w-3.5 h-3.5 text-blue-300 shrink-0 mt-0.5" />
                                <span>Penyelenggara: {FUTURISE_CONFIG.organizerFull}</span>
                            </div>
                            <div className="flex items-start gap-2">
                                <MapPin className="w-3.5 h-3.5 text-blue-300 shrink-0 mt-0.5" />
                                <span>Venue: {FUTURISE_CONFIG.venue}</span>
                            </div>
                            <div className="flex items-center gap-2">
                                <Calendar className="w-3.5 h-3.5 text-blue-300 shrink-0" />
                                <span>Pelaksanaan: {FUTURISE_CONFIG.dates} ({FUTURISE_CONFIG.time})</span>
                            </div>
                            <div className="flex items-center gap-2">
                                <Mail className="w-3.5 h-3.5 text-blue-300 shrink-0" />
                                <span>{FUTURISE_CONFIG.secretariat.email}</span>
                            </div>
                        </div>
                    </div>

                    {/* Navigasi Utama */}
                    <div className="space-y-3">
                        <h4 className="text-xs font-bold text-white uppercase tracking-wider">
                            Navigasi Acara
                        </h4>
                        <ul className="space-y-2 text-xs">
                            <li>
                                <Link href="/" className="hover:text-white transition-colors">
                                    Beranda Utama
                                </Link>
                            </li>
                            <li>
                                <Link href="/berita" className="hover:text-white transition-colors">
                                    Terkait Berita & Warta
                                </Link>
                            </li>
                            <li>
                                <Link href="/video" className="hover:text-white transition-colors">
                                    Video & Dokumentasi
                                </Link>
                            </li>
                            <li>
                                <Link href="/flyer" className="hover:text-white transition-colors">
                                    Brosur, Flyer & Rundown
                                </Link>
                            </li>
                        </ul>
                    </div>

                    {/* Program Kunci */}
                    <div className="space-y-3">
                        <h4 className="text-xs font-bold text-white uppercase tracking-wider">
                            Program Acara
                        </h4>
                        <ul className="space-y-2 text-xs">
                            <li>
                                <span className="text-slate-300">Showcase 60 Karya Inovasi</span>
                            </li>
                            <li>
                                <span className="text-slate-300">Pitching Day 10 Finalis</span>
                            </li>
                            <li>
                                <span className="text-slate-300">Industry-Campus Matchmaking</span>
                            </li>
                            <li>
                                <span className="text-slate-300">School Tech Tour (SMK/SMA)</span>
                            </li>
                            <li>
                                <span className="text-slate-300">Penjurian Rubrik & Voting</span>
                            </li>
                        </ul>
                    </div>

                    {/* Kampus Mitra Jabar */}
                    <div className="space-y-3">
                        <h4 className="text-xs font-bold text-white uppercase tracking-wider">
                            Delegasi Kampus Jabar
                        </h4>
                        <div className="text-xs space-y-1.5 text-blue-200/80">
                            <p>30 Kampus Terkonfirmasi:</p>
                            <div className="flex flex-wrap gap-1 pt-1">
                                {WEST_JAVA_CAMPUSES.slice(0, 8).map((campus, idx) => (
                                    <span
                                        key={idx}
                                        className="inline-block px-1.5 py-0.5 rounded bg-blue-900/60 text-[10px] text-blue-200 border border-blue-800"
                                    >
                                        {campus.split(' (')[1]?.replace(')', '') || campus.split(' ')[0]}
                                    </span>
                                ))}
                                <span className="inline-block px-1.5 py-0.5 rounded bg-blue-950 text-[10px] text-blue-300">
                                    +22 Kampus Lainnya
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Bottom Bar */}
                <div className="mt-10 pt-6 border-t border-blue-950 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-blue-300/80">
                    <p>
                        © 2026 <strong>FUTURiSE</strong> — West Java Digital Innovation Festival. Diselenggarakan oleh APTIKOM Wilayah IV Jawa Barat.
                    </p>
                    <div className="flex items-center gap-4">
                        <span className="flex items-center gap-1 text-[11px] text-blue-300">
                            <ShieldCheck className="w-3.5 h-3.5 text-blue-400" />
                            Sistem Resmi APTIKOM Jabar
                        </span>
                        <a
                            href="#top"
                            className="hover:text-white transition-colors"
                        >
                            Kembali ke Atas ↑
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    );
}
