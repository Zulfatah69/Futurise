import Image from "next/image";
import Link from "next/link";

function getBackendBaseUrl(): string {
  if (process.env.NEXT_PUBLIC_BACKEND_URL) {
    return process.env.NEXT_PUBLIC_BACKEND_URL.replace(/\/+$/, "");
  }

  const apiUrl = process.env.NEXT_PUBLIC_API_URL;
  if (apiUrl) {
    try {
      return new URL(apiUrl).origin;
    } catch {
      // Abaikan jika format URL relatif/tidak valid dan gunakan fallback lokal.
    }
  }

  return "http://localhost:8000";
}

// 1. DATA DUMMY BERITA (Nanti bisa diganti dari API Laravel)
const newsList = [
  {
    id: 1,
    category: "Berita Utama",
    date: "20 November 2026",
    title: "Persiapan Menuju Futurise 2027: Kolaborasi Kampus dan Industri",
    excerpt:
      "APTIKOM Jawa Barat kembali menggelar rangkaian roadshow inovasi digital untuk menjembatani riset mahasiswa ke dunia industri.",
    image:
      "https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&q=80",
  },
  {
    id: 2,
    category: "Pengumuman",
    date: "15 November 2026",
    title: "Pendaftaran Webinar Futurise Connect Resmi Dibuka",
    excerpt:
      "Simak pembahasan strategi komersialisasi inovasi bersama para pakar akademik dan praktisi teknologi nasional.",
    image:
      "https://images.unsplash.com/photo-1515187029135-18ee286d815b?auto=format&fit=crop&w=800&q=80",
  },
  {
    id: 3,
    category: "Komunitas",
    date: "10 November 2026",
    title: "Diktisaintek Berdampak Dorong Hilirisasi Produk Digital Mahasiswa",
    excerpt:
      "Program inkubasi talenta digital Jawa Barat siap memfasilitasi karya inovatif agar memiliki nilai guna di pasar.",
    image:
      "https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80",
  },
];

// 2. DATA PROTOTYPE VIDEO YOUTUBE
const youtubeVideos = [
  {
    id: 1,
    title: "Aftermovie West Java Digital Innovation Festival",
    duration: "04:15",
    youtubeId: "mkL8rrLnClE",
  },
  {
    id: 2,
    title: "Teaser Road to Futurise 2027 - APTIKOM Jawa Barat",
    duration: "02:30",
    youtubeId: "2etn55SzCpo",
  },
  {
    id: 3,
    title: "Highlight Seminar Nasional Inovasi & Komersialisasi",
    duration: "12:45",
    youtubeId: "vYFfpaJRxOc",
  },
];

