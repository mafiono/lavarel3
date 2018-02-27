<?php
// Include trace Domains
$domains = [
    'www.google-analytics.com',
    'embed.tawk.to',
//    'wchat.freshchat.com',
    'cdn.jsdelivr.net',
    'casinoportugal-static-test.casinomodule.com',
    'casinoportugal.pt',
    'meowallet.pt',
];
header("Content-Security-Policy: frame-ancestors; script-src 'self' 'unsafe-eval' 'unsafe-inline' " . implode(' ', $domains) . "; object-src 'self'");