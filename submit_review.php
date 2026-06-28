<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name   = $con->real_escape_string($_POST['name']);
    $rating = (int)$_POST['rating'];
    $review = $con->real_escape_string($_POST['review']);

    $sql = "INSERT INTO reviews (name, rating, review, created_at) 
            VALUES ('$name', '$rating', '$review', NOW())";

    if ($con->query($sql)) {
        header("Location: reviews.php?success=1");
        exit;
    } else {
        echo "Error: " . $con->error;
    }
}
?>