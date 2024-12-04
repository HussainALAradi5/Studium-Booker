<?php

$studiums = view_studiums(); // Get all studiums

foreach ($studiums as $studium) {
    $average_rating = get_average_rating($studium['studium_id']); // Get average rating
    echo "<div class='studium-card'>";
    echo "<h3>" . htmlspecialchars($studium['studium_name']) . "</h3>";
    echo "<p>Location: " . htmlspecialchars($studium['location']) . "</p>";
    echo "<p>Price per hour: " . htmlspecialchars($studium['price_per_hour']) .  " BD" . "</p>";
    echo "<p>Average Rating: " . $average_rating . " / 5</p>";
    echo "<a href='index.php?action=studium&id=" . $studium['studium_id'] . "'>View Details</a>";

    echo "</div>";
}
