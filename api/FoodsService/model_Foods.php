<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

function fetchFoodsByPreferences($preferences, $pageSize = 10) {
    $products = [];

    if (empty($preferences)) {
        $preferences = ['Vegetarian'];
    }

    foreach ($preferences as $preference) {
        $url = "https://world.openfoodfacts.org/cgi/search.pl?action=process&search_terms=" . urlencode($preference) . "&json=true&page_size=" . $pageSize;
        $json = file_get_contents($url);

        if ($json !== FALSE) {
            $data = json_decode($json, true);

            if (isset($data['products'])) {
                $products = array_merge($products, $data['products']);
            }
        }
    }

    return $products;
}
?>
