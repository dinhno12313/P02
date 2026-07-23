<?php

require_once __DIR__ . '/data.php';
require_once __DIR__ . '/helpers.php';

/*
 * Tạo bản đồ danh mục để tra cứu tên danh mục theo ID.
 */
$categoryMap = [];

foreach ($categories as $category) {
    $categoryMap[$category['id']] = $category['name'];
}

/*
 * Đọc category_id từ URL.
 * Nếu không có category_id thì hiển thị toàn bộ sản phẩm.
 */
$categoryId = isset($_GET['category_id'])
    ? (int) $_GET['category_id']
    : null;

$filteredProducts = filterByCategory($products, $categoryId);

/*
 * Tính tổng giá trị và quy mô của toàn bộ kho.
 */
$totalValue = inventoryValue($products);
$inventoryRank = rankInventory($totalValue);

/*
 * Kiểm tra chức năng tìm sản phẩm theo SKU.
 */
$foundProduct = findProductBySku($products, 'MN-02');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MiniShop - Bao cao kho (Buoi 2)</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #222;
        }

        nav {
            margin-bottom: 20px;
        }

        nav a {
            display: inline-block;
            margin-right: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #eeeeee;
        }

        pre {
            padding: 15px;
            background-color: #f5f5f5;
            border: 1px solid #cccccc;
            overflow: auto;
        }
    </style>
</head>

<body>
    <h1>MiniShop - Bao cao kho (Buoi 2)</h1>

    <nav>
        <a href="index.php">Tat ca</a>
        <a href="?category_id=1">Ban phim</a>
        <a href="?category_id=2">Chuot</a>
        <a href="?category_id=3">Man hinh</a>
    </nav>

    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Ten</th>
                <th>Danh muc</th>
                <th>Gia</th>
                <th>So luong</th>
                <th>Thanh tien</th>
                <th>Muc ton</th>
            </tr>
        </thead>

        <tbody>
            <?php renderProductRows($filteredProducts, $categoryMap); ?>
        </tbody>
    </table>

    <p>
        <strong>Tong gia tri kho:</strong>
        <?= number_format($totalValue, 0, ',', '.') ?> đ
    </p>

    <p>
        <strong>Quy mo kho:</strong>
        <?= htmlspecialchars($inventoryRank, ENT_QUOTES, 'UTF-8') ?>
    </p>

    <h2>Bao cao theo danh muc</h2>

    <table>
        <thead>
            <tr>
                <th>Danh muc</th>
                <th>So SP</th>
                <th>Tong gia tri</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($categories as $category): ?>
                <?php
                $currentCategoryId = (int) $category['id'];

                $productCount = countByCategory(
                    $products,
                    $currentCategoryId
                );

                $categoryProducts = filterByCategory(
                    $products,
                    $currentCategoryId
                );

                $categoryTotal = inventoryValue($categoryProducts);
                ?>

                <tr>
                    <td>
                        <?= htmlspecialchars(
                            (string) $category['name'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </td>

                    <td><?= $productCount ?></td>

                    <td>
                        <?= number_format(
                            $categoryTotal,
                            0,
                            ',',
                            '.'
                        ) ?> đ
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Kiem tra findProductBySku</h2>

    <?php if ($foundProduct !== null): ?>
        <p>
            Tim thay
            <?= htmlspecialchars(
                (string) $foundProduct['sku'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>:
            <?= htmlspecialchars(
                (string) $foundProduct['name'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>
    <?php else: ?>
        <p>Khong tim thay san pham co SKU MN-02.</p>
    <?php endif; ?>

    <h2>Debug</h2>

    <pre>
<?php var_dump($filteredProducts); ?>
    </pre>
</body>
</html>
<!-- MS_EXPECT inventory_value=41380000 rank=Lon -->