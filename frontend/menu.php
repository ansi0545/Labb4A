<?php
// menu.php
$categories = [];
$categorySql = 'SELECT * FROM categories ORDER BY id ASC';
// Rest of the code to fetch and display categories

$categoryResult = mysqli_query($connection, $categorySql);
if ($categoryResult) {
    $categories = mysqli_fetch_all($categoryResult, MYSQLI_ASSOC);
    mysqli_free_result($categoryResult);
} else {
    echo "Error fetching categories: " . mysqli_error($connection);
}

// Hämta kategorier för dropdown
$categorySql = 'SELECT * FROM categories ORDER BY name ASC';
$categoryResult = mysqli_query($connection, $categorySql);
$categories = mysqli_fetch_all($categoryResult, MYSQLI_ASSOC);

$categoryColors = [
    'Allt om hundar' => 'linear-gradient(135deg, #3b82f6, #ef4444, #16a34a, #eab308)',
    'Hundträning' => '#3b82f6',
    'Hundfoder' => '#16a34a',
    'Barmarksdrag' => '#ef4444',
    'Hundutrustning' => '#eab308',
    'Agility' => '#db2777',
    'Rallylydnad' => '#14b8a6',
];

