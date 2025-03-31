<?php
$jsonPath = 'C:/localhost/abz/Emo-Pugs/project pages/sporttovar.json';

$json = file_get_contents($jsonPath);

if (!file_exists($jsonPath)) {
    die("Файл не найден: " . realpath($jsonPath) ?: $jsonPath);
}

// Читаем JSON
$json = file_get_contents($jsonPath);
if ($json === false) {
    die("Не удалось прочитать файл: " . $jsonPath);
}

// Декодируем JSON
$products = json_decode($json, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die("Ошибка в JSON: " . json_last_error_msg());
}

// Выводим товары
foreach ($products as $product) {
    $finalPrice = $product['price'];
    $hasDiscount = $product['discount'] > 0;

    if ($hasDiscount) {
        $finalPrice = $product['price'] * (1 - $product['discount'] / 100);
    }

    echo '<div class="product">';
    echo '<img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '">';
    echo '<div class="categories">' . implode(', ', $product['categories']) . '</div>';
    echo '<h3>' . htmlspecialchars($product['name']) . '</h3>';
    echo '<p>' . htmlspecialchars($product['description']) . '</p>';

    if ($hasDiscount) {
        echo '<div class="price">' . number_format($finalPrice, 2, '.', '') . ' ₽</div>';
        echo '<div class="old-price">' . number_format($product['price'], 2, '.', '') . ' ₽</div>';
    } else {
        echo '<div class="price">' . number_format($product['price'], 2, '.', '') . ' ₽</div>';
    }

    echo '</div>';
}
