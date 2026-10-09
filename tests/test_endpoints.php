<?php

$urls = [
    '/' => 200,
    '/shop' => 200,
    '/shop?search=Chronograph' => 200,
    '/shop?category=horology' => 200,
    '/shop?sort=price_desc' => 200,
    '/products/zyricz-horizon-spatial-headphones' => 200,
    '/products/aethelgard-chrono-phantom-automatic' => 200,
    '/cart' => 200,
    '/wishlist' => 200,
    '/checkout' => 302, // Protected redirect to /cart when bag is empty
    '/track-order' => 200,
    '/track-order?order_number=ZYR-2026-91044' => 200,
    '/login' => 200,
    '/register' => 200,
    '/admin/login' => 200,
];

$failed = 0;
foreach ($urls as $url => $expectedCode) {
    $ch = curl_init('http://127.0.0.1:8000' . $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $status = ($code === $expectedCode) ? "PASS" : "FAIL (got $code, expected $expectedCode)";
    if ($code !== $expectedCode) {
        $failed++;
    }
    echo "[$status] $url\n";
}

if ($failed > 0) {
    echo "\nTotal Failures: $failed\n";
    exit(1);
} else {
    echo "\nAll endpoints responded as expected!\n";
    exit(0);
}
