<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SitemapController extends Controller
{
    /**
     * Generate dynamic sitemap XML.
     */
    public function index(): Response
    {
        $baseUrl = rtrim(config('app.frontend_url', env('FRONTEND_URL', 'https://easyticket.com.pk')), '/');
        $now = now()->toAtomString();

        // Core static public pages
        $urls = [
            [
                'loc' => $baseUrl . '/',
                'lastmod' => $now,
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'loc' => $baseUrl . '/flights',
                'lastmod' => $now,
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
            [
                'loc' => $baseUrl . '/destinations',
                'lastmod' => $now,
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ],
            [
                'loc' => $baseUrl . '/b2b-services',
                'lastmod' => $now,
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => $baseUrl . '/about',
                'lastmod' => $now,
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => $baseUrl . '/blog',
                'lastmod' => $now,
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
            [
                'loc' => $baseUrl . '/contact',
                'lastmod' => $now,
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'loc' => $baseUrl . '/become-agent',
                'lastmod' => $now,
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'loc' => $baseUrl . '/login',
                'lastmod' => $now,
                'changefreq' => 'monthly',
                'priority' => '0.5',
            ],
        ];

        // Dynamic Published Blogs
        try {
            $blogs = Blog::published()
                ->select(['id', 'slug', 'title', 'image', 'updated_at', 'published_at'])
                ->orderBy('updated_at', 'desc')
                ->get();

            foreach ($blogs as $blog) {
                $lastmod = ($blog->updated_at ?? $blog->published_at ?? now())->toAtomString();
                $imageUrl = null;

                if (!empty($blog->image)) {
                    if (str_starts_with($blog->image, 'http')) {
                        $imageUrl = $blog->image;
                    } elseif (Storage::disk('public')->exists($blog->image)) {
                        $imageUrl = Storage::disk('public')->url($blog->image);
                    } else {
                        $imageUrl = url($blog->image);
                    }
                }

                $urls[] = [
                    'loc' => $baseUrl . '/blog/' . urlencode($blog->slug),
                    'lastmod' => $lastmod,
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                    'image' => $imageUrl ? [
                        'loc' => $imageUrl,
                        'title' => $blog->title,
                    ] : null,
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('Sitemap generation failed to query blogs: ' . $e->getMessage());
        }

        // Build XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        foreach ($urls as $item) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($item['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
            $xml .= '    <lastmod>' . htmlspecialchars($item['lastmod'], ENT_XML1, 'UTF-8') . "</lastmod>\n";
            $xml .= '    <changefreq>' . htmlspecialchars($item['changefreq'], ENT_XML1, 'UTF-8') . "</changefreq>\n";
            $xml .= '    <priority>' . htmlspecialchars($item['priority'], ENT_XML1, 'UTF-8') . "</priority>\n";

            if (!empty($item['image'])) {
                $xml .= "    <image:image>\n";
                $xml .= '      <image:loc>' . htmlspecialchars($item['image']['loc'], ENT_XML1, 'UTF-8') . "</image:loc>\n";
                if (!empty($item['image']['title'])) {
                    $xml .= '      <image:title>' . htmlspecialchars($item['image']['title'], ENT_XML1, 'UTF-8') . "</image:title>\n";
                }
                $xml .= "    </image:image>\n";
            }

            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600', // Cache for 1 hour
        ]);
    }
}

