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
        body {
            margin: 0;
            height: 100%;
            font-family: "Source Serif 4", serif;
            overflow-x: hidden;
        }

        .burger {
            display: none;
            position: relative;
        }

        .burger label {
            cursor: pointer;
            font-weight: 600;
            position: relative;
        }

        .burger .content {
            border-bottom-left-radius: 40px;
            padding: 30px 20px 30px;
            text-align: right;
            background-color: #3693d7;
            position: absolute;
            right: 0;
            top: 100%;
            transition: 0.5s ease;
            opacity: 0;
            z-index: -1;
        }

        .burger input[type="checkbox"] {
            position: absolute;
            right: 0;
            top: 0;
            opacity: 0;
        }

        .burger input[type="checkbox"]:checked~.content {
            opacity: 1;
            z-index: 2;
        }

        .burger a {
            color: white;
            text-decoration: none;
            font-size: 24px;
        }

        .burger a:hover {
            color: #08003b;
            text-shadow: 3px 2px 7px rgba(255, 255, 255, 0.5);
        }

        main {
            width: 80%;
            margin: 0 auto;
            display: block;
        }

        h1 {
            border-bottom: 2px #dedede solid;
            padding-bottom: 1%;
            width: 30%;
            font-family: "Montserrat", sans-serif;
        }

        body.no-scroll {
            overflow: hidden;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #3693d7;
            padding: 10px 20px;
            margin-bottom: 2%;
            width: 100%;
            font-family: "Montserrat", sans-serif;
        }

        .logo {
            height: 90px;
            /* Высота логотипа */
        }

        nav {
            display: flex;
            gap: 60px;
        }

        nav a {
            text-decoration: none;
            font-size: 24px;
            font-weight: bold;
            color: white;
        }

        nav a:hover {
            color: #08003b;
        }

        .cerb img {
            margin-left: 5px;
            transition: fill 0.3s;
            fill: white;
        }

        footer {
            margin-top: 2%;
            background-color: #08003b;
            color: white;
            display: flex;
            text-align: start;
            justify-content: space-between;
            width: 100%;
            padding: 0 1%;
            font-size: 24px;
        }

        .footer-section {
            margin: 0;
            margin-bottom: 10px;
        }

        .footer-section p {
            font-weight: bold;
            font-size: 28px;
            text-decoration: underline;
            margin: 3% 0;
        }

        .footer-section ul {
            list-style-type: none;
            padding: 0;
            margin: 3% 0;
        }

        .footer-section li {
            margin: 3px 0;
            opacity: 70%;
        }

        .footer-section a {
            color: white;
            text-decoration: none;
        }

        .footer-section a:hover {
            text-decoration: underline;
        }

        .social-icons a {
            margin-right: 2%;
        }

        .social-icons img {
            width: 50px;
            height: 50px;
        }

        .men {
            display: none;
        }

        article {
            min-height: 100%;
            display: grid;
            grid-template-rows: auto 1fr auto;
            grid-template-columns: 100%;
        }

        @media (width<1000px) {
            .products {
                grid-template-columns: 1fr 1fr 1fr;
            }
        }

        @media (width<880px) {
            .scrollbar {
                font-size: smaller;
            }
        }

        @media screen and (max-width: 768px) {
            .modal-content {
                width: 95%;
                margin: 5% auto;
            }

            .knop {
                align-items: center;
            }

            .modal-body {
                flex-direction: column;
                align-items: stretch;
            }

            .modal-image {
                max-width: 100%;
                margin-right: 0;
                margin-bottom: 20px;
            }

            .modal-details {
                text-align: center;
            }
        }

        @media (width <=755px) {
            .burger {
                display: block;
                padding: 10px;
            }

            .men {
                display: block;
                font-family: "Montserrat", sans-serif;
                background-color: #3693d7;
                padding-top: 2%;
                max-height: 70%;
            }

            header {
                display: none;
            }

            .products {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }

            main {
                display: block;
            }

            .scrollbar {
                display: grid;
                grid-template-columns: 1fr;
                margin-bottom: 2%;
                border: none;
            }

            .razdel {
                padding: 2% 0;
                text-align: center;
                border-bottom: 2px solid #dedede;
            }

            .cerb {
                color: #000000;
            }

            .category-header {
                font-size: 18px;
            }

            .subcategory-item {
                font-size: 16px;
            }

            .sorting button {
                font-size: 20px;
            }

            .slide {
                margin-left: 5%;
                border: 2px solid #62208a;
                border-radius: 20px;
                padding-left: 8%;
                padding-top: 5%;
            }
        }

        @media (width<960px) {
            .footer {
                font-size: 22px;
            }

            .footer-section p {
                font-size: 24px;
            }

            .social-icons img {
                width: 35px;
                /* Размер иконок соцсетей */
                height: 35px;
            }
        }

        @media (width<580px) {
            .footer {
                flex-direction: column;
            }

            .stolbi {
                flex-direction: column;
            }

            .footer p,
            ul {
                margin: 1.5% 0;
                font-size: smaller;
            }

            .footer-section {
                width: 90%;
                margin: auto;
            }
        }

        @media (width<430px) {

            .modal-order-button,
            .btn {
                padding: 6px 10px;
                border-radius: 8px;
                min-width: 160px;
                min-height: 40px;
            }
        }

        /*доработать стилизацию*/
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
    <?php include 'php/header.php'; ?>

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
                                                value="<?= htmlspecialchars($subCat) ?>"
                                                <?= in_array($subCat, $filters['categories']) ? 'checked' : '' ?>>
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
    <footer><?php include 'php/footer.php'; ?></footer>
</body>

</html>