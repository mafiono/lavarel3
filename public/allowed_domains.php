<?php
// Include trace Domains
$domains = [
    'www.google-analytics.com',
    'stats.g.doubleclick.net',
    'www.google.com',
    'www.google.pt',
    'www.googleadservices.com',
    '*.tawk.to',
    'wss://*.tawk.to',
    '*.casinoportugal.pt',
    'casinoportugal-static-test.casinomodule.com',
    'stage-game-launcher-lux.isoftbet.com',
    'cdn.jsdelivr.net',
    'fonts.googleapis.com',
    'fonts.gstatic.com',
    'casinoportugal.betstream.betgenius.com',
    'www.score24.com',
    '*.paysafecard.com',
//    'wchat.freshchat.com',
    '*.switchpayments.com',
];
$parts = [
    "default-src 'self' 'unsafe-eval' 'unsafe-inline' " . implode(' ', $domains),
//    "connect-src wss://*.tawk.to",
    "object-src 'self'",
];
header("Content-Security-Policy: frame-ancestors; " . implode('; ', $parts));