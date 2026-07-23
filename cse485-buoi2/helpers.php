<?php
/**
 * Escape dữ liệu trước khi hiển thị ra HTML.
 */
function e(mixed $value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}
/**
 * Tính thành tiền của một sản phẩm.
 */
function lineTotal(array $product): int
{
    return $product['price'] * $product['qty'];
}

/**
 * Tính tổng giá trị kho của toàn bộ sản phẩm.
 */
function inventoryValue(array $products): int
{
    $totalValue = 0;

    foreach ($products as $product) {
        $totalValue += lineTotal($product);
    }

    return $totalValue;
}

/**
 * Tìm sản phẩm theo SKU.
 */
function findProductBySku(array $products, string $sku): ?array
{
    foreach ($products as $product) {
        if ($product['sku'] === $sku) {
            return $product;
        }
    }

    return null;
}

/**
 * Đếm số sản phẩm thuộc một danh mục.
 */
function countByCategory(array $products, int $categoryId): int
{
    $count = 0;

    foreach ($products as $product) {
        if ($product['category_id'] === $categoryId) {
            $count++;
        }
    }

    return $count;
}

/**
 * Xác định mức tồn kho của sản phẩm.
 */
function stockLevel(array $product): string
{
    if ($product['qty'] >= 5) {
        return 'Du';
    }

    if ($product['qty'] >= 2) {
        return 'Sap het';
    }

    return 'Can nhap';
}

/**
 * Lọc sản phẩm theo danh mục.
 */
function filterByCategory(array $products, ?int $categoryId): array
{
    if ($categoryId === null) {
        return $products;
    }

    $filteredProducts = [];

    foreach ($products as $product) {
        if ($product['category_id'] === $categoryId) {
            $filteredProducts[] = $product;
        }
    }

    return $filteredProducts;
}

/**
 * Xếp quy mô kho dựa trên tổng giá trị.
 */
function rankInventory(int $totalValue): string
{
    if ($totalValue < 15000000) {
        return 'Nho';
    }

    if ($totalValue < 35000000) {
        return 'Trung binh';
    }

    return 'Lon';
}

/**
 * In các dòng sản phẩm trong bảng HTML.
 */
function renderProductRows(array $products, array $categoryMap): void
{
    foreach ($products as $product) {
        $categoryName =
            $categoryMap[$product['category_id']] ?? 'Khong xac dinh';

        echo '<tr>';
        echo '<td>' . e($product['sku']) . '</td>';
        echo '<td>' . e($product['name']) . '</td>';
        echo '<td>' . e($categoryName) . '</td>';
        echo '<td>'
            . number_format((int) $product['price'], 0, ',', '.')
            . ' đ</td>';
        echo '<td>'
            . number_format((int) $product['qty'], 0, ',', '.')
            . '</td>';
        echo '<td>'
            . number_format(lineTotal($product), 0, ',', '.')
            . ' đ</td>';
        echo '<td>' . e(stockLevel($product)) . '</td>';
        echo '</tr>';
    }
}
