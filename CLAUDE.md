# CLAUDE.md

See also AGENTS.md for the full development instructions.

## ALDEFTECH Persistent Project Rules

- Production project: /var/www/aldeftech
- Repository: https://github.com/aldef-deni/aldeftech.com.git
- Semua perubahan yang diminta user harus diselesaikan sampai implementasi, bukan hanya analisis.
- Setelah perubahan yang diminta selesai dan valid, commit dan push kecuali user secara eksplisit melarang.
- Git author wajib:
  Deni Afrizal <deniafrizal2904@gmail.com>
- Jangan pernah menambahkan Co-authored-by, Claude, Anthropic, AI, bot, atau contributor lain.
- Jangan force push atau rewrite history tanpa instruksi eksplisit.
- Untuk troubleshooting, prioritaskan targeted inspection dan targeted test agar hemat token.
- Jangan melakukan audit/test berulang tanpa alasan.
- Production deploy berada di /var/www/aldeftech.
- Pertahankan SEO production: canonical, hreflang, sitemap, robots, redirect dan indexing harus konsisten.
- Setelah setiap pekerjaan, berikan laporan file yang berubah, commit hash, push status, deployment status, dan hasil validasi.
