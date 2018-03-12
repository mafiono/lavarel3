<?php
// Include trace Domains
$domains = [
    "'self'",
    '*.casinoportugal.pt',
    'www.google-analytics.com',
    '*.doubleclick.net',
    'www.google.com',
    'www.google.pt',
    'www.googleadservices.com',
    '*.tawk.to',
    'wss://*.tawk.to',
    '*.casinoportugal.pt',
    'casinoportugal-static.casinomodule.com',
    'casinoportugal-static-test.casinomodule.com',
    'game-launcher-lux.isoftbet.com',
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
    "frame-ancestors " . implode(' ', $domains),
    "default-src 'unsafe-eval' 'unsafe-inline' " . implode(' ', $domains),
//    "connect-src wss://*.tawk.to",
    "object-src 'self'",
];
header("Content-Security-Policy: " . implode('; ', $parts));