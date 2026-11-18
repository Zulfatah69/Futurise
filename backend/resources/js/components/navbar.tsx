import { useState, useEffect } from 'react';
import { Link, usePage } from '@inertiajs/react';
import {
    Compass,
    Newspaper,
    Video,
    FileImage,
    Sun,
    Moon,
    Menu,
    X,
    Calendar,
    ArrowUpRight,
    Search,
    MapPin,
    Ticket,
} from 'lucide-react';
import { useAppearance } from '@/hooks/use-appearance';
import { FUTURISE_CONFIG } from '@/data/mock-data';

interface NavbarProps {
    activeNav?: 'home' | 'berita' | 'video' | 'flyer';
    onOpenSearch?: () => void;
    onOpenTicketModal?: () => void;
}

export function Navbar({ activeNav, onOpenSearch, onOpenTicketModal }: NavbarProps) {
    const { url } = usePage();
    const { appearance, updateAppearance } = useAppearance();
    const [scrolled, setScrolled] = useState(false);
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

    useEffect(() => {
        const handleScroll = () => {
            setScrolled(window.scrollY > 15);
        };
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    const navItems = [
        {
            label: 'Home',
            href: '/',
            key: 'home',
            icon: Compass,
            active: activeNav === 'home' || url === '/',
        },
        {
            label: 'Terkait Berita',
            href: '/berita',
            key: 'berita',
            icon: Newspaper,
            active: activeNav === 'berita' || url.startsWith('/berita'),
        },
        {
            label: 'Video',
            href: '/video',
            key: 'video',
            icon: Video,
            active: activeNav === 'video' || url.startsWith('/video'),
        },
        {
            label: 'Flyer',
            href: '/flyer',
            key: 'flyer',
            icon: FileImage,
            active: activeNav === 'flyer' || url.startsWith('/flyer'),
        },
    ];

    const toggleTheme = () => {
        updateAppearance(appearance === 'dark' ? 'light' : 'dark');
    };

    return (
        <header className="sticky top-0 z-50 w-full">
            {/* Top Bar - Clean Dark Navy with Event Specs */}
            <div className="bg-blue-950 text-white text-xs py-1.5 px-4 border-b border-blue-900">
                <div className="max-w-7xl mx-auto flex items-center justify-between">
                    <div className="flex items-center gap-2 overflow-hidden text-ellipsis whitespace-nowrap">
                        <span className="bg-blue-900 text-blue-200 px-2 py-0.5 rounded text-[11px] font-semibold tracking-wide">
                            APTIKOM JAWA BARAT
                        </span>
                        <span className="font-medium text-slate-100">
                            FUTURiSE 2026: West Java Digital Innovation Festival
                        </span>
                        <span className="text-blue-300 hidden sm:inline">
                            • 26–28 November 2026 di Sabuga ITB Bandung
                        </span>
                    </div>
                    <div className="hidden md:flex items-center gap-4 text-xs font-normal text-blue-200">
                        <span className="flex items-center gap-1">
                            <MapPin className="w-3.5 h-3.5" />
                            Sabuga ITB, Bandung
                        </span>
                        <span>|</span>
                        <Link
                            href="/flyer"
                            className="hover:text-white underline underline-offset-2 flex items-center gap-1"
                        >
                            Brosur & Rundown
                            <ArrowUpRight className="w-3 h-3" />
                        </Link>
                    </div>
                </div>
            </div>

            {/* Main Navbar */}
            <nav
                className={`w-full transition-all duration-200 ${
                    scrolled
                        ? 'bg-blue-950/95 dark:bg-[#060e22]/95 backdrop-blur-md shadow-md border-b border-blue-900'
                        : 'bg-blue-950 dark:bg-[#060e22] border-b border-blue-900/80'
                }`}
            >
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center justify-between h-16">
                        {/* Brand Logo & Event Tag */}
                        <div className="flex items-center gap-3">
                            <Link href="/" className="flex items-center gap-2.5 group">
                                <div className="flex items-center justify-center w-10 h-10 rounded-lg bg-white text-blue-950 font-black text-xl shadow-sm group-hover:scale-105 transition-transform">
                                    F
                                </div>
                                <div className="flex flex-col">
                                    <div className="flex items-center gap-1.5">
                                        <span className="font-extrabold text-xl text-white tracking-tight">
                                            FUTUR<span className="text-blue-300">i</span>SE
                                        </span>
                                        <span className="text-[10px] font-bold px-1.5 py-0.5 rounded bg-blue-900 text-blue-100 border border-blue-800">
                                            2026
                                        </span>
                                    </div>
                                    <span className="text-[10px] text-blue-200 font-normal">
                                        West Java Digital Innovation Festival
                                    </span>
                                </div>
                            </Link>
                        </div>

                        {/* Desktop Navigation Links */}
                        <div className="hidden md:flex items-center gap-1">
                            {navItems.map((item) => {
                                const Icon = item.icon;
                                return (
                                    <Link
                                        key={item.key}
                                        href={item.href}
                                        className={`flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-sm font-medium transition-colors ${
                                            item.active
                                                ? 'bg-white text-blue-950 font-semibold shadow-sm'
                                                : 'text-slate-200 hover:text-white hover:bg-blue-900/60'
                                        }`}
                                    >
                                        <Icon className="w-4 h-4" />
                                        <span>{item.label}</span>
                                    </Link>
                                );
                            })}
                        </div>

                        {/* Right Utility Actions */}
                        <div className="hidden md:flex items-center gap-2.5">
                            {onOpenSearch && (
                                <button
                                    onClick={onOpenSearch}
                                    type="button"
                                    className="flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs text-blue-200 bg-blue-900/60 hover:bg-blue-900 border border-blue-800 hover:border-blue-700 transition-colors"
                                    title="Pencarian cepat (Tekan /)"
                                >
                                    <Search className="w-3.5 h-3.5 text-blue-300" />
                                    <span>Cari Karya / Berita</span>
                                    <kbd className="hidden lg:inline-block px-1.5 py-0.5 text-[10px] font-mono bg-blue-950 rounded border border-blue-800 text-blue-300">
                                        /
                                    </kbd>
                                </button>
                            )}

                            {/* Theme Toggle */}
                            <button
                                onClick={toggleTheme}
                                className="p-2 rounded-lg text-blue-200 hover:text-white hover:bg-blue-900 transition-colors"
                                title={appearance === 'dark' ? 'Mode Terang' : 'Mode Gelap'}
                            >
                                {appearance === 'dark' ? (
                                    <Sun className="w-4 h-4 text-amber-300" />
                                ) : (
                                    <Moon className="w-4 h-4 text-blue-200" />
                                )}
                            </button>

                            {/* Direct E-Pass Ticket Action */}
                            <button
                                onClick={onOpenTicketModal}
                                className="flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold bg-white text-blue-950 hover:bg-blue-50 shadow-sm transition-all active:scale-95"
                            >
                                <Ticket className="w-3.5 h-3.5 text-blue-950" />
                                <span>Daftar Tiket Pengunjung</span>
                            </button>
                        </div>

                        {/* Mobile Menu Button */}
                        <div className="flex md:hidden items-center gap-2">
                            <button
                                onClick={toggleTheme}
                                className="p-2 rounded-lg text-blue-200 hover:text-white hover:bg-blue-900"
                            >
                                {appearance === 'dark' ? (
                                    <Sun className="w-4 h-4 text-amber-300" />
                                ) : (
                                    <Moon className="w-4 h-4 text-blue-200" />
                                )}
                            </button>
                            <button
                                onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                                className="p-2 rounded-lg text-white hover:bg-blue-900 focus:outline-none"
                                aria-label="Menu"
                            >
                                {mobileMenuOpen ? (
                                    <X className="w-6 h-6" />
                                ) : (
                                    <Menu className="w-6 h-6" />
                                )}
                            </button>
                        </div>
                    </div>
                </div>

                {/* Mobile Drawer */}
                {mobileMenuOpen && (
                    <div className="md:hidden border-t border-blue-900 bg-blue-950 px-4 pt-3 pb-5 space-y-2">
                        {navItems.map((item) => {
                            const Icon = item.icon;
                            return (
                                <Link
                                    key={item.key}
                                    href={item.href}
                                    onClick={() => setMobileMenuOpen(false)}
                                    className={`flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium ${
                                        item.active
                                            ? 'bg-white text-blue-950 font-semibold'
                                            : 'text-slate-200 hover:bg-blue-900'
                                    }`}
                                >
                                    <Icon className="w-4 h-4" />
                                    <span>{item.label}</span>
                                </Link>
                            );
                        })}

                        <div className="pt-3 border-t border-blue-900 space-y-2">
                            {onOpenSearch && (
                                <button
                                    onClick={() => {
                                        setMobileMenuOpen(false);
                                        onOpenSearch();
                                    }}
                                    className="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-xs font-medium text-blue-200 bg-blue-900/60 border border-blue-800"
                                >
                                    <Search className="w-4 h-4" />
                                    <span>Pencarian Karya & Info</span>
                                </button>
                            )}

                            <button
                                onClick={() => {
                                    setMobileMenuOpen(false);
                                    if (onOpenTicketModal) onOpenTicketModal();
                                }}
                                className="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-xs font-bold bg-white text-blue-950 hover:bg-blue-50"
                            >
                                <Ticket className="w-4 h-4" />
                                <span>Daftar Tiket Pengunjung (Gratis)</span>
                            </button>
                        </div>
                    </div>
                )}
            </nav>
        </header>
    );
}
