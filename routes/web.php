<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SolutionController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

/*
 * Public pages are registered twice from one closure.
 *
 * Indonesian keeps the bare URLs it already has indexed (/services) and the
 * plain route names. English is mounted under /en with an 'en.' name prefix,
 * so /en/services is a real, crawlable address — a session-held locale gave it
 * no address at all, and Googlebot carries no session, which left the whole
 * English translation unindexed.
 *
 * Laravel only allows an optional route parameter in the final position, so a
 * single {locale?} prefix cannot express "absent means Indonesian". Two mounts
 * is the honest way to say it. Views call lroute() so the name prefix is
 * applied for whichever language is being read.
 */
$publicPages = function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [AboutController::class, 'index'])->name('about');
    Route::get('/services', [ServiceController::class, 'index'])->name('services');
    Route::get('/solutions', [SolutionController::class, 'index'])->name('solutions');

    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
    Route::get('/portfolio/{portfolio:slug}', [PortfolioController::class, 'show'])->name('portfolio.show');

    Route::get('/faq', [FaqController::class, 'index'])->name('faq');

    Route::get('/blog', [BlogController::class, 'index'])->name('blog');
    Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
};

Route::group([], $publicPages);                                  // id — kanonik
Route::prefix('en')->name('en.')->group($publicPages);           // en

/*
|--------------------------------------------------------------------------
| Bahasa
|--------------------------------------------------------------------------
| Menyimpan pilihan bahasa di sesi lalu mengembalikan pengunjung ke halaman
| yang sedang dibuka, sehingga URL tetap bersih tanpa prefiks /id atau /en.
*/
Route::get('/lang/{locale}', function (string $locale) {
    abort_unless(array_key_exists($locale, config('locales.available', [])), 404);

    // The switcher now links straight at the localized URL; this only survives
    // so older links and bookmarks land somewhere sensible instead of 404ing.
    $default = config('locales.default', 'id');

    return redirect(url($locale === $default ? '/' : '/' . $locale), 301);
})->name('locale.switch');

// SEO
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');


// Load admin routes
require __DIR__ . '/admin.php';
