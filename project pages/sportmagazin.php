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

        .categories {
            font-size: 0.8em;
            color: #666;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <header></header>
    <main>
        <div class="products" id="productContainer">
            <?php include 'php/products.php'; ?>
        </div>
    </main>
    <footer></footer>
</body>

</html>