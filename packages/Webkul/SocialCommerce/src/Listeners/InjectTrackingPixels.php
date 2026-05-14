<?php

namespace Webkul\SocialCommerce\Listeners;

class InjectTrackingPixels
{
    public function onHeadAfter(): string
    {
        $output = '';

        if (
            core()->getConfigData('general.content.tiktok_pixel.enabled')
            && ($pixelId = core()->getConfigData('general.content.tiktok_pixel.pixel_id'))
        ) {
            $output .= view('social-commerce::pixels.tiktok', compact('pixelId'))->render();
        }

        if (
            core()->getConfigData('general.content.google_analytics.enabled')
            && ($measurementId = core()->getConfigData('general.content.google_analytics.measurement_id'))
        ) {
            $output .= view('social-commerce::pixels.google-analytics', compact('measurementId'))->render();
        }

        return $output;
    }

    public function onBodyAfter(): string
    {
        $output = '';

        if (
            core()->getConfigData('general.content.tiktok_pixel.enabled')
            && core()->getConfigData('general.content.tiktok_pixel.pixel_id')
        ) {
            $output .= '<script>(function () {
    if (typeof ttq === "undefined") return;
    var _fetch = window.fetch;
    window.fetch = function () {
        var args = arguments;
        var url = typeof args[0] === "string" ? args[0] : (args[0] instanceof Request ? args[0].url : "");
        return _fetch.apply(window, args).then(function (response) {
            if (/\/cart\/add/.test(url)) {
                response.clone().json().then(function (data) {
                    if (data && data.message === "success") {
                        ttq.track("AddToCart");
                    }
                }).catch(function () {});
            }
            return response;
        });
    };
})();</script>';
        }

        return $output;
    }
}
