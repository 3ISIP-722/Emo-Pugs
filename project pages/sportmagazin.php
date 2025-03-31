<?php
require_once 'php/filtr.php';
require_once 'php/products.php';

$filters = [
    'search' => $_GET['search'] ?? '',
    'categories' => isset($_GET['categories']) ? (array)$_GET['categories'] : [],
    'minPrice' => isset($_GET['minPrice']) ? (float)$_GET['minPrice'] : null,
    'maxPrice' => isset($_GET['maxPrice']) ? (float)$_GET['maxPrice'] : null,
    'sort' => $_GET['sort'] ?? ''
];

$filteredProducts = filterProducts($filters);
$categories = getProductCategories();
$allCategories = getAllCategoriesFlat();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="../img/sportlogo.svg" type="image/x-icon" />
    <title>PowerUp</title>
    <style>
        .products {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
        }

        .product {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            width: 250px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .product img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .product h3 {
            margin-top: 10px;
            margin-bottom: 5px;
        }

        .price {
            font-weight: bold;
            font-size: 1.2em;
            color: #e63946;
        }

        .old-price {
            text-decoration: line-through;
            color: #999;
            font-size: 0.9em;
        }

        .products-container {
            display: flex;
        }

        .filters {
            width: 250px;
            padding: 20px;
        }

        .products {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
        }

        .product {
            border: 1px solid #ddd;
            padding: 15px;
            width: 250px;
        }

        .category-group {
            margin-bottom: 15px;
        }

        .category-item {
            margin-left: 15px;
        }
    </style>
</head>

<body>
    <header></header>
    <main>
        <div class="products-container">
            <div class="filters">
                <form method="get">
                    <h3>Поиск</h3>
                    <input type="text" name="search" value="<?= htmlspecialchars($filters['search']) ?>"
                        placeholder="Название или описание" style="width: 100%; padding: 8px;">

                    <h3>Категории</h3>
                    <?php
                    $categories = getProductCategories();
                    foreach ($categories as $mainCat => $subCats): ?>
                        <div class="category-group">
                            <strong><?= htmlspecialchars($mainCat) ?></strong>
                            <?php if (!empty($subCats)): ?>
                                <?php foreach ($subCats as $subCat): ?>
                                    <div class="category-item">
                                        <label>
                                            <input type="checkbox" name="categories[]"
                                                value="<?= htmlspecialchars("$mainCat ($subCat)") ?>"
                                                <?= in_array("$mainCat ($subCat)", $filters['categories']) ? 'checked' : '' ?>>
                                            <?= htmlspecialchars($subCat) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="category-item">
                                    <label>
                                        <input type="checkbox" name="categories[]"
                                            value="<?= htmlspecialchars($mainCat) ?>"
                                            <?= in_array($mainCat, $filters['categories']) ? 'checked' : '' ?>>
                                        Все <?= htmlspecialchars($mainCat) ?>
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <h3>Цена, ₽</h3>
                    <div style="display: flex; gap: 5px; margin-bottom: 15px;">
                        <input type="number" name="minPrice" placeholder="От" value="<?= $filters['minPrice'] ?>"
                            style="width: 50%; padding: 5px;">
                        <input type="number" name="maxPrice" placeholder="До" value="<?= $filters['maxPrice'] ?>"
                            style="width: 50%; padding: 5px;">
                    </div>

                    <h3>Сортировка</h3>
                    <select name="sort" style="width: 100%; padding: 5px; margin-bottom: 15px;">
                        <option value="">По умолчанию</option>
                        <option value="asc" <?= $filters['sort'] === 'asc' ? 'selected' : '' ?>>Цена ↑</option>
                        <option value="desc" <?= $filters['sort'] === 'desc' ? 'selected' : '' ?>>Цена ↓</option>
                    </select>

                    <button type="submit" style="padding: 8px 15px; background:rgb(28, 0, 66); color: white; border: none; cursor: pointer;">
                        Применить
                    </button>
                    <a href="?" style="margin-left: 10px; color: #666;">Сбросить</a>
                </form>
            </div>

            <div class="products">
                <?php if (empty($filteredProducts)): ?>
                    <p style="width: 100%; text-align: center; margin-top: 50px; color: #666;">
                        Товары не найдены. Измените параметры фильтрации.
                    </p>
                <?php else: ?>
                    <?php foreach ($filteredProducts as $product):
                        $finalPrice = $product['discount'] > 0
                            ? $product['price'] * (1 - $product['discount'] / 100)
                            : $product['price'];
                    ?>
                        <div class="product">
                            <img src="<?= htmlspecialchars($product['image'] ?? '../img/korm1.jpg') ?>"
                                alt="<?= htmlspecialchars($product['name']) ?>">
                            <div style="color: #666; font-size: 0.8em; margin: 5px 0;">
                                <?= implode(', ', $product['categories']) ?>
                            </div>
                            <h3 style="margin: 5px 0; font-size: 1.1em;"><?= htmlspecialchars($product['name']) ?></h3>
                            <p style="color: #555; font-size: 0.9em;"><?= htmlspecialchars($product['description']) ?></p>

                            <div style="margin-top: 10px;">
                                <?php if ($product['discount'] > 0): ?>
                                    <div class="price">
                                        <?= number_format($finalPrice, 0, '', ' ') ?> ₽
                                    </div>
                                    <div class="old-price"><?= number_format($product['price'], 0, '', ' ') ?> ₽</div>
                                <?php else: ?>
                                    <div class="price"><?= number_format($product['price'], 0, '', ' ') ?> ₽</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <footer></footer>
</body>

</html>