<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use Illuminate\Database\Seeder;

/**
 * The six real case studies, under the slugs Google already knows.
 *
 * These are the targets of PortfolioController::LEGACY_SLUGS, so seeding them
 * also brings the old placeholder addresses back to a 301 instead of a 404.
 * Matched on slug, so running it again refreshes the rows instead of
 * duplicating them. The three "Demo — …" records from DatabaseSeeder are
 * soft-deleted: they would otherwise sit in the listing beside the real work.
 *
 * Running it again overwrites whatever was edited in the admin for these six
 * records, so once the copy has been reviewed there, leave it alone.
 *
 *   php artisan db:seed --class=RealPortfolioSeeder --force
 */
class RealPortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $categories = PortfolioCategory::pluck('id', 'slug');

        foreach ($this->portfolios() as $index => $item) {
            $categorySlug = $item['category'];
            $english = $item['en'];
            unset($item['category'], $item['en']);

            $portfolio = Portfolio::withTrashed()->firstOrNew(['slug' => $item['slug']]);
            $portfolio->fill($item + [
                'category_id' => $categories[$categorySlug] ?? null,
                'is_featured' => $index < 3,
                'is_published' => true,
                'published_at' => $portfolio->published_at ?? now(),
                'sort_order' => $index + 1,
            ]);
            $portfolio->setTranslations('en', $english);
            $portfolio->deleted_at = null;
            $portfolio->save();
        }

        Portfolio::where('slug', 'like', 'demo-%')->delete();

        $this->command?->info('Real portfolios seeded: ' . count($this->portfolios()));
    }

    /** One paragraph per entry, rendered with nl2br on the case study page. */
    private function text(string ...$paragraphs): string
    {
        return implode("\n\n", $paragraphs);
    }

    private function portfolios(): array
    {
        return [
            [
                'slug' => 'arahinn-superapps',
                'title' => 'ArahInn SuperApps — OTA & Travel Platform',
                'client' => 'PT Arahinn Digital Nusantara',
                'category' => 'mobile-app',
                'featured_image' => 'images/portfolio/arahinn-mobile.webp',
                'project_url' => 'https://arahinn.com',
                'technologies' => ['Laravel API', 'React + Vite', 'React Native / Expo', 'MySQL', 'Midtrans Payment Gateway'],
                'short_description' => 'Super-app perjalanan Indonesia: pemesanan akomodasi, tiket pesawat dan kapal Pelni, serta pembayaran tagihan PPOB dalam satu akun, dengan pembayaran multi-channel otomatis dan program poin loyalitas bertingkat.',
                'description' => $this->text(
                    'ArahInn adalah platform Online Travel Agent yang menyatukan pemesanan akomodasi, tiket transportasi, dan pembayaran tagihan harian dalam satu ekosistem. Pelanggan memakai aplikasi Android/iOS dan situs arahinn.com, pemilik properti mengelola kamar lewat extranet dan aplikasi My ArahInn, sementara tim internal bekerja dari panel manajemen terpisah.',
                    'Semua kanal dilayani satu backend Laravel, sehingga harga, stok kamar, status pembayaran, dan poin pelanggan selalu sama di web maupun aplikasi.'
                ),
                'challenge' => $this->text(
                    'Pelanggan harus berpindah aplikasi untuk memesan hotel, membeli tiket, dan membayar tagihan, sementara pemilik penginapan kecil belum punya kanal penjualan online yang terjangkau.',
                    'Platform harus menghubungkan banyak pemasok berbeda — pemasok hotel, maskapai, Pelni, dan dua pemasok PPOB — yang masing-masing punya format data, kode status, dan callback sendiri, tanpa membuat pengalaman pelanggan terasa terpecah.'
                ),
                'approach' => $this->text(
                    'Kami membangun satu API pusat yang menjadi sumber kebenaran untuk seluruh kanal, lalu menambahkan lapisan adaptor per pemasok sehingga perbedaan format vendor tidak bocor ke aplikasi.',
                    'Aplikasi pelanggan dan pemilik dibangun dengan React Native (Expo) agar satu basis kode melayani Android dan iOS. Fitur dirilis bertahap per modul — akomodasi lebih dulu, disusul travel, PPOB, lalu loyalitas — supaya setiap modul bisa diuji di produksi sebelum modul berikutnya.'
                ),
                'solution' => $this->text(
                    'Pemesanan akomodasi dengan inventori kamar real-time, pencarian berdasarkan lokasi terdekat, dan informasi destinasi di sekitar properti.',
                    'Tiket pesawat dan kapal Pelni langsung dari pemasok, serta pembayaran pulsa, data, listrik, BPJS, e-toll, dan berbagai tagihan lain lewat dua pemasok PPOB dengan pencocokan callback otomatis.',
                    'Pembayaran multi-channel melalui Midtrans, poin loyalitas bertingkat Silver/Gold/Platinum dengan referral, promo dan kampanye yang bisa diikuti pemilik properti, live chat dengan tim layanan, serta extranet lengkap bagi pemilik untuk mengatur kamar, harga, dan pesanan.'
                ),
                'results' => $this->text(
                    'Pelanggan cukup memakai satu akun untuk menginap, bepergian, dan membayar tagihan, di web maupun aplikasi Android dan iOS.',
                    'Pemilik properti mendapat kanal penjualan online beserta laporan pendapatan yang transparan, dan tim internal memantau transaksi semua modul dari satu panel manajemen dengan login pengelola yang dilindungi autentikasi dua faktor.'
                ),
                'en' => [
                    'title' => 'ArahInn SuperApps — OTA & Travel Platform',
                    'short_description' => 'An Indonesian travel super-app: accommodation booking, flight and Pelni ferry tickets, and bill payments under one account, with automated multi-channel payments and a tiered loyalty programme.',
                    'description' => $this->text(
                        'ArahInn is an Online Travel Agent platform that brings accommodation booking, transport tickets, and everyday bill payments into one ecosystem. Customers use the Android/iOS app and arahinn.com, property owners manage their rooms through an extranet and the My ArahInn app, and the internal team works from a separate management panel.',
                        'Every channel is served by a single Laravel backend, so prices, room availability, payment status, and customer points are always the same on the web and in the apps.'
                    ),
                    'challenge' => $this->text(
                        'Customers had to switch between apps to book a hotel, buy a ticket, and pay a bill, while small property owners had no affordable online sales channel.',
                        'The platform had to connect many different suppliers — a hotel supplier, airlines, Pelni, and two bill-payment providers — each with its own data format, status codes, and callbacks, without the customer experience feeling fragmented.'
                    ),
                    'approach' => $this->text(
                        'We built one central API as the source of truth for every channel, with an adapter layer per supplier so vendor differences never leak into the apps.',
                        'The customer and owner apps are built with React Native (Expo), so one codebase serves Android and iOS. Features shipped module by module — accommodation first, then travel, bill payments, and loyalty — so each could be proven in production before the next.'
                    ),
                    'solution' => $this->text(
                        'Accommodation booking with real-time room inventory, nearby-location search, and information on destinations around each property.',
                        'Flight and Pelni ferry tickets straight from the suppliers, plus mobile credit, data, electricity, BPJS, e-toll, and other bills through two bill-payment providers with automatic callback matching.',
                        'Multi-channel payments through Midtrans, a Silver/Gold/Platinum loyalty programme with referrals, promotions and campaigns that owners can opt into, live chat with the service team, and a full extranet for owners to manage rooms, rates, and orders.'
                    ),
                    'results' => $this->text(
                        'Customers use one account to stay, travel, and pay bills, on the web and in the Android and iOS apps.',
                        'Property owners gain an online sales channel with transparent earnings reports, and the internal team monitors every module from one management panel, with staff logins protected by two-factor authentication.'
                    ),
                ],
            ],
            [
                'slug' => 'bamboe-oerip-villabox',
                'title' => 'Bamboe Oerip VillaBox — Booking Engine & Hospitality OTA',
                'client' => 'Bamboe Oerip Hospitality Group',
                'category' => 'web-application',
                'featured_image' => 'images/portfolio/bamboe-oerip.webp',
                'technologies' => ['Laravel 11', 'Vue.js 3', 'Tailwind CSS', 'MySQL', 'WhatsApp Business API'],
                'short_description' => 'Sistem reservasi dan manajemen hospitality digital berbasis web dengan dynamic pricing engine, kalender okupansi interaktif, automated WhatsApp billing invoice, dan integrasi channel manager.',
                'description' => $this->text(
                    'VillaBox adalah booking engine dan sistem manajemen properti untuk unit-unit villa Bamboe Oerip. Tamu memesan langsung dari situs, sementara tim operasional mengelola ketersediaan, harga, dan tagihan dari satu dasbor.',
                    'Dengan reservasi langsung, properti tidak sepenuhnya bergantung pada OTA pihak ketiga dan komisinya.'
                ),
                'challenge' => $this->text(
                    'Reservasi sebelumnya dicatat manual dari berbagai sumber — telepon, WhatsApp, dan OTA — sehingga rawan double booking dan sulit dipantau.',
                    'Harga belum menyesuaikan musim dan tingkat hunian, dan penagihan ke tamu masih dikirim satu per satu secara manual.'
                ),
                'approach' => $this->text(
                    'Kami memetakan alur kerja tim reservasi dari pertanyaan tamu sampai check-out, lalu menjadikan kalender okupansi sebagai pusat sistem: setiap pemesanan dari kanal mana pun mengunci tanggal yang sama.',
                    'Aturan harga dibuat dapat diatur sendiri oleh pengelola tanpa perlu bantuan developer.'
                ),
                'solution' => $this->text(
                    'Booking engine di situs dengan ketersediaan real-time dan kalender okupansi interaktif per unit.',
                    'Dynamic pricing berdasarkan musim, hari, dan tingkat hunian; invoice dan pengingat pembayaran yang terkirim otomatis lewat WhatsApp Business API; serta integrasi channel manager agar ketersediaan selaras dengan OTA lain.'
                ),
                'results' => $this->text(
                    'Ketersediaan di semua kanal kini mengacu pada satu kalender, sehingga risiko double booking turun drastis.',
                    'Tim tidak lagi menyusun tagihan satu per satu, dan pengelola dapat mengubah harga musiman sendiri kapan saja.'
                ),
                'en' => [
                    'title' => 'Bamboe Oerip VillaBox — Booking Engine & Hospitality OTA',
                    'short_description' => 'A web-based reservation and hospitality management system with dynamic pricing, an interactive occupancy calendar, automated WhatsApp invoicing, and channel manager integration.',
                    'description' => $this->text(
                        'VillaBox is the booking engine and property management system for the Bamboe Oerip villas. Guests book directly on the website, while the operations team manages availability, rates, and billing from one dashboard.',
                        'Direct reservations mean the property no longer depends entirely on third-party OTAs and their commissions.'
                    ),
                    'challenge' => $this->text(
                        'Reservations used to be recorded by hand from several sources — phone, WhatsApp, and OTAs — which invited double bookings and made them hard to track.',
                        'Rates did not follow the season or occupancy, and guest invoices were still sent one at a time.'
                    ),
                    'approach' => $this->text(
                        'We mapped the reservation team\'s workflow from first enquiry to check-out, then made the occupancy calendar the centre of the system: a booking from any channel locks the same dates.',
                        'Pricing rules were made editable by the managers themselves, with no developer needed.'
                    ),
                    'solution' => $this->text(
                        'An on-site booking engine with real-time availability and an interactive occupancy calendar per unit.',
                        'Dynamic pricing by season, day, and occupancy; invoices and payment reminders sent automatically through the WhatsApp Business API; and channel manager integration to keep availability in step with other OTAs.'
                    ),
                    'results' => $this->text(
                        'Availability on every channel now comes from one calendar, sharply reducing the risk of double bookings.',
                        'The team no longer prepares invoices one by one, and managers can change seasonal rates themselves at any time.'
                    ),
                ],
            ],
            [
                'slug' => 'sistem-pos-multi-cabang-aldef-tech',
                'title' => 'Sistem POS Multi-Cabang Aldef Tech',
                'client' => 'Aldef Enterprise Retail',
                'category' => 'business-system',
                'featured_image' => 'images/portfolio/aldeftech-pos.webp',
                'technologies' => ['Laravel', 'Electron / PWA', 'PostgreSQL', 'Thermal Printing', 'WebSockets'],
                'short_description' => 'Platform Point of Sale (POS) cloud omnichannel berkecepatan tinggi dengan sinkronisasi inventori multi-cabang, barcode scanning offline-ready, audit kasir real-time, dan analitik performa laba-rugi.',
                'description' => $this->text(
                    'Aldef POS adalah sistem kasir berbasis cloud untuk bisnis ritel dengan banyak cabang. Setiap outlet bertransaksi di aplikasi kasir, sementara kantor pusat melihat stok, penjualan, dan laba seluruh cabang secara langsung.',
                    'Aplikasi kasir tetap bisa dipakai saat internet terputus dan akan menyinkronkan transaksi begitu koneksi kembali.'
                ),
                'challenge' => $this->text(
                    'Setiap cabang memakai pencatatan sendiri, sehingga stok pusat dan cabang sering berbeda dan laporan baru bisa disusun berhari-hari kemudian.',
                    'Koneksi internet di beberapa outlet tidak stabil, padahal antrean kasir tidak boleh berhenti hanya karena jaringan putus.'
                ),
                'approach' => $this->text(
                    'Kami merancang aplikasi kasir dengan prinsip offline-first: transaksi disimpan lokal lebih dulu, lalu dikirim ke server pusat dengan sinkronisasi yang aman dari duplikasi.',
                    'Perubahan stok disiarkan ke semua cabang melalui WebSockets agar data persediaan selalu mutakhir.'
                ),
                'solution' => $this->text(
                    'Aplikasi kasir desktop/PWA dengan pemindaian barcode, cetak struk printer thermal, dan mode offline.',
                    'Sinkronisasi inventori antar cabang, transfer stok, audit kasir dan rekap shift secara real-time, serta dasbor laba-rugi per cabang dan per produk untuk manajemen.'
                ),
                'results' => $this->text(
                    'Stok pusat dan cabang kini selaras secara otomatis, dan laporan penjualan tersedia saat itu juga tanpa rekap manual.',
                    'Kasir tetap melayani pembeli meski jaringan terputus, dan selisih kas lebih cepat terdeteksi lewat audit per shift.'
                ),
                'en' => [
                    'title' => 'Aldef Tech Multi-Branch POS System',
                    'short_description' => 'A fast omnichannel cloud Point of Sale platform with multi-branch inventory sync, offline-ready barcode scanning, real-time cashier audits, and profit-and-loss analytics.',
                    'description' => $this->text(
                        'Aldef POS is a cloud point-of-sale system for retailers with many branches. Each outlet sells through the cashier app, while head office sees stock, sales, and profit across every branch as they happen.',
                        'The cashier app keeps working when the internet drops and syncs its transactions as soon as the connection returns.'
                    ),
                    'challenge' => $this->text(
                        'Each branch kept its own records, so head-office and branch stock often disagreed and reports took days to compile.',
                        'Some outlets had unreliable internet, yet the checkout queue could not stop just because the network did.'
                    ),
                    'approach' => $this->text(
                        'We designed the cashier app offline-first: transactions are stored locally, then sent to the central server through a sync that is safe against duplicates.',
                        'Stock changes are broadcast to every branch over WebSockets so inventory is always current.'
                    ),
                    'solution' => $this->text(
                        'A desktop/PWA cashier app with barcode scanning, thermal receipt printing, and offline mode.',
                        'Inventory sync between branches, stock transfers, real-time cashier audits and shift summaries, and a profit-and-loss dashboard by branch and by product for management.'
                    ),
                    'results' => $this->text(
                        'Head-office and branch stock now stay in step automatically, and sales reports are available immediately with no manual tallying.',
                        'Cashiers keep serving customers through network outages, and cash discrepancies surface sooner through per-shift audits.'
                    ),
                ],
            ],
            [
                'slug' => 'absensi-aldef-tech',
                'title' => 'Absensi Aldef Tech — Biometric & Geofencing HRIS',
                'client' => 'Enterprise Workforce Management',
                'category' => 'mobile-app',
                'featured_image' => 'images/portfolio/absensi.webp',
                'technologies' => ['Laravel 11 API', 'Flutter Mobile', 'AI Face Recognition', 'PostGIS Geofencing', 'PostgreSQL'],
                'short_description' => 'Sistem presensi karyawan cerdas berbasis AI Face Recognition biometrik dan validasi radius GPS (lock location) anti-fake GPS, terintegrasi otomatis dengan payroll dan manajemen shift multi-cabang.',
                'description' => $this->text(
                    'Absensi Aldef Tech adalah aplikasi presensi karyawan yang memverifikasi dua hal sekaligus: siapa yang absen, lewat pengenalan wajah, dan di mana ia berada, lewat radius lokasi kantor.',
                    'Data kehadiran mengalir langsung ke pengaturan shift dan perhitungan gaji, sehingga HR tidak perlu merekap ulang.'
                ),
                'challenge' => $this->text(
                    'Mesin absensi konvensional mudah dititipkan, sementara aplikasi absensi biasa bisa diakali dengan aplikasi fake GPS.',
                    'Perusahaan dengan banyak cabang dan pola shift berbeda juga kesulitan menghitung keterlambatan dan lembur secara konsisten.'
                ),
                'approach' => $this->text(
                    'Kami menggabungkan verifikasi wajah biometrik dengan geofencing per lokasi kerja, ditambah deteksi lokasi palsu di perangkat.',
                    'Aturan shift, toleransi keterlambatan, dan lembur dibuat dapat diatur per cabang, sehingga satu sistem melayani banyak pola kerja.'
                ),
                'solution' => $this->text(
                    'Aplikasi mobile untuk absen masuk dan pulang dengan selfie yang diverifikasi AI Face Recognition, dalam radius lokasi yang dikunci per kantor.',
                    'Deteksi fake GPS, manajemen shift multi-cabang, pengajuan izin dan cuti, serta rekap kehadiran yang terhubung otomatis ke payroll.'
                ),
                'results' => $this->text(
                    'Titip absen dan manipulasi lokasi tertutup, karena setiap presensi terikat pada wajah dan lokasi karyawan yang sebenarnya.',
                    'Rekap kehadiran untuk penggajian tersusun otomatis, dan atasan bisa memantau kehadiran tim di semua cabang secara langsung.'
                ),
                'en' => [
                    'title' => 'Aldef Tech Attendance — Biometric & Geofencing HRIS',
                    'short_description' => 'Smart employee attendance using biometric AI face recognition and GPS radius lock with fake-GPS detection, wired straight into payroll and multi-branch shift management.',
                    'description' => $this->text(
                        'Aldef Tech Attendance is an employee attendance app that verifies two things at once: who is clocking in, through face recognition, and where they are, through a radius around the workplace.',
                        'Attendance data flows straight into shift scheduling and payroll, so HR never has to re-tally it.'
                    ),
                    'challenge' => $this->text(
                        'Conventional time clocks are easy to clock in for someone else, and ordinary attendance apps can be fooled with fake-GPS apps.',
                        'Companies with many branches and different shift patterns also struggled to calculate lateness and overtime consistently.'
                    ),
                    'approach' => $this->text(
                        'We combined biometric face verification with geofencing per workplace, plus on-device detection of spoofed locations.',
                        'Shift rules, lateness tolerance, and overtime are configurable per branch, so one system serves many working patterns.'
                    ),
                    'solution' => $this->text(
                        'A mobile app for clocking in and out with a selfie verified by AI face recognition, within a radius locked to each office.',
                        'Fake-GPS detection, multi-branch shift management, leave and permission requests, and attendance summaries linked automatically to payroll.'
                    ),
                    'results' => $this->text(
                        'Proxy clock-ins and location spoofing are shut out, because every check-in is tied to the employee\'s real face and location.',
                        'Attendance summaries for payroll are compiled automatically, and managers can follow team attendance across every branch as it happens.'
                    ),
                ],
            ],
            [
                'slug' => 'aplikasi-penyimpanan-drive-aldef-tech',
                'title' => 'Aldef Drive — Aplikasi Penyimpanan Multi-Tenant',
                'client' => 'Multi-Enterprise Storage Solution',
                'category' => 'saas',
                'featured_image' => 'images/portfolio/drive.webp',
                'technologies' => ['Laravel 11', 'Vue.js 3', 'S3 Object Storage', 'Multi-Tenant SaaS', 'Tailwind CSS'],
                'short_description' => 'Platform cloud storage multi-perusahaan (multi-tenant) berkecepatan tinggi dengan antarmuka modern drag-and-drop, enkripsi berkas end-to-end, kolaborasi izin akses folder, dan audit log keamanan terpusat.',
                'description' => $this->text(
                    'Aldef Drive adalah layanan penyimpanan berkas berbasis cloud untuk perusahaan. Setiap perusahaan mendapat ruang kerja terpisah dengan pengguna, folder, dan kebijakan aksesnya sendiri, di atas satu platform bersama.',
                    'Tersedia di web dan aplikasi Android, sehingga dokumen kerja dapat diakses dari kantor maupun lapangan.'
                ),
                'challenge' => $this->text(
                    'Dokumen perusahaan tersebar di laptop pribadi, flashdisk, dan layanan penyimpanan konsumen tanpa kendali akses yang jelas.',
                    'Saat ada kebocoran atau berkas hilang, tidak ada catatan siapa yang mengakses atau mengubah dokumen tersebut.'
                ),
                'approach' => $this->text(
                    'Kami membangun arsitektur multi-tenant yang memisahkan data setiap perusahaan secara ketat, dengan berkas disimpan di object storage yang kompatibel S3.',
                    'Keamanan dirancang sejak awal: enkripsi berkas, izin akses per folder, dan audit log untuk setiap aksi penting.'
                ),
                'solution' => $this->text(
                    'Antarmuka drag-and-drop untuk unggah, pratinjau, dan berbagi berkas, dengan izin akses folder per pengguna atau per tim.',
                    'Enkripsi berkas, audit log keamanan terpusat, kuota penyimpanan per perusahaan, serta panel admin untuk mengelola pengguna di setiap ruang kerja.'
                ),
                'results' => $this->text(
                    'Dokumen perusahaan kini tersimpan di satu tempat dengan hak akses yang jelas.',
                    'Setiap pengunduhan, perubahan, dan pembagian berkas tercatat, sehingga perusahaan dapat menelusuri aktivitas dokumen kapan pun dibutuhkan.'
                ),
                'en' => [
                    'title' => 'Aldef Drive — Multi-Tenant Storage App',
                    'short_description' => 'A fast multi-tenant cloud storage platform with a modern drag-and-drop interface, end-to-end file encryption, shared folder permissions, and a central security audit log.',
                    'description' => $this->text(
                        'Aldef Drive is a cloud file storage service for companies. Each company gets its own isolated workspace with its own users, folders, and access policies, on one shared platform.',
                        'It is available on the web and as an Android app, so work documents are reachable from the office and from the field.'
                    ),
                    'challenge' => $this->text(
                        'Company documents were scattered across personal laptops, flash drives, and consumer storage services with no clear access control.',
                        'When a file leaked or went missing, there was no record of who had opened or changed it.'
                    ),
                    'approach' => $this->text(
                        'We built a multi-tenant architecture that strictly separates each company\'s data, with files kept in S3-compatible object storage.',
                        'Security was designed in from the start: file encryption, per-folder permissions, and an audit log for every significant action.'
                    ),
                    'solution' => $this->text(
                        'A drag-and-drop interface for uploading, previewing, and sharing files, with folder permissions per user or per team.',
                        'File encryption, a central security audit log, storage quotas per company, and an admin panel for managing the users in each workspace.'
                    ),
                    'results' => $this->text(
                        'Company documents now live in one place with clear access rights.',
                        'Every download, change, and share is recorded, so a company can trace document activity whenever it needs to.'
                    ),
                ],
            ],
            [
                'slug' => 'touring-aldef-tech',
                'title' => 'ALDEF Touring — Aplikasi Komunitas Touring Motor',
                'client' => 'Komunitas Motor & Rider Federation',
                'category' => 'mobile-app',
                'featured_image' => 'images/portfolio/touring.webp',
                'technologies' => ['Laravel 11', 'React Native / Expo', 'Leaflet / OpenStreetMap', 'MySQL', 'REST API'],
                'short_description' => 'Aplikasi komunitas touring motor dengan pelacakan posisi rombongan secara langsung, titik kumpul dan rute di peta, sinyal darurat SOS, papan peringkat rider, serta pengelolaan chapter dan anggota komunitas.',
                'description' => $this->text(
                    'ALDEF Touring adalah ekosistem digital untuk komunitas touring motor: aplikasi mobile bagi rider dan dasbor web bagi pengurus komunitas.',
                    'Selama perjalanan, posisi setiap rider tampil di peta bersama titik kumpul, rute, dan tujuan, sehingga rombongan tetap terkoordinasi meski terpencar.'
                ),
                'challenge' => $this->text(
                    'Saat touring, rombongan sering terpisah di jalan dan koordinasi hanya mengandalkan grup chat dan telepon yang sulit dipakai sambil berkendara.',
                    'Pengurus juga kesulitan mendata anggota dari banyak chapter dan memastikan hanya anggota resmi yang ikut perjalanan.'
                ),
                'approach' => $this->text(
                    'Kami membangun satu backend Laravel yang melayani aplikasi rider dan dasbor pengurus, dengan pembaruan posisi berkala yang hemat baterai dan kuota.',
                    'Struktur komunitas dimodelkan apa adanya — chapter, ketua chapter, dan anggota — dengan kode komunitas yang dibuat sistem agar pendaftaran anggota tetap terkendali.'
                ),
                'solution' => $this->text(
                    'Peta perjalanan langsung berbasis OpenStreetMap yang menampilkan posisi rider, titik kumpul, rute, dan tujuan, beserta tombol SOS darurat yang langsung memberi tahu rombongan.',
                    'Pendaftaran rider dengan verifikasi email, pengelolaan chapter dan anggota, papan peringkat rider, serta dasbor pengurus untuk menyusun agenda touring dan memantau perjalanan.'
                ),
                'results' => $this->text(
                    'Rombongan dapat saling memantau posisi tanpa harus berhenti untuk menelepon, dan keadaan darurat di jalan segera diketahui seluruh peserta.',
                    'Pengurus mengelola anggota dari berbagai chapter dalam satu dasbor, dengan pendaftaran yang terverifikasi.'
                ),
                'en' => [
                    'title' => 'ALDEF Touring — Motorcycle Touring Community App',
                    'short_description' => 'A motorcycle touring community app with live group tracking, meeting points and routes on the map, SOS alerts, a rider leaderboard, and management of community chapters and members.',
                    'description' => $this->text(
                        'ALDEF Touring is a digital ecosystem for motorcycle touring communities: a mobile app for riders and a web dashboard for community organisers.',
                        'During a ride, each rider\'s position appears on the map alongside meeting points, the route, and the destination, keeping the group coordinated even when it spreads out.'
                    ),
                    'challenge' => $this->text(
                        'On a tour, groups often split up on the road, and coordination relied on group chats and phone calls that are hard to use while riding.',
                        'Organisers also struggled to keep track of members across many chapters and to make sure only registered members joined a ride.'
                    ),
                    'approach' => $this->text(
                        'We built one Laravel backend serving both the rider app and the organiser dashboard, with periodic position updates that go easy on battery and data.',
                        'The community is modelled as it really is — chapters, chapter leads, and members — with system-generated community codes so member sign-ups stay under control.'
                    ),
                    'solution' => $this->text(
                        'A live ride map on OpenStreetMap showing rider positions, meeting points, the route, and the destination, with an SOS button that alerts the whole group at once.',
                        'Rider registration with email verification, chapter and member management, a rider leaderboard, and an organiser dashboard for planning tours and following them live.'
                    ),
                    'results' => $this->text(
                        'Riders can follow each other\'s positions without stopping to call, and any emergency on the road reaches every participant right away.',
                        'Organisers manage members from every chapter in one dashboard, with verified sign-ups.'
                    ),
                ],
            ],
        ];
    }
}
