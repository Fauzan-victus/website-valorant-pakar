<?php

echo "<h1>Database Test</h1>";

$conn = mysqli_connect("localhost", "root", "", "valorant_pakar");

if ($conn) {
    echo "<p style='color:green;'>✅ Database CONNECTED!</p>";
    
    
    $result = mysqli_query($conn, "SHOW TABLES");
    echo "<h3>Tables in database:</h3>";
    echo "<ul>";
    while($row = mysqli_fetch_array($result)) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color:red;'>❌ Database FAILED: " . mysqli_connect_error() . "</p>";
}
?>