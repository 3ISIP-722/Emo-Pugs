<?php
require_once 'products.php';

function filterProducts($filters)
{
    $products = loadProductsData();

    foreach ($products as $key => $product) {
        // Улучшенный фильтр по поиску в названии, описании и категориях
        if (!empty($filters['search'])) {
            $search = mb_strtolower(trim($filters['search']));
            $name = mb_strtolower($product['name']);
            $desc = mb_strtolower($product['description']);
            $categories = array_map('mb_strtolower', $product['categories']);

            $found = false;
            
            // Поиск по отдельным словам
            $searchWords = explode(' ', $search);
            foreach ($searchWords as $word) {
                if (strpos($name, $word) !== false || 
                    strpos($desc, $word) !== false) {
                    $found = true;
                    break;
                }
                
                // Поиск по категориям
                foreach ($categories as $category) {
                    if (strpos($category, $word) !== false) {
                        $found = true;
                        break 2;
                    }
                }
            }
            
            if (!$found) {
                unset($products[$key]);
                continue;
            }
        }

        // Остальные фильтры остаются без изменений
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
