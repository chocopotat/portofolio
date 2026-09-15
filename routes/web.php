<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortfolioController;

/**
 * ================================================================
 * Portfolio Landing Page Routes
 * ================================================================
 */

// Portfolio landing page
Route::get('/', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.show');

// Contact API endpoint
Route::prefix('api')->group(function () {
    Route::post('/contact', [PortfolioController::class, 'submitContact'])
        ->name('api.contact.submit')
        ->middleware('throttle:3,60');
});

// SEO Routes
Route::get('/robots.txt', function () {
    return response(
        "User-agent: *\nAllow: /\nSitemap: " . url('/sitemap.xml'),
        200
    )->header('Content-Type', 'text/plain');
});

Route::get('/sitemap.xml', function () {
    $urlset = '<?xml version="1.0" encoding="UTF-8"?>' .
        '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' .
        '<url><loc>' . url('/') . '</loc>' .
        '<lastmod>' . now()->toAtomString() . '</lastmod>' .
        '<priority>1.0</priority></url></urlset>';
    
    return response($urlset)->header('Content-Type', 'application/xml');
});
