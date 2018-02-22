<?php
// Include trace Domains
$domains = [
    'www.google-analytics.com',
    'www.google-analytics.com/analytics.js',
    'embed.tawk.to',
//    'wchat.freshchat.com',
    'cdn.jsdelivr.net',
//    'd84vwnsnais90.cloudfront.net',
    'casinoportugal-static-test.casinomodule.com',
    'casinoportugal.pt',
];
header("Content-Security-Policy: frame-ancestors; script-src 'self' 'unsafe-eval' 'unsafe-inline' " . implode(' ', $domains) . "; object-src 'self'");