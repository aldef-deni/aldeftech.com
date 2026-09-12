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
| generate_lead | Halaman thank-you setelah lead berhasil disimpan | lead_source, form_name, project_type, budget_range, page_path, page_title, language, campaign IDs | Primary | /contact/thank-you |
| whatsapp_click | Klik link WhatsApp | cta_location, service, page_path, language, destination | Micro conversion | Navbar/widget, service, portfolio, artikel, contact |
| contact_form_start | Field pertama pada brief mendapat fokus | form_name, page_path, language | Diagnostic | Contact |
| contact_form_submit | Form brief dikirim | form_name, page_path, language | Diagnostic | Contact |
| email_click | Klik mailto: | cta_location, page_path, language, destination | Micro conversion | Contact |
| cta_click | Klik CTA internal atau CTA dengan tujuan terukur | cta_location, service, portfolio_slug, destination, page_path, language | Micro/diagnostic | Semua CTA utama |
| view_service | Page load landing service | service, page_path, page_title, language | Diagnostic | Individual service |
| view_portfolio | Page load indeks portfolio | page_path, page_title, language | Diagnostic | Portfolio |
| view_case_study | Page load detail portfolio | portfolio_slug, page_path, page_title, language | Diagnostic | Portfolio detail |

contact_form_submit sengaja bukan lead confirmation. Tombol submit saja tidak
mengirim generate_lead; event itu hanya dibuat setelah backend berhasil
menyimpan lead dan redirect ke URL thank-you. Token submission diambil satu kali
dari session dan dideduplikasi lagi dengan localStorage, sehingga reload atau
back button tidak menghitung submission yang sama dua kali.

## Pengujian setelah deployment

1. Buka situs dengan query UTM contoh, misalnya
   ?utm_source=google&utm_medium=organic&utm_campaign=brand-test.
2. Buka DevTools Network dan Console. Pada local/testing helper boleh mencatat
   event secara aman; production tidak mengaktifkan debug_mode permanen.
3. Uji klik WhatsApp dari widget, halaman service, portfolio detail, dan contact.
4. Isi lalu kirim brief valid. Pastikan record lead muncul, URL berubah ke
   /contact/thank-you, dan generate_lead terlihat satu kali di GA4 Realtime
   atau DebugView.
5. Reload halaman thank-you dan pastikan tidak ada generate_lead kedua.
6. Uji validasi gagal dan kegagalan penyimpanan: tidak boleh ada
   generate_lead, dan user harus mendapat error state atau fallback WhatsApp.
7. Jika memakai GTM, Preview tag container dan pastikan event di dataLayer
   dipetakan ke GA4 Event tags yang sesuai. Jangan menambahkan GA4 page view
   kedua jika tag configuration sudah mengirimkannya.

## Konfigurasi manual GA4

Setelah generate_lead terdeteksi di property production, administrator harus
menandainya sebagai **Primary key event**. whatsapp_click boleh ditandai
sebagai secondary/micro key event bila ingin mengukur lead intent, tetapi jangan
menyamakan klik WhatsApp dengan lead yang sudah tersimpan.

Jangan menandai scroll, page_view, view_service, atau cta_click biasa sebagai
primary lead conversion. Di GTM, buat mapping parameter hanya untuk parameter
non-PII yang terdokumentasi di matrix ini.

## Search Console

Setelah deployment, kirim /sitemap.xml di Search Console, validasi canonical dan
hreflang untuk versi ID serta /en, lalu pantau Coverage/Page indexing pada
landing service baru. Halaman /contact/thank-you sengaja noindex dan tidak masuk
sitemap.
