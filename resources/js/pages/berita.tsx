import { useState } from 'react';
import { Link } from '@inertiajs/react';
import PublicLayout from '@/layouts/public-layout';
import {
    Newspaper,
    Search,
    Calendar,
    Clock,
    Share2,
    ArrowRight,
    MapPin,
    CheckCircle2,
    X,
    Quote,
    ExternalLink,
} from 'lucide-react';
import {
    NEWS_DATA,
    CATEGORIES_LIST,
    WEST_JAVA_REGIONS,
} from '@/data/mock-data';
import { NewsArticle } from '@/types/futurise';

export default function BeritaPage() {
    const [searchQuery, setSearchQuery] = useState('');
    const [selectedCategory, setSelectedCategory] = useState<string>('Semua');
    const [selectedRegion, setSelectedRegion] = useState<string>('Semua Wilayah');
    const [activeArticle, setActiveArticle] = useState<NewsArticle | null>(null);
    const [copiedShare, setCopiedShare] = useState(false);

    // Filter news
    const filteredNews = NEWS_DATA.filter((news) => {
        const matchesCategory =
            selectedCategory === 'Semua' || news.category === selectedCategory;
        const matchesRegion =
            selectedRegion === 'Semua Wilayah' || news.region === selectedRegion;
        const q = searchQuery.toLowerCase().trim();
        const matchesQuery =
            !q ||
            news.title.toLowerCase().includes(q) ||
            news.excerpt.toLowerCase().includes(q) ||
            news.city.toLowerCase().includes(q) ||
            news.tags.some((t) => t.toLowerCase().includes(q));

        return matchesCategory && matchesRegion && matchesQuery;
    });

    const featuredStory = NEWS_DATA.find((item) => item.featured) || NEWS_DATA[0];

    const handleCopyShare = () => {
        navigator.clipboard.writeText(window.location.href);
        setCopiedShare(true);
        setTimeout(() => setCopiedShare(false), 2500);
    };

    return (
        <PublicLayout
            title="Terkait Berita - Futurise Pameran Jawa Barat"
            description="Kumpulan berita resmi, liputan acara, dan jadwal kegiatan pameran di kota-kota Jawa Barat."
            activeNav="berita"
        >
            {/* Header Hero Banner */}
            <div className="bg-white dark:bg-[#070e22] border-b border-slate-200 dark:border-blue-950 py-10 lg:py-14">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3.5">
                    <div className="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-900 dark:bg-blue-950 dark:text-blue-200 border border-blue-200 dark:border-blue-800">
                        <Newspaper className="w-3.5 h-3.5 text-blue-900 dark:text-blue-300" />
                        Berita & Pengumuman Acara
                    </div>

                    <h1 className="text-3xl sm:text-4xl font-extrabold text-blue-950 dark:text-white tracking-tight">
                        Berita & Liputan Pameran Jawa Barat
                    </h1>

                    <p className="text-sm sm:text-base text-slate-600 dark:text-slate-300 max-w-2xl leading-relaxed">
                        Ikuti perkembangan persiapan acara, liputan pembukaan pameran, serta agenda kegiatan dari berbagai gedung expo di Jawa Barat.
                    </p>

                    {/* Filter & Search Bar */}
                    <div className="pt-3 flex flex-col md:flex-row items-center gap-3">
                        <div className="relative w-full md:w-80">
                            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                            <input
                                type="text"
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                placeholder="Cari judul berita atau lokasi..."
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
                {/* FEATURED STORY */}
                {selectedCategory === 'Semua' &&
                    selectedRegion === 'Semua Wilayah' &&
                    !searchQuery && (
                        <div
                            onClick={() => setActiveArticle(featuredStory)}
                            className="group cursor-pointer rounded-2xl overflow-hidden bg-white dark:bg-[#0c183a] border border-slate-200 dark:border-blue-900 shadow-sm hover:border-blue-700 transition-colors"
                        >
                            <div className="grid grid-cols-1 lg:grid-cols-12 gap-0">
                                <div className="lg:col-span-7 relative h-64 sm:h-80 lg:h-auto overflow-hidden bg-slate-900">
                                    <img
                                        src={featuredStory.image}
                                        alt={featuredStory.title}
                                        className="w-full h-full object-cover"
                                    />
                                    <div className="absolute top-3 left-3">
                                        <span className="px-2.5 py-1 rounded text-xs font-semibold bg-blue-900 text-white">
                                            Berita Pilihan
                                        </span>
                                    </div>
                                    <div className="absolute bottom-3 left-3 right-3 text-xs text-white bg-black/60 px-3 py-1.5 rounded-lg flex items-center gap-1.5 backdrop-blur-xs w-fit">
                                        <MapPin className="w-3.5 h-3.5 text-blue-300" />
                                        <span>{featuredStory.city} • {featuredStory.exhibitionName}</span>
                                    </div>
                                </div>

                                <div className="lg:col-span-5 p-6 sm:p-8 flex flex-col justify-between space-y-4">
                                    <div className="space-y-3">
                                        <div className="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                            <span className="font-semibold text-blue-900 dark:text-blue-300">
                                                {featuredStory.category}
                                            </span>
                                            <span>•</span>
                                            <span>{featuredStory.publishedAt}</span>
                                        </div>

                                        <h2 className="text-xl sm:text-2xl font-bold text-blue-950 dark:text-white group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors leading-snug">
                                            {featuredStory.title}
                                        </h2>

                                        <p className="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                                            {featuredStory.excerpt}
                                        </p>
                                    </div>

                                    <div className="pt-3 border-t border-slate-100 dark:border-blue-950 flex items-center justify-between">
                                        <div className="flex items-center gap-2">
                                            <img
                                                src={featuredStory.author.avatar}
                                                alt={featuredStory.author.name}
                                                className="w-7 h-7 rounded-full object-cover"
                                            />
                                            <span className="text-xs font-medium text-slate-700 dark:text-slate-200">
                                                {featuredStory.author.name}
                                            </span>
                                        </div>

                                        <span className="text-xs font-semibold text-blue-900 dark:text-blue-300 group-hover:underline flex items-center gap-1">
                                            <span>Baca Berita</span>
                                            <ArrowRight className="w-3.5 h-3.5" />
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}

                {/* NEWS GRID */}
                <div>
                    <div className="flex items-center justify-between mb-5">
                        <h3 className="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-200">
                            Daftar Artikel Berita ({filteredNews.length})
                        </h3>
                    </div>

                    {filteredNews.length === 0 ? (
                        <div className="text-center py-12 bg-white dark:bg-[#0c183a] rounded-2xl border border-slate-200 dark:border-blue-900 p-8 space-y-2">
                            <Newspaper className="w-8 h-8 text-slate-400 mx-auto" />
                            <h4 className="text-sm font-bold text-blue-950 dark:text-white">
                                Tidak ada berita yang sesuai
                            </h4>
                            <p className="text-xs text-slate-500">
                                Coba ganti kata kunci pencarian atau ubah pilihan filter di atas.
                            </p>
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {filteredNews.map((news) => (
                                <article
                                    key={news.id}
                                    onClick={() => setActiveArticle(news)}
                                    className="group cursor-pointer flex flex-col bg-white dark:bg-[#0c183a] rounded-2xl border border-slate-200 dark:border-blue-900 overflow-hidden shadow-xs hover:border-blue-700 transition-colors"
                                >
                                    <div className="relative h-44 overflow-hidden bg-slate-900">
                                        <img
                                            src={news.image}
                                            alt={news.title}
                                            className="w-full h-full object-cover"
                                        />
                                        <div className="absolute top-2.5 left-2.5">
                                            <span className="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-950/80 text-white">
                                                {news.category}
                                            </span>
                                        </div>
                                        <div className="absolute bottom-2 left-2.5 right-2.5 text-[11px] text-white bg-black/60 px-2 py-0.5 rounded flex items-center gap-1">
                                            <MapPin className="w-3 h-3 text-blue-300" />
                                            <span>{news.city}</span>
                                        </div>
                                    </div>

                                    <div className="p-4 flex-1 flex flex-col justify-between space-y-3">
                                        <div className="space-y-1.5">
                                            <div className="text-[11px] text-slate-400">
                                                {news.publishedAt}
                                            </div>
                                            <h3 className="text-sm font-bold text-blue-950 dark:text-white group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors line-clamp-2">
                                                {news.title}
                                            </h3>
                                            <p className="text-xs text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                                {news.excerpt}
                                            </p>
                                        </div>

                                        <div className="pt-3 border-t border-slate-100 dark:border-blue-950 flex items-center justify-between text-xs">
                                            <span className="text-[11px] text-slate-500">
                                                {news.author.name}
                                            </span>
                                            <span className="font-semibold text-blue-900 dark:text-blue-300 flex items-center gap-1">
                                                <span>Baca</span>
                                                <ArrowRight className="w-3 h-3" />
                                            </span>
                                        </div>
                                    </div>
                                </article>
                            ))}
                        </div>
                    )}
                </div>
            </div>

            {/* ARTICLE READER MODAL */}
            {activeArticle && (
                <div
                    className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-xs animate-in fade-in duration-200"
                    onClick={() => setActiveArticle(null)}
                >
                    <div
                        className="w-full max-w-2xl bg-white dark:bg-[#0c183a] rounded-2xl overflow-hidden shadow-xl border border-slate-200 dark:border-blue-900 flex flex-col max-h-[90vh]"
                        onClick={(e) => e.stopPropagation()}
                    >
                        {/* Header */}
                        <div className="p-4 border-b border-slate-200 dark:border-blue-900 flex items-center justify-between bg-slate-50 dark:bg-[#070e22]">
                            <div className="flex items-center gap-2">
                                <span className="px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 dark:bg-blue-950 text-blue-900 dark:text-blue-200">
                                    {activeArticle.category}
                                </span>
                                <span className="text-xs text-slate-500">
                                    {activeArticle.city} • {activeArticle.publishedAt}
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
                                    onClick={() => setActiveArticle(null)}
                                    className="p-1.5 rounded-lg hover:bg-slate-200 dark:hover:bg-blue-950 text-slate-400 hover:text-slate-800 dark:hover:text-white"
                                >
                                    <X className="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        {/* Content */}
                        <div className="p-6 overflow-y-auto space-y-4 text-xs sm:text-sm">
                            <h2 className="text-xl sm:text-2xl font-bold text-blue-950 dark:text-white leading-snug">
                                {activeArticle.title}
                            </h2>

                            <div className="flex items-center gap-2 text-xs text-slate-500 pb-2 border-b border-slate-100 dark:border-blue-950">
                                <img
                                    src={activeArticle.author.avatar}
                                    alt={activeArticle.author.name}
                                    className="w-6 h-6 rounded-full object-cover"
                                />
                                <span>Ditulis oleh: {activeArticle.author.name} ({activeArticle.author.role})</span>
                            </div>

                            <div className="rounded-xl overflow-hidden bg-slate-900">
                                <img
                                    src={activeArticle.image}
                                    alt={activeArticle.title}
                                    className="w-full h-64 object-cover"
                                />
                            </div>

                            <div className="space-y-3 text-slate-700 dark:text-slate-200 leading-relaxed pt-1">
                                {activeArticle.content.map((p, i) => (
                                    <p key={i}>{p}</p>
                                ))}
                            </div>

                            {activeArticle.quote && (
                                <div className="p-3.5 rounded-xl bg-blue-50 dark:bg-blue-950/60 border-l-4 border-blue-900 text-xs text-blue-950 dark:text-blue-100 space-y-1">
                                    <p className="italic">"{activeArticle.quote.text}"</p>
                                    <div className="font-semibold text-slate-500 dark:text-blue-300">
                                        — {activeArticle.quote.author}
                                    </div>
                                </div>
                            )}

                            {activeArticle.exhibitionName && (
                                <div className="p-3 rounded-xl bg-slate-100 dark:bg-[#070e22] border border-slate-200 dark:border-blue-900 flex items-center justify-between text-xs">
                                    <div>
                                        <div className="text-slate-400 text-[11px]">Pameran Terkait:</div>
                                        <div className="font-bold text-blue-950 dark:text-white">
                                            {activeArticle.exhibitionName}
                                        </div>
                                    </div>
                                    <Link
                                        href="/flyer"
                                        onClick={() => setActiveArticle(null)}
                                        className="text-xs font-semibold text-blue-900 dark:text-blue-300 hover:underline flex items-center gap-1"
                                    >
                                        <span>Lihat Brosur</span>
                                        <ExternalLink className="w-3.5 h-3.5" />
                                    </Link>
                                </div>
                            )}
                        </div>

                        {/* Footer */}
                        <div className="p-3 border-t border-slate-200 dark:border-blue-900 flex justify-end bg-slate-50 dark:bg-[#070e22]">
                            <button
                                type="button"
                                onClick={() => setActiveArticle(null)}
                                className="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-200 dark:bg-blue-950 hover:bg-slate-300 dark:hover:bg-blue-900 text-slate-700 dark:text-slate-300"
                            >
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </PublicLayout>
    );
}
