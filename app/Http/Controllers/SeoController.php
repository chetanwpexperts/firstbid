<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function robots(): Response
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /dashboard\n";
        $content .= "Disallow: /settings\n";
        $content .= "Disallow: /admin\n";
        $content .= "Disallow: /api/\n\n";
        $content .= "Sitemap: " . url('/sitemap.xml') . "\n";

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }

    public function sitemap(): Response
    {
        $date = date('Y-m-d');
        $baseUrl = config('app.url', url('/'));

        $routes = [
            ['url' => url('/'), 'priority' => '1.0', 'freq' => 'daily'],
            ['url' => route('extension'), 'priority' => '0.9', 'freq' => 'weekly'],
            ['url' => route('blog.index'), 'priority' => '0.8', 'freq' => 'daily'],
            ['url' => route('privacy'), 'priority' => '0.3', 'freq' => 'monthly'],
            ['url' => route('terms'), 'priority' => '0.3', 'freq' => 'monthly'],
            ['url' => route('security'), 'priority' => '0.5', 'freq' => 'monthly'],
            ['url' => route('login'), 'priority' => '0.5', 'freq' => 'monthly'],
            ['url' => route('register'), 'priority' => '0.7', 'freq' => 'monthly'],
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($routes as $r) {
            $xml .= '<url>';
            $xml .= "<loc>{$r['url']}</loc>";
            $xml .= "<lastmod>{$date}</lastmod>";
            $xml .= "<changefreq>{$r['freq']}</changefreq>";
            $xml .= "<priority>{$r['priority']}</priority>";
            $xml .= '</url>';
        }

        // Add dynamic blog posts to sitemap
        $blogs = Blog::where('is_published', true)->latest('published_at')->get();
        foreach ($blogs as $b) {
            $blogUrl = route('blog.show', $b->slug);
            $modDate = $b->updated_at->format('Y-m-d');
            $xml .= '<url>';
            $xml .= "<loc>{$blogUrl}</loc>";
            $xml .= "<lastmod>{$modDate}</lastmod>";
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
