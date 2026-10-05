# Analytics dan Lead Tracking Aldef Tech

## Prinsip implementasi

Situs memakai satu konfigurasi tracking utama. Jika "google_tag_manager_id"
terisi, GTM menjadi pemilik page view dan event; "google_analytics_id" tidak
dimuat sebagai gtag kedua. Jika GTM kosong, GA4 standalone memakai ID dari
pengaturan situs. Script vendor bersifat async dan helper tracking tidak
menghentikan navigasi jika vendor diblokir.

Attribution first-touch disimpan di localStorage pada browser dan disalin ke
field hidden formulir brief. Setelah tersimpan, field attribution juga masuk ke
record leads. Nilai yang dikirim ke GA4 hanya metadata non-PII: tidak ada
nama, email, WhatsApp, perusahaan, atau isi pesan.

## Event matrix

| Event | Trigger | Parameter utama | Conversion | Page/component |
| --- | --- | --- | --- | --- |
| generate_lead | Halaman thank-you setelah lead non-spam berhasil disimpan | lead_source, form_name, project_type, budget_range, page_path (URL form), page_title, language, campaign IDs | Lead | /contact/thank-you dan /en/contact/thank-you |
| whatsapp_click | Klik link WhatsApp | button_location, cta_location, cta_name, service, page_path, language, destination | Intent | Navbar/widget/footer, service, portfolio, artikel, contact |
| contact_form_start | Field pertama pada brief mendapat fokus | form_name, page_path, language | Diagnostic | Contact |
| contact_form_submit | Form brief dikirim | form_name, page_path, language | Diagnostic | Contact |
| email_click | Klik mailto: | cta_location, page_path, language, destination | Micro conversion | Contact |
| cta_click | Klik CTA internal atau WhatsApp | cta_name, cta_location, service, portfolio_slug, destination, page_path, language | Diagnostic | CTA konsultasi, service, solutions, portfolio, artikel, contact |
| view_service | Page load landing service | service, page_path, page_title, language | Diagnostic | Individual service |
| view_portfolio | Page load indeks portfolio | page_path, page_title, language | Diagnostic | Portfolio |
| view_case_study | Page load detail portfolio | portfolio_slug, page_path, page_title, language | Diagnostic | Portfolio detail |

contact_form_submit sengaja bukan lead confirmation. Tombol submit saja tidak
mengirim generate_lead; event itu hanya dibuat setelah backend berhasil
menyimpan lead dan redirect ke URL thank-you. Token submission diambil satu kali
dari session dan dideduplikasi lagi dengan localStorage, sehingga reload atau
back button tidak menghitung submission yang sama dua kali.
Submission yang ditandai spam tetap tersimpan mengikuti workflow existing,
tetapi tidak memperoleh token conversion. Membuka form/thank-you secara
langsung atau submit yang gagal validasi/penyimpanan tidak mengirim lead.

Helper memakai satu listener capture untuk klik dan klik tengah; nama event
dideduplikasi per klik. Semua link wa.me, api.whatsapp.com, web.whatsapp.com,
dan whatsapp: terdeteksi, termasuk link tanpa atribut tracking. Event masuk
antrean sebelum navigasi tanpa preventDefault, timeout, atau perubahan UI.
CTA tanpa atribut mendapatkan nama dari label tombol/judul kartu dan lokasi
dari navbar/footer/form atau ID section. Event engagement existing tetap dipakai
agar tidak memecah histori dengan nama event tambahan.

Page view hanya berasal dari konfigurasi GA4 existing di layout publik, baik ID
maupun EN; helper tidak mengirim page_view. Dashboard memakai bundle terpisah
dan helper juga mengabaikan path /admin. Tidak ada consent manager di source
existing. Konfigurasi tag production memuat Enhanced Measurement outbound click;
outbound menggunakan event click otomatis dari fitur tersebut, tanpa event custom
outbound_click yang dapat menghitung interaksi sama lagi.

Query kampanye tidak dihapus dari URL browser. Middleware
PreserveAnalyticsCampaign menjaga UTM/click ID pada redirect GET publik ke
host yang sama, termasuk URL legacy dan pergantian bahasa. Tujuan, status
redirect, canonical, hreflang, dan sitemap tetap mengikuti implementasi existing;
parameter tidak diteruskan ke dashboard atau host lain.

Temuan terpisah: CanonicalRedirect existing membandingkan query mentah dengan
Request::getUri() yang menormalkan urutan query. UTM yang belum berurutan dapat
memicu 301 ke URL yang sama sebelum middleware web berjalan. Perubahan ini
tidak menyentuh middleware canonical sesuai batas lingkup; perbandingan URL
tersebut perlu diperbaiki setelah mendapat izin. Parameter baru pada redirect
kampanye diurutkan agar tujuan redirect yang diperbaiki bisa dimuat.

## Verifikasi tanpa event uji di production

1. Periksa HTML publik lewat HTTP tanpa mengeksekusi vendor analytics: satu
   script GA4, satu config, dan tanpa config di dashboard.
2. Periksa sintaks JavaScript dan jalankan simulasi helper dengan gtag/dataLayer
   lokal; jangan kirim event ke endpoint Google.
3. Uji token conversion menggunakan SQLite :memory: terisolasi: sukses,
   reload thank-you, akses langsung, gagal validasi, spam, dan form EN.
4. Jika memakai GTM, pastikan event di dataLayer
   dipetakan ke GA4 Event tags yang sesuai. Jangan menambahkan GA4 page view
   kedua jika tag configuration sudah mengirimkannya.

## Konfigurasi manual GA4

Di GA4 Admin > Data display > Events, tandai generate_lead dan whatsapp_click
sebagai Key Event. Nama event dapat disiapkan sebelum event pertama diterima;
jangan membuat aturan yang mengubah page_view menjadi generate_lead.
generate_lead berarti brief non-spam sudah tersimpan, sedangkan whatsapp_click
hanya menunjukkan niat menghubungi, bukan chat terkirim atau lead terkonfirmasi.
Penandaan tidak berlaku surut terhadap data lama. Jika dipakai di Google Ads,
gunakan generate_lead sebagai primary dan whatsapp_click sebagai secondary
agar bidding tidak menyamakan klik dengan lead terkonfirmasi.

Jangan menandai scroll, page_view, view_service, atau cta_click biasa sebagai
primary lead conversion. Di GTM, buat mapping parameter hanya untuk parameter
non-PII yang terdokumentasi di matrix ini.

Aktifkan Outbound clicks pada Enhanced Measurement. Untuk breakdown parameter
di laporan, buat custom dimensions event-scoped: cta_name, cta_location,
button_location, form_name, dan page_path jika diperlukan.
