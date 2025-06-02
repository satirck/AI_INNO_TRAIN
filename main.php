<?php
header('Content-Type: text/html; charset=utf-8');

function checkProduct($product) {
    $defects = [];
    
    // Проверка title
    if (empty($product['title'])) {
        $defects[] = "Пустое название товара";
    }
    
    // Проверка price
    if (!isset($product['price']) || $product['price'] < 0) {
        $defects[] = "Некорректная цена: " . ($product['price'] ?? 'отсутствует');
    }
    
    // Проверка rating.rate
    if (!isset($product['rating']['rate']) || $product['rating']['rate'] > 5) {
        $defects[] = "Некорректный рейтинг: " . ($product['rating']['rate'] ?? 'отсутствует');
    }
    
    return $defects;
}

$url = 'https://fakestoreapi.com/products';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Статистика проверки
$stats = [
    'total_products' => 0,
    'defects' => [
        'title' => 0,
        'price' => 0,
        'rating' => 0
    ],
    'products_with_defects' => 0
];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Проверка данных API</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .status {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .product-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .defects {
            color: #dc3545;
            margin-top: 10px;
            padding: 10px;
            background-color: #f8d7da;
            border-radius: 4px;
        }
        .product-info {
            display: grid;
            grid-template-columns: 100px 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }
        .product-image {
            width: 100px;
            height: 100px;
            object-fit: contain;
        }
        .stats-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 15px;
        }
        .stat-item {
            padding: 15px;
            border-radius: 5px;
            background: #f8f9fa;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #0d6efd;
        }
        .stat-label {
            color: #6c757d;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Результаты проверки данных API</h1>
        
        <div class="status <?php echo $httpCode === 200 ? 'success' : 'error'; ?>">
            <h2>Статус ответа сервера: <?php echo $httpCode; ?></h2>
            <?php if ($httpCode !== 200): ?>
                <p>Ошибка: Сервер вернул неожиданный код ответа</p>
            <?php endif; ?>
        </div>

        <?php
        if ($response === false) {
            echo '<div class="error">Ошибка при получении данных от API</div>';
        } else {
            $products = json_decode($response, true);
            $productsWithDefects = [];
            $stats['total_products'] = count($products);
            
            foreach ($products as $product) {
                $defects = checkProduct($product);
                
                // Обновляем статистику
                if (empty($product['title'])) $stats['defects']['title']++;
                if (!isset($product['price']) || $product['price'] < 0) $stats['defects']['price']++;
                if (!isset($product['rating']['rate']) || $product['rating']['rate'] > 5) $stats['defects']['rating']++;
                
                if (!empty($defects)) {
                    $stats['products_with_defects']++;
                    $productsWithDefects[] = [
                        'product' => $product,
                        'defects' => $defects
                    ];
                }
            }
            
            // Выводим статистику
            echo '<div class="stats-section">';
            echo '<h2>Статистика проверки</h2>';
            echo '<div class="stats-grid">';
            echo '<div class="stat-item">';
            echo '<div class="stat-value">' . $stats['total_products'] . '</div>';
            echo '<div class="stat-label">Всего товаров</div>';
            echo '</div>';
            echo '<div class="stat-item">';
            echo '<div class="stat-value">' . $stats['products_with_defects'] . '</div>';
            echo '<div class="stat-label">Товаров с дефектами</div>';
            echo '</div>';
            echo '<div class="stat-item">';
            echo '<div class="stat-value">' . $stats['defects']['title'] . '</div>';
            echo '<div class="stat-label">Пустых названий</div>';
            echo '</div>';
            echo '<div class="stat-item">';
            echo '<div class="stat-value">' . $stats['defects']['price'] . '</div>';
            echo '<div class="stat-label">Некорректных цен</div>';
            echo '</div>';
            echo '<div class="stat-item">';
            echo '<div class="stat-value">' . $stats['defects']['rating'] . '</div>';
            echo '<div class="stat-label">Некорректных рейтингов</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            
            if (empty($productsWithDefects)) {
                echo '<div class="success">Все товары прошли проверку успешно!</div>';
            } else {
                echo '<h2>Найдены товары с дефектами:</h2>';
                foreach ($productsWithDefects as $item) {
                    $product = $item['product'];
                    echo '<div class="product-card">';
                    echo '<div class="product-info">';
                    echo '<img src="' . htmlspecialchars($product['image']) . '" class="product-image" alt="' . htmlspecialchars($product['title']) . '">';
                    echo '<div>';
                    echo '<h3>' . htmlspecialchars($product['title']) . '</h3>';
                    echo '<p>Цена: $' . number_format($product['price'], 2) . '</p>';
                    echo '<p>Рейтинг: ' . number_format($product['rating']['rate'], 1) . '/5</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="defects">';
                    echo '<strong>Найденные дефекты:</strong><br>';
                    foreach ($item['defects'] as $defect) {
                        echo '- ' . htmlspecialchars($defect) . '<br>';
                    }
                    echo '</div>';
                    echo '</div>';
                }
            }
        }
        ?>
    </div>
</body>
</html>