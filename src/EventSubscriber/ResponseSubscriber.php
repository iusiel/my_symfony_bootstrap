<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ResponseSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [ResponseEvent::class => "onKernelResponse"];
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $response = $event->getResponse();
        $response->headers->set(
            "Strict-Transport-Security",
            "max-age=31536000 ; includeSubDomains"
        );
        $response->headers->set("X-Frame-Options", "sameorigin");
        $response->headers->set("X-Content-Type-Options", "nosniff");
        $response->headers->set("X-Permitted-Cross-Domain-Policies", "none");
        $response->headers->set("Referrer-Policy", "same-origin");
        $response->headers->set("Clear-Site-Data", '"cache","cookies"');
        $response->headers->set("Cross-Origin-Embedder-Policy", "require-corp");
        $response->headers->set("Cross-Origin-Opener-Policy", "same-origin");
        $response->headers->set("Cross-Origin-Resource-Policy", "same-origin");
        $response->headers->set(
            "Cache-Control",
            "private, max-age=604800, must-revalidate"
        );
        $response->headers->set(
            "Feature-Policy",
            "accelerometer 'none'; ambient-light-sensor 'none'; autoplay 'none'; battery 'none'; camera 'none'; display-capture 'none'; document-domain 'none'; encrypted-media 'none'; fullscreen 'none'; geolocation 'none'; gyroscope 'none'; magnetometer 'none'; microphone 'none'; midi 'none'; navigation-override 'none'; payment 'none'; picture-in-picture 'none'; speaker 'none'; usb 'none'; vibrate 'none'; vr 'none'; " //phpcs:ignore
        );
        $response->headers->set(
            "Content-Security-Policy",
            "default-src 'self'; script-src 'self' "
        );
    }
}
