<?php

namespace Webkul\GoogleShopping\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FeedController extends Controller
{
    public function feed(): Response
    {
        $channel  = core()->getCurrentChannel();
        $locale   = app()->getLocale();
        $currency = core()->getBaseCurrencyCode();
        $baseUrl  = url('/');

        $products = DB::table('product_flat as pf')
            ->join('products as p', 'p.id', '=', 'pf.product_id')
            ->leftJoin('product_images as pi', function ($j) {
                $j->on('pi.product_id', '=', 'pf.product_id')
                  ->whereRaw('pi.id = (SELECT MIN(id) FROM product_images WHERE product_id = pf.product_id)');
            })
            ->leftJoin('product_categories as pc', 'pc.product_id', '=', 'pf.product_id')
            ->leftJoin('category_translations as ct', function ($j) use ($locale) {
                $j->on('ct.category_id', '=', 'pc.category_id')
                  ->where('ct.locale', '=', $locale);
            })
            ->where('pf.status', 1)
            ->where('pf.locale', $locale)
            ->where('pf.channel', $channel->code)
            ->where('pf.visible_individually', 1)
            ->whereIn('p.type', ['simple', 'virtual', 'downloadable'])
            ->whereNotNull('pf.price')
            ->where('pf.price', '>', 0)
            ->select([
                'pf.product_id as id',
                'pf.sku',
                'pf.name',
                'pf.short_description as description',
                'pf.url_key',
                'pf.price',
                'pf.special_price',
                'pf.special_price_from',
                'pf.special_price_to',
                'pi.path as image_path',
                'ct.name as category_name',
            ])
            ->groupBy(
                'pf.product_id', 'pf.sku', 'pf.name',
                'pf.short_description', 'pf.url_key', 'pf.price',
                'pf.special_price', 'pf.special_price_from',
                'pf.special_price_to', 'pi.path', 'ct.name'
            )
            ->limit(5000)
            ->get();

        $xml = $this->buildXml($products, $channel, $baseUrl, $currency);

        return response($xml, 200, [
            'Content-Type'        => 'application/xml; charset=UTF-8',
            'Content-Disposition' => 'inline; filename="google-shopping.xml"',
            'Cache-Control'       => 'public, max-age=3600',
        ]);
    }

    private function buildXml($products, $channel, string $baseUrl, string $currency): string
    {
        $storeName = htmlspecialchars($channel->name ?? config('app.name'), ENT_XML1);
        $storeDesc = htmlspecialchars('Products from ' . ($channel->name ?? config('app.name')), ENT_XML1);

        $items = '';

        foreach ($products as $p) {
            $productUrl = $baseUrl . '/' . $p->url_key;

            $price = $this->effectivePrice($p);
            $priceStr = number_format($price, 2, '.', '') . ' ' . $currency;

            $imageUrl = $p->image_path
                ? Storage::url($p->image_path)
                : '';

            if (! $imageUrl) {
                continue;
            }

            // Ensure absolute URL
            if (! str_starts_with($imageUrl, 'http')) {
                $imageUrl = $baseUrl . $imageUrl;
            }

            $title       = htmlspecialchars($p->name ?? '', ENT_XML1);
            $description = htmlspecialchars(strip_tags($p->description ?? $p->name ?? ''), ENT_XML1);
            $category    = htmlspecialchars($p->category_name ?? 'General', ENT_XML1);
            $id          = (int) $p->id;
            $sku         = htmlspecialchars($p->sku ?? (string) $id, ENT_XML1);

            $items .= <<<XML

        <item>
            <g:id>{$id}</g:id>
            <g:title>{$title}</g:title>
            <g:description>{$description}</g:description>
            <g:link>{$productUrl}</g:link>
            <g:image_link>{$imageUrl}</g:image_link>
            <g:condition>new</g:condition>
            <g:availability>in stock</g:availability>
            <g:price>{$priceStr}</g:price>
            <g:brand>{$storeName}</g:brand>
            <g:google_product_category>{$category}</g:google_product_category>
            <g:identifier_exists>no</g:identifier_exists>
            <g:mpn>{$sku}</g:mpn>
        </item>
XML;
        }

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
    <channel>
        <title>{$storeName}</title>
        <link>{$baseUrl}</link>
        <description>{$storeDesc}</description>{$items}
    </channel>
</rss>
XML;
    }

    private function effectivePrice(object $p): float
    {
        $now = now();

        if (
            $p->special_price &&
            $p->special_price > 0 &&
            (! $p->special_price_from || $now >= $p->special_price_from) &&
            (! $p->special_price_to   || $now <= $p->special_price_to)
        ) {
            return (float) $p->special_price;
        }

        return (float) $p->price;
    }
}
