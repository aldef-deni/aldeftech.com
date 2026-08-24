<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SolutionController as AdminSolutionController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\BlogCategoryController as AdminBlogCategoryController;
use App\Http\Controllers\Admin\BlogTagController as AdminBlogTagController;
use App\Http\Controllers\Admin\LeadController as AdminLeadController;
use App\Http\Controllers\Admin\CeoProfileController as AdminCeoProfileController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\SeoSettingController;
use App\Http\Controllers\Admin\WhatsAppSettingController;
use App\Http\Controllers\Admin\AnalyticsSettingController;
use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ProcessStepController;
use App\Http\Controllers\Admin\SocialMediaController;
use App\Http\Controllers\Admin\AboutController as AdminAboutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Admin Auth
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin Protected Routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Homepage CMS
    Route::get('homepage', [HomepageController::class, 'index'])->name('homepage.index');
    Route::put('homepage/{section}', [HomepageController::class, 'update'])->name('homepage.update');

    // Content Management
    Route::resource('services', AdminServiceController::class)->names('services');
    Route::post('services/reorder', [AdminServiceController::class, 'reorder'])->name('services.reorder');

    Route::resource('solutions', AdminSolutionController::class)->names('solutions');
    Route::post('solutions/reorder', [AdminSolutionController::class, 'reorder'])->name('solutions.reorder');

    Route::resource('portfolio', AdminPortfolioController::class)->names('portfolio');
    Route::post('portfolio/{portfolio}/toggle-featured', [AdminPortfolioController::class, 'toggleFeatured'])->name('portfolio.toggle-featured');
    Route::post('portfolio/{portfolio}/toggle-published', [AdminPortfolioController::class, 'togglePublished'])->name('portfolio.toggle-published');
    Route::delete('portfolio/{portfolio}/image/{image}', [AdminPortfolioController::class, 'deleteImage'])->name('portfolio.delete-image');

    Route::resource('testimonials', AdminTestimonialController::class)->names('testimonials');
    Route::post('testimonials/reorder', [AdminTestimonialController::class, 'reorder'])->name('testimonials.reorder');

    Route::resource('faq', AdminFaqController::class)->names('faq');
    Route::post('faq/reorder', [AdminFaqController::class, 'reorder'])->name('faq.reorder');

    Route::resource('process-steps', ProcessStepController::class)->names('process-steps');
    Route::post('process-steps/reorder', [ProcessStepController::class, 'reorder'])->name('process-steps.reorder');

    // Blog
    Route::resource('blog', AdminBlogPostController::class)->names('blog');
    Route::post('blog/{blog}/toggle-publish', [AdminBlogPostController::class, 'togglePublish'])->name('blog.toggle-publish');
    Route::resource('categories', AdminBlogCategoryController::class)->names('categories');
    Route::resource('tags', AdminBlogTagController::class)->names('tags');

    // Leads
    Route::get('leads', [AdminLeadController::class, 'index'])->name('leads.index');
    Route::get('leads/export', [AdminLeadController::class, 'export'])->name('leads.export');
    Route::get('leads/{lead}', [AdminLeadController::class, 'show'])->name('leads.show');
    Route::put('leads/{lead}/status', [AdminLeadController::class, 'updateStatus'])->name('leads.update-status');
    Route::post('leads/{lead}/notes', [AdminLeadController::class, 'addNote'])->name('leads.add-note');
    Route::put('leads/{lead}/assign', [AdminLeadController::class, 'assign'])->name('leads.assign');
    Route::put('leads/{lead}/archive', [AdminLeadController::class, 'archive'])->name('leads.archive');
    Route::delete('leads/{lead}', [AdminLeadController::class, 'destroy'])->name('leads.destroy');

    // Company
    Route::get('ceo', [AdminCeoProfileController::class, 'edit'])->name('ceo.edit');
    Route::put('ceo', [AdminCeoProfileController::class, 'update'])->name('ceo.update');

    Route::get('about', [AdminAboutController::class, 'edit'])->name('about.edit');
    Route::put('about', [AdminAboutController::class, 'update'])->name('about.update');

    Route::get('social-media', [SocialMediaController::class, 'index'])->name('social-media.index');
    Route::post('social-media', [SocialMediaController::class, 'store'])->name('social-media.store');
    Route::put('social-media/{socialLink}', [SocialMediaController::class, 'update'])->name('social-media.update');
    Route::delete('social-media/{socialLink}', [SocialMediaController::class, 'destroy'])->name('social-media.destroy');

    // Settings
    Route::get('settings/site', [SiteSettingController::class, 'edit'])->name('settings.site');
    Route::put('settings/site', [SiteSettingController::class, 'update'])->name('settings.site.update');

    Route::get('settings/seo', [SeoSettingController::class, 'edit'])->name('settings.seo');
    Route::put('settings/seo', [SeoSettingController::class, 'update'])->name('settings.seo.update');

    Route::get('settings/whatsapp', [WhatsAppSettingController::class, 'edit'])->name('settings.whatsapp');
    Route::put('settings/whatsapp', [WhatsAppSettingController::class, 'update'])->name('settings.whatsapp.update');

    Route::get('settings/analytics', [AnalyticsSettingController::class, 'edit'])->name('settings.analytics');
    Route::put('settings/analytics', [AnalyticsSettingController::class, 'update'])->name('settings.analytics.update');

    // Media
    Route::get('media', [MediaController::class, 'index'])->name('media.index');
    Route::post('media/upload', [MediaController::class, 'upload'])->name('media.upload');
    Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');

    // Users (Super Admin only)
    Route::middleware('role:super-admin')->group(function () {
        Route::resource('users', UserController::class)->names('users');
    });

    // Activity Logs
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
});
