<?php
session_start();
require_once "conn.php";

function getFilteredPurchases($sort, $filter) {
    global $config;
    $username = $_SESSION["username"];
    $query = "SELECT * FROM animals WHERE username = ?";
    $params = [$username];
    $types = "s";

    if ($filter !== "all") {
        $query .= " AND animal = ?";
        $params[] = $filter;
        $types .= "s";
    }

    if ($sort === "oldest") {
        $query .= " ORDER BY time_date ASC";
    } else {
        $query .= " ORDER BY time_date DESC";
    }

    $stmt = $config->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result();
}

$sort = $_GET['sort'] ?? 'newest';
$filter = $_GET['filter'] ?? 'all';

$purchases = [];
$result = getFilteredPurchases($sort, $filter);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $purchases[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Purchases</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Your existing CSS remains unchanged... */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('pictures/raccoon.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            overflow-y: auto;
        }

        .purchase-container {
            max-width: 800px;
            margin: 80px auto;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            position: relative;
        }

        h1 {
            text-align: center;
            color: #187795;
            margin-bottom: 20px;
        }

        .sort-filter-form {
            text-align: center;
            margin-bottom: 30px;
        }

        .sort-filter-form select {
            padding: 8px;
            margin: 0 10px;
            font-size: 16px;
        }

        .purchase-card {
            border-left: 5px solid #187795;
            background: #f9f9f9;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            transition: transform 0.2s ease;
        }

        .purchase-card:hover {
            transform: scale(1.02);
        }

        .purchase-title {
            font-size: 22px;
            font-weight: bold;
            color: #187795;
        }

        .purchase-info {
            font-size: 15px;
            color: #444;
            margin-top: 5px;
        }

        .purchase-icon {
            font-size: 28px;
            margin-right: 8px;
            vertical-align: middle;
        }

        .no-purchases {
            text-align: center;
            font-size: 20px;
            color: #187795;
            margin: 40px 0;
        }

        .buttons {
            text-align: center;
            margin-top: 40px;
        }

        .btn {
            display: inline-block;
            margin: 10px;
            padding: 12px 25px;
            background-color: #187795;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #145f75;
        }

        .newest-badge {
            background: #187795;
            color: white;
            font-size: 12px;
            padding: 3px 8px;
            border-radius: 6px;
            margin-left: 10px;
        }

        .refund-btn, .update-btn {
            display: inline-block;
            margin-top: 10px;
            margin-right: 10px;
            padding: 6px 14px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .refund-btn {
            background-color: #c0392b;
        }

        .refund-btn:hover {
            background-color: #96281b;
        }

        .update-btn {
            background-color: #2980b9;
        }

        .update-btn:hover {
            background-color: #21618c;
        }
    </style>
    <script>
        function confirmRefund(purchaseId) {
            const confirmed = confirm("Are you sure you want to refund this purchase?");
            if (confirmed) {
                window.location.href = `refundPurchase.php?purchase_id=${purchaseId}`;
            }
        }

        function goToUpdate(purchaseId) {
            window.location.href = `updatePurchase.php?purchase_id=${purchaseId}`;
        }

        function submitSortFilterForm() {
            document.getElementById('sortFilterForm').submit();
        }
    </script>
</head>

<body>
<div class="purchase-container">
    <h1>🐾 My Animal Purchases</h1>

    <form id="sortFilterForm" class="sort-filter-form" method="get">
        <label for="sort">Sort by:</label>
        <select name="sort" id="sort" onchange="submitSortFilterForm()">
            <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
            <option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>Oldest</option>
        </select>

        <label for="filter">Filter by animal:</label>
        <select name="filter" id="filter" onchange="submitSortFilterForm()">
            <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>All</option>
            <option value="dog" <?= $filter === 'dog' ? 'selected' : '' ?>>Dog</option>
            <option value="cat" <?= $filter === 'cat' ? 'selected' : '' ?>>Cat</option>
            <option value="bird" <?= $filter === 'bird' ? 'selected' : '' ?>>Bird</option>
            <option value="horse" <?= $filter === 'horse' ? 'selected' : '' ?>>Horse</option>
            <option value="cow" <?= $filter === 'cow' ? 'selected' : '' ?>>Cow</option>
            <option value="other" <?= $filter === 'other' ? 'selected' : '' ?>>Other</option>
        </select>
    </form>

    <?php if (count($purchases) > 0): ?>
        <?php foreach ($purchases as $index => $purchase): ?>
            <div class="purchase-card" style="<?= $index === 0 ? 'background-color:#e8f8f5;' : ''; ?>">
                <div class="purchase-title">
                    <span class="purchase-icon">🦴</span>
                    Animal: <?= ucfirst(htmlspecialchars($purchase['animal'])); ?>
                    <?php if ($index === 0 && $sort === 'newest'): ?>
                        <span class="newest-badge">Newest</span>
                    <?php endif; ?>
                </div>
                <div class="purchase-info">Purchase ID: <?= htmlspecialchars($purchase['purchase_id']); ?></div>
                <div class="purchase-info">Purchased on: <?= htmlspecialchars($purchase['time_date']); ?></div>
                <div class="purchase-info">
                    <a class="refund-btn" href="javascript:void(0);" onclick="confirmRefund('<?= urlencode($purchase['purchase_id']); ?>')">Refund</a>
                    <a class="update-btn" href="javascript:void(0);" onclick="goToUpdate('<?= urlencode($purchase['purchase_id']); ?>')">Update</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-purchases">
            You haven't purchased any animals yet! 🐾
        </div>
    <?php endif; ?>

    <div class="buttons">
        <a href="index.php" class="btn">Return Home</a>
        <a href="makePurchase.php" class="btn">Buy Another</a>
    </div>
</div>
</body>
</html>
