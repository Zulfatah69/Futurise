export type ProjectCategory =
    | 'Semua Bidang'
    | 'Kecerdasan Buatan (AI) & Data'
    | 'IoT & Smart City'
    | 'HealthTech & Rekam Medis'
    | 'AgriTech & Ketahanan Pangan'
    | 'FinTech, Logistik & E-Gov'
    | 'EduTech & Aksesibilitas';

export interface StudentProject {
    id: string;
    title: string;
    campus: string;
    campusShort: string;
    city: string;
    boothNumber: string;
    category: ProjectCategory;
    problem: string;
    solution: string;
    readinessLevel: string; // TRL
    technology: string[];
    teamLead: string;
    advisor: string;
    image: string;
    isPitchFinalist?: boolean;
    featured?: boolean;
}

export interface NewsArticle {
    id: string;
    title: string;
    slug: string;
    category: 'Pendaftaran & Kurasi' | 'Agenda & Rundown' | 'Kemitraan Industri' | 'Teknis & Booth' | 'Pengumuman Resmi';
    publishedAt: string;
    readTime: string;
    author: {
        name: string;
        role: string;
        institution: string;
    };
    excerpt: string;
    content: string[];
    quote?: {
        text: string;
        author: string;
    };
    image: string;
    tags: string[];
    featured?: boolean;
}

export interface VideoItem {
    id: string;
    title: string;
    category: 'Teaser Resmi' | 'Wawancara & Sambutan' | 'Demo Karya' | 'Panduan Teknis' | 'Liputan';
    duration: string;
    views: string;
    publishedAt: string;
    speaker?: string;
    institution?: string;
    thumbnail: string;
    youtubeId?: string;
    featured?: boolean;
    description: string;
}

export interface FlyerItem {
    id: string;
    title: string;
    category: 'Poster Utama' | 'Panduan PIC Kampus' | 'Sponsorship & Mitra' | 'Rundown & Denah' | 'Edu-Tour Sekolah';
    targetAudience: string;
    fileFormat: string;
    fileSize: string;
    releaseDate: string;
    status: 'Tersedia Unduh' | 'Dokumen Resmi' | 'Pembaruan Terkini';
    image: string;
    description: string;
    highlights: string[];
    rundownPreview?: { time: string; session: string; location: string }[];
    contactPerson: string;
    contactEmail: string;
}

export interface EventProgram {
    id: number;
    title: string;
    subtitle: string;
    time: string;
    location: string;
    target: string;
    description: string;
    icon: string;
}
