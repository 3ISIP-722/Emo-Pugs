<?php
require_once 'products.php';

function filterProducts($filters)
{
    $products = loadProductsData();

    foreach ($products as $key => $product) {
        // Фильтр по поиску в названии и описании
        if (!empty($filters['search'])) {
            $search = mb_strtolower($filters['search']);
            $name = mb_strtolower($product['name']);
            $desc = mb_strtolower($product['description']);

            if (strpos($name, $search) === false && strpos($desc, $search) === false) {
                unset($products[$key]);
                continue;
            }
        }

        // Фильтр по категориям
        if (!empty($filters['categories'])) {
            $match = false;
            foreach ($filters['categories'] as $filterCat) {
                if (in_array($filterCat, $product['categories'])) {
                    $match = true;
                    break;
                }
            }
            if (!$match) {
                unset($products[$key]);
                continue;
            }
        }

        // Фильтр по цене
        if (!empty($filters['minPrice']) && $product['price'] < $filters['minPrice']) {
            unset($products[$key]);
            continue;
        }

        if (!empty($filters['maxPrice']) && $product['price'] > $filters['maxPrice']) {
            unset($products[$key]);
            continue;
        }
    }

    // Сортировка
    if (!empty($filters['sort'])) {
        usort($products, function ($a, $b) use ($filters) {
            return $filters['sort'] === 'asc'
                ? $a['price'] <=> $b['price']
                : $b['price'] <=> $a['price'];
        });
    }

    return array_values($products);
}
