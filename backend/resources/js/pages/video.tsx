import { useState } from 'react';
import { Link } from '@inertiajs/react';
import PublicLayout from '@/layouts/public-layout';
import {
    Video as VideoIcon,
    Play,
    Search,
    MapPin,
    X,
    Share2,
    CheckCircle2,
    ExternalLink,
} from 'lucide-react';
import { VIDEOS_DATA } from '@/data/mock-data';
import { VideoItem } from '@/types/futurise';

const VIDEO_CATEGORIES = [
    'Semua',
    'Teaser',
    'Aftermovie',
    'Liputan Stan',
    'Talkshow',
    'Dokumentasi',
] as const;

export default function VideoPage() {
    const [searchQuery, setSearchQuery] = useState('');
    const [selectedCategory, setSelectedCategory] = useState<string>('Semua');
    const [selectedCity, setSelectedCity] = useState<string>('Semua Kota');
    const [activeVideo, setActiveVideo] = useState<VideoItem | null>(null);
    const [copiedShare, setCopiedShare] = useState(false);

    const cities = ['Semua Kota', 'Bandung', 'Bekasi', 'Bogor'];

    const filteredVideos = VIDEOS_DATA.filter((vid) => {
        const matchesCategory =
            selectedCategory === 'Semua' || vid.category === selectedCategory;
        const matchesCity =
            selectedCity === 'Semua Kota' || vid.city === selectedCity;
        const q = searchQuery.toLowerCase().trim();
        const matchesQuery =
            !q ||
            vid.title.toLowerCase().includes(q) ||
            vid.description.toLowerCase().includes(q) ||
            vid.exhibitionName.toLowerCase().includes(q);

        return matchesCategory && matchesCity && matchesQuery;
    });

    const spotlightVideo = VIDEOS_DATA.find((v) => v.featured) || VIDEOS_DATA[0];

    const handleCopyShare = () => {
        navigator.clipboard.writeText(window.location.href);
        setCopiedShare(true);
        setTimeout(() => setCopiedShare(false), 2500);
    };

    return (
        <PublicLayout
            title="Video & Dokumentasi - Futurise Pameran Jawa Barat"
            description="Koleksi video liputan pameran, cuplikan suasana pembukaan acara, dan wawancara peserta di Jawa Barat."
            activeNav="video"
        >
            {/* Header Hero Banner */}
            <div className="bg-white dark:bg-[#070e22] border-b border-slate-200 dark:border-blue-950 py-10 lg:py-14">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3.5">
                    <div className="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-900 dark:bg-blue-950 dark:text-blue-200 border border-blue-200 dark:border-blue-800">
                        <VideoIcon className="w-3.5 h-3.5 text-blue-900 dark:text-blue-300" />
                        Dokumentasi Video Acara
                    </div>

                    <h1 className="text-3xl sm:text-4xl font-extrabold text-blue-950 dark:text-white tracking-tight">
                        Video & Dokumentasi Pameran Jawa Barat
                    </h1>

                    <p className="text-sm sm:text-base text-slate-600 dark:text-slate-300 max-w-2xl leading-relaxed">
                        Tonton rekaman video suasana stan, sesi tanya jawab bersama pengrajin dan pembuat aplikasi, serta panduan praktis menuju lokasi pameran.
                    </p>

                    {/* Filter & Search Bar */}
                    <div className="pt-3 flex flex-col md:flex-row items-center gap-3">
                        <div className="relative w-full md:w-80">
                            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                            <input
                                type="text"
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                placeholder="Cari judul video atau acara..."
                                className="w-full pl-9 pr-3 py-2 text-xs rounded-lg bg-white dark:bg-[#0c183a] border border-slate-300 dark:border-blue-900 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-800"
                            />
                        </div>

                        <div className="w-full md:w-auto">
                            <select
                                value={selectedCity}
                                onChange={(e) => setSelectedCity(e.target.value)}
                                className="w-full md:w-auto px-3 py-2 text-xs rounded-lg bg-white dark:bg-[#0c183a] border border-slate-300 dark:border-blue-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-800"
                            >
                                {cities.map((city) => (
                                    <option key={city} value={city}>
                                        {city}
                                    </option>
                                ))}
                            </select>
                        </div>

                        {(searchQuery ||
                            selectedCategory !== 'Semua' ||
                            selectedCity !== 'Semua Kota') && (
                            <button
                                type="button"
                                onClick={() => {
                                    setSearchQuery('');
                                    setSelectedCategory('Semua');
                                    setSelectedCity('Semua Kota');
                                }}
                                className="text-xs text-blue-900 dark:text-blue-300 hover:underline px-2 py-1 font-semibold"
                            >
                                Reset Filter
                            </button>
                        )}
                    </div>

                    {/* Category Buttons */}
                    <div className="flex items-center gap-2 overflow-x-auto pt-1 scrollbar-none">
                        {VIDEO_CATEGORIES.map((cat) => (
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

            {/* Main Content */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
                {/* SPOTLIGHT VIDEO */}
                {selectedCategory === 'Semua' &&
                    selectedCity === 'Semua Kota' &&
                    !searchQuery && (
                        <div
                            onClick={() => setActiveVideo(spotlightVideo)}
                            className="group cursor-pointer rounded-2xl overflow-hidden bg-white dark:bg-[#0c183a] border border-slate-200 dark:border-blue-900 shadow-sm hover:border-blue-700 transition-colors"
                        >
                            <div className="grid grid-cols-1 lg:grid-cols-12 gap-0">
                                <div className="lg:col-span-8 relative aspect-video overflow-hidden bg-slate-950">
                                    <img
                                        src={spotlightVideo.thumbnail}
                                        alt={spotlightVideo.title}
                                        className="w-full h-full object-cover"
                                    />
                                    <div className="absolute inset-0 flex items-center justify-center">
                                        <div className="w-14 h-14 rounded-full bg-blue-900/90 text-white flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                                            <Play className="w-6 h-6 fill-white ml-0.5" />
                                        </div>
                                    </div>
                                    <div className="absolute bottom-3 right-3 px-2 py-0.5 rounded bg-black/80 text-xs font-mono text-white">
                                        {spotlightVideo.duration}
                                    </div>
                                </div>

                                <div className="lg:col-span-4 p-5 sm:p-6 flex flex-col justify-between space-y-3">
                                    <div className="space-y-2.5">
                                        <div className="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                            <span className="font-semibold text-blue-900 dark:text-blue-300">
                                                {spotlightVideo.category}
                                            </span>
                                            <span>•</span>
                                            <span className="flex items-center gap-1">
                                                <MapPin className="w-3.5 h-3.5" />
                                                {spotlightVideo.city}
                                            </span>
                                        </div>

                                        <h2 className="text-lg sm:text-xl font-bold text-blue-950 dark:text-white leading-snug">
                                            {spotlightVideo.title}
                                        </h2>

                                        <p className="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                            {spotlightVideo.description}
                                        </p>

                                        {spotlightVideo.speakers && (
                                            <div className="pt-1 text-xs">
                                                <span className="text-slate-400">Narasumber: </span>
                                                <span className="text-slate-700 dark:text-slate-200">
                                                    {spotlightVideo.speakers.join(', ')}
                                                </span>
                                            </div>
                                        )}
                                    </div>

                                    <div className="pt-3 border-t border-slate-100 dark:border-blue-950 flex items-center justify-between text-xs">
                                        <span className="text-slate-500">
                                            {spotlightVideo.views} kali ditonton
                                        </span>
                                        <button
                                            type="button"
                                            className="font-semibold text-blue-900 dark:text-blue-300 flex items-center gap-1"
                                        >
                                            <Play className="w-3 h-3 fill-current" />
                                            <span>Putar Video</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}

                {/* VIDEOS GRID */}
                <div>
                    <div className="flex items-center justify-between mb-5">
                        <h3 className="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-200">
                            Daftar Video Dokumentasi ({filteredVideos.length})
                        </h3>
                    </div>

                    {filteredVideos.length === 0 ? (
                        <div className="text-center py-12 bg-white dark:bg-[#0c183a] rounded-2xl border border-slate-200 dark:border-blue-900 p-8 space-y-2">
                            <VideoIcon className="w-8 h-8 text-slate-400 mx-auto" />
                            <h4 className="text-sm font-bold text-blue-950 dark:text-white">
                                Tidak ada video yang cocok
                            </h4>
                            <p className="text-xs text-slate-500">
                                Coba ganti kata kunci pencarian atau pilih kategori lain.
                            </p>
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {filteredVideos.map((video) => (
                                <div
                                    key={video.id}
                                    onClick={() => setActiveVideo(video)}
                                    className="group cursor-pointer flex flex-col rounded-2xl bg-white dark:bg-[#0c183a] border border-slate-200 dark:border-blue-900 overflow-hidden shadow-xs hover:border-blue-700 transition-colors"
                                >
                                    <div className="relative h-44 overflow-hidden bg-slate-950">
                                        <img
                                            src={video.thumbnail}
                                            alt={video.title}
                                            className="w-full h-full object-cover"
                                        />
                                        <div className="absolute inset-0 flex items-center justify-center">
                                            <div className="w-10 h-10 rounded-full bg-blue-900/90 text-white flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                                                <Play className="w-4 h-4 fill-white ml-0.5" />
                                            </div>
                                        </div>

                                        <div className="absolute bottom-2.5 left-2.5 text-xs text-white bg-black/60 px-2 py-0.5 rounded">
                                            {video.city}
                                        </div>

                                        <div className="absolute bottom-2.5 right-2.5 px-2 py-0.5 rounded bg-black/80 font-mono text-[10px] text-white">
                                            {video.duration}
                                        </div>
                                    </div>

                                    <div className="p-4 flex-1 flex flex-col justify-between space-y-2.5">
                                        <div className="space-y-1">
                                            <div className="text-[11px] text-slate-400">
                                                {video.category} • {video.views} tontonan
                                            </div>
                                            <h3 className="text-sm font-bold text-blue-950 dark:text-white group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors line-clamp-2">
                                                {video.title}
                                            </h3>
                                            <p className="text-xs text-slate-500 line-clamp-2">
                                                {video.description}
                                            </p>
                                        </div>

                                        <div className="pt-2.5 border-t border-slate-100 dark:border-blue-950 flex items-center justify-between text-xs">
                                            <span className="text-[11px] text-slate-400 truncate max-w-[170px]">
                                                {video.exhibitionName}
                                            </span>
                                            <span className="font-semibold text-blue-900 dark:text-blue-300 flex items-center gap-1">
                                                <span>Tonton</span>
                                                <Play className="w-3 h-3 fill-current" />
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </div>

            {/* VIDEO PLAYER MODAL */}
            {activeVideo && (
                <div
                    className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-xs animate-in fade-in duration-200"
                    onClick={() => setActiveVideo(null)}
                >
                    <div
                        className="w-full max-w-3xl bg-slate-900 rounded-2xl overflow-hidden shadow-2xl border border-blue-900"
                        onClick={(e) => e.stopPropagation()}
                    >
                        <div className="relative aspect-video bg-black flex items-center justify-center">
                            <iframe
                                src={`https://www.youtube.com/embed/${activeVideo.youtubeId || 'dQw4w9WgXcQ'}?autoplay=1`}
                                title={activeVideo.title}
                                className="w-full h-full border-0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowFullScreen
                            />
                        </div>

                        <div className="p-5 space-y-3 bg-slate-950 text-white">
                            <div className="flex items-center justify-between text-xs">
                                <div className="text-blue-300">
                                    {activeVideo.category} • {activeVideo.city} • {activeVideo.duration}
                                </div>
                                <div className="flex items-center gap-2">
                                    <button
                                        type="button"
                                        onClick={handleCopyShare}
                                        className="p-1 rounded bg-blue-950 hover:bg-blue-900 text-slate-300 hover:text-white"
                                        title="Bagikan Tautan"
                                    >
                                        {copiedShare ? (
                                            <CheckCircle2 className="w-3.5 h-3.5 text-blue-300" />
                                        ) : (
                                            <Share2 className="w-3.5 h-3.5" />
                                        )}
                                    </button>
                                    <button
                                        type="button"
                                        onClick={() => setActiveVideo(null)}
                                        className="p-1 rounded bg-blue-950 hover:bg-blue-900 text-slate-400 hover:text-white"
                                    >
                                        <X className="w-4 h-4" />
                                    </button>
                                </div>
                            </div>

                            <h3 className="text-base sm:text-lg font-bold">
                                {activeVideo.title}
                            </h3>

                            <p className="text-xs text-slate-300 leading-relaxed">
                                {activeVideo.description}
                            </p>

                            <div className="pt-2 border-t border-blue-950 flex items-center justify-between text-xs">
                                <span className="text-slate-400">
                                    Acara: <span className="text-white font-medium">{activeVideo.exhibitionName}</span>
                                </span>
                                <Link
                                    href="/flyer"
                                    onClick={() => setActiveVideo(null)}
                                    className="text-blue-300 hover:underline flex items-center gap-1"
                                >
                                    <span>Buka Brosur Acara</span>
                                    <ExternalLink className="w-3 h-3" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </PublicLayout>
    );
}
