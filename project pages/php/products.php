
<?php
function loadProductsData()
{
    $jsonPath = 'C:/localhost/abz/Emo-Pugs/project pages/sporttovar.json';
    if (!file_exists($jsonPath)) {
        throw new Exception("Файл с товарами не найден");
    }

    $json = file_get_contents($jsonPath);
    $data = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Ошибка чтения JSON: " . json_last_error_msg());
    }

    return $data;
}

function getProductCategories()
{
    $products = loadProductsData();
    $categories = [];

    foreach ($products as $product) {
        // Если у товара есть категории
        if (!empty($product['categories'])) {
            // Первая категория считается основной
            $mainCategory = $product['categories'][0];

            // Если есть вторая категория - считаем ее подкатегорией
            if (isset($product['categories'][1])) {
                $subCategory = $product['categories'][1];
                if (!isset($categories[$mainCategory])) {
                    $categories[$mainCategory] = [];
                }
                if (!in_array($subCategory, $categories[$mainCategory])) {
                    $categories[$mainCategory][] = $subCategory;
                }
            } else {
                // Если только одна категория
                if (!isset($categories[$mainCategory])) {
                    $categories[$mainCategory] = [];
                }
            }
        }
    }

    return $categories;
}

function getAllCategoriesFlat()
{
    $categories = getProductCategories();
    $flat = [];

    foreach ($categories as $mainCat => $subCats) {
        $flat[] = $mainCat;
        foreach ($subCats as $subCat) {
            $flat[] = "$mainCat ($subCat)";
        }
    }

    return $flat;
}
?>