<?php
// Include trace Domains
$domains = [
    'www.google-analytics.com',
    '*.tawk.to',
    'wss://*.tawk.to',
    '*.casinoportugal.pt',
    'casinoportugal-static-test.casinomodule.com',
    'stage-game-launcher-lux.isoftbet.com/',
    'cdn.jsdelivr.net',
    'fonts.googleapis.com',
    'fonts.gstatic.com',
    '*.switchpayments.com',
    'stats.g.doubleclick.net',
    'www.google.com/ads*',
    'www.google.pt/ads*',
    'casinoportugal.betstream.betgenius.com/',
    'www.score24.com',
    '*.paysafecard.com',
//    'wchat.freshchat.com',
//    'sandbox.meowallet.pt',
//    'wallet.pt',
//    'sandbox.paypal.com',
//    'paypal.com',
];
$parts = [
    "default-src 'self' 'unsafe-eval' 'unsafe-inline' " . implode(' ', $domains),
//    "connect-src wss://*.tawk.to",
    "object-src 'self'",
];
header("Content-Security-Policy: frame-ancestors; " . implode('; ', $parts));