export default function HomePage() {
  const backendUrl = getBackendBaseUrl();

  return (
    <div className="min-h-screen bg-[#F4F7FA] text-slate-800 flex flex-col">
      {/* NAVBAR */}
      <header className="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-slate-200">
        <div className="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
          {/* Logo senada halaman Login */}
          <Link href="/" className="flex flex-col">
            <span className="text-2xl font-extrabold tracking-tight text-[#23275B] leading-none">
              FUTURI<span className="text-[#00B4D8]">SE</span>
            </span>
            <span className="text-[10px] text-slate-500 tracking-wide">
              Future Innovation And Creativity
            </span>
          </Link>

          {/* Menu Navigasi */}
          <nav className="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
            <a href="#event" className="hover:text-[#23275B] transition">
              Event
            </a>
            <a href="#berita" className="hover:text-[#23275B] transition">
              Informasi Berita
            </a>
            <a href="#video" className="hover:text-[#23275B] transition">
              Galeri Video
            </a>
          </nav>

          {/* Tombol Masuk mengarah ke Login */}
          <div className="flex items-center gap-3">
            <a
              href={`${backendUrl}/login`}
              className="px-5 py-2 rounded-lg bg-[#23275B] hover:bg-[#191c44] text-white text-sm font-semibold transition shadow-sm"
            >
              Masuk
            </a>
          </div>
        </div>
      </header>

      <main className="flex-1">
        {/* SECTION 1: HERO & GAMBAR EVENT */}
        <section id="event" className="py-12 sm:py-16 px-4 sm:px-6">
          <div className="max-w-6xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            {/* Garis aksen 2 warna di atas kartu seperti halaman Login */}
            <div className="h-1.5 w-full flex">
              <div className="w-[70%] bg-[#23275B]" />
              <div className="w-[30%] bg-[#00B4D8]" />
            </div>

            <div className="p-6 sm:p-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
              {/* Kolom Kiri: Poster Gambar Event */}
              <div className="lg:col-span-5 flex justify-center">
                <div className="w-full max-w-sm rounded-xl overflow-hidden shadow-md border border-slate-200 bg-[#0A194E]">
                  <Image
                    src="/images/poster.jpeg"
                    alt="Poster Event Futurise Connect"
                    width={1080}
                    height={1350}
                    sizes="(max-width: 1024px) 100vw, 384px"
                    fetchPriority="high"
                    loading="eager"
                    className="w-full h-auto object-cover"
                  />
                </div>
              </div>

              {/* Kolom Kanan: Informasi Event */}
              <div className="lg:col-span-7 space-y-5">
                <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold">
                  <span className="w-2 h-2 rounded-full bg-amber-500" />
                  THE ROAD TO FUTURISE 2027
                </div>

                <h1 className="text-3xl sm:text-4xl font-extrabold text-[#23275B] leading-tight">
                  FUTURISE CONNECT
                </h1>

                <p className="text-lg font-medium text-[#0096C7]">
                  From Innovation to Commercialization: Turning Ideas into Market Impact
                </p>

                <p className="text-slate-600 text-sm sm:text-base leading-relaxed">
                  Bergabunglah dalam sesi diskusi inspiratif bersama pengurus pusat APTIKOM
                  Indonesia serta para praktisi industri teknologi untuk membahas langkah nyata
                  mengubah ide inovasi menjadi dampak pasar.
                </p>

                {/* Box Jadwal */}
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                  <div className="p-4 rounded-xl bg-[#F4F7FA] border border-slate-200/70">
                    <p className="text-xs text-slate-500 font-medium">Waktu Pelaksanaan</p>
                    <p className="text-sm font-bold text-[#23275B] mt-0.5">
                      Jumat, 27 November 2026
                    </p>
                    <p className="text-xs text-slate-600">08:00 – 10:00 WIB</p>
                  </div>
                  <div className="p-4 rounded-xl bg-[#F4F7FA] border border-slate-200/70">
                    <p className="text-xs text-slate-500 font-medium">Lokasi & Narahubung</p>
                    <p className="text-sm font-bold text-[#23275B] mt-0.5">Online via Zoom</p>
                    <p className="text-xs text-slate-600">Info: Yessica (0822-1120-1345)</p>
                  </div>
                </div>

                {/* Tombol Aksi */}
                <div className="pt-2 flex flex-wrap gap-3">
                  <a
                    href={`${backendUrl}/register`}
                    className="px-6 py-3 rounded-lg bg-[#23275B] hover:bg-[#191c44] text-white text-sm font-semibold transition"
                  >
                    Daftar Sekarang
                  </a>
                  <a
                    href="#video"
                    className="px-6 py-3 rounded-lg bg-white hover:bg-slate-50 text-[#23275B] border border-slate-300 text-sm font-semibold transition"
                  >
                    Tonton Teaser
                  </a>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* SECTION 2: INFORMASI BERITA */}
        <section id="berita" className="py-12 px-4 sm:px-6">
          <div className="max-w-6xl mx-auto">
            <div className="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-2">
              <div>
                <span className="text-xs font-bold uppercase tracking-wider text-[#00B4D8]">
                  Kabar Terbaru
                </span>
                <h2 className="text-2xl sm:text-3xl font-bold text-[#23275B] mt-1">
                  Informasi & Berita
                </h2>
              </div>
              <p className="text-sm text-slate-500">
                Update kegiatan dan pengumuman seputar ekosistem Futurise
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
              {newsList.map((item) => (
                <article
                  key={item.id}
                  className="bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col hover:shadow-md transition"
                >
                  <div className="h-1 w-full flex">
                    <div className="w-[70%] bg-[#23275B]" />
                    <div className="w-[30%] bg-[#00B4D8]" />
                  </div>

                  <div className="relative h-48 w-full overflow-hidden bg-slate-200">
                    <Image
                      src={item.image}
                      alt={item.title}
                      fill
                      sizes="(max-width: 768px) 100vw, 33vw"
                      loading="lazy"
                      className="object-cover hover:scale-105 transition duration-300"
                    />
                  </div>

                  <div className="p-5 flex-1 flex flex-col">
                    <div className="flex items-center justify-between text-xs mb-2.5">
                      <span className="px-2.5 py-0.5 rounded-md bg-sky-50 text-[#0096C7] font-semibold">
                        {item.category}
                      </span>
                      <span className="text-slate-400">{item.date}</span>
                    </div>

                    <h3 className="text-base font-bold text-[#23275B] line-clamp-2 mb-2">
                      {item.title}
                    </h3>

                    <p className="text-sm text-slate-600 line-clamp-3 flex-1">
                      {item.excerpt}
                    </p>

                    <div className="pt-4 mt-4 border-t border-slate-100">
                      <Link
                        href={`/berita/${item.id}`}
                        className="text-sm font-semibold text-[#0096C7] hover:text-[#23275B] inline-flex items-center gap-1 transition"
                      >
                        Baca Selengkapnya
                        <span aria-hidden="true">&rarr;</span>
                      </Link>
                    </div>
                  </div>
                </article>
              ))}
            </div>
          </div>
        </section>

        {/* SECTION 3: VIDEO YOUTUBE (KLIK LANGSUNG KE YOUTUBE) */}
        <section id="video" className="py-12 pb-20 px-4 sm:px-6">
          <div className="max-w-6xl mx-auto">
            <div className="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-2">
              <div>
                <span className="text-xs font-bold uppercase tracking-wider text-[#00B4D8]">
                  Dokumentasi & Publikasi
                </span>
                <h2 className="text-2xl sm:text-3xl font-bold text-[#23275B] mt-1">
                  Video Futurise
                </h2>
              </div>
              <a
                href="https://www.youtube.com"
                target="_blank"
                rel="noopener noreferrer"
                className="text-sm font-semibold text-[#23275B] hover:text-[#00B4D8] transition"
              >
                Kunjungi Channel YouTube &rarr;
              </a>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
              {youtubeVideos.map((video) => (
                <a
                  key={video.id}
                  href={`https://www.youtube.com/watch?v=${video.youtubeId}`}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="group bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden hover:shadow-md transition block"
                >
                  {/* Thumbnail Otomatis dari YouTube */}
                  <div className="relative aspect-video bg-slate-900 overflow-hidden">
                    <Image
                      src={`https://img.youtube.com/vi/${video.youtubeId}/hqdefault.jpg`}
                      alt={video.title}
                      fill
                      sizes="(max-width: 768px) 100vw, 33vw"
                      loading="lazy"
                      className="object-cover opacity-90 group-hover:scale-105 group-hover:opacity-100 transition duration-300"
                    />

                    {/* Overlay Gelap + Tombol Play YouTube */}
                    <div className="absolute inset-0 bg-black/25 group-hover:bg-black/15 transition flex items-center justify-center">
                      <div className="w-14 h-10 rounded-xl bg-red-600 group-hover:bg-red-500 text-white flex items-center justify-center shadow-lg transition transform group-hover:scale-110">
                        <svg
                          className="w-6 h-6 fill-current ml-0.5"
                          viewBox="0 0 24 24"
                        >
                          <path d="M8 5v14l11-7z" />
                        </svg>
                      </div>
                    </div>

                    {/* Badge Durasi */}
                    <span className="absolute bottom-2 right-2 px-2 py-0.5 rounded bg-black/80 text-white text-xs font-medium">
                      {video.duration}
                    </span>
                  </div>

                  {/* Judul Video */}
                  <div className="p-4">
                    <h3 className="text-sm font-bold text-[#23275B] group-hover:text-[#0096C7] transition line-clamp-2">
                      {video.title}
                    </h3>
                    <p className="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                      Tonton di YouTube &#8599;
                    </p>
                  </div>
                </a>
              ))}
            </div>
          </div>
        </section>
      </main>

      {/* FOOTER */}
      <footer className="bg-[#23275B] text-white py-8 px-4 sm:px-6">
        <div className="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 text-sm">
          <div>
            <span className="font-extrabold tracking-tight text-lg">
              FUTURI<span className="text-[#00B4D8]">SE</span>
            </span>
            <p className="text-slate-300 text-xs mt-1">
              West Java Digital Innovation Festival by APTIKOM Jawa Barat
            </p>
          </div>
          <p className="text-slate-400 text-xs">
            &copy; 2026 Futurise. All rights reserved.
          </p>
        </div>
      </footer>
    </div>
  );
}