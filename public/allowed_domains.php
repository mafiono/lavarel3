<?php
// Include trace Domains
$domains = [
    'www.google-analytics.com',
    '*.doubleclick.net',
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
    '*.switchpayments.com',
    'minifootball.pt',
//    'wchat.freshchat.com',
];
$parts = [
    "frame-ancestors * 'self' *.casinoportugal.pt " . implode(' ', $domains),
    "default-src 'self' 'unsafe-eval' 'unsafe-inline' " . implode(' ', $domains),
//    "connect-src wss://*.tawk.to",
    "object-src 'self'",
];
header("Content-Security-Policy: " . implode('; ', $parts));