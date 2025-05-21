<?php
$servername = "127.0.0.1";
$username = "root";
$password = ""; // Assuming no password for local WAMP setup
$dbname = "gestion_clubs";
$port = 3306; // Default MySQL port

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

// Function to check if a column exists
function columnExists($conn, $tableName, $columnName) {
    $result = $conn->query("SHOW COLUMNS FROM `$tableName` LIKE '$columnName'");
    return $result && $result->num_rows > 0;
}

// Add Poster_URL column if it doesn't exist
if (!columnExists($conn, 'activite', 'Poster_URL')) {
    $sql_add_poster_url = "ALTER TABLE activite ADD Poster_URL VARCHAR(255) NULL AFTER responsable_notifie";
    if ($conn->query($sql_add_poster_url) === TRUE) {
        echo "Column Poster_URL added successfully.\n";
    } else {
        echo "Error adding column Poster_URL: (" . $conn->errno . ") " . $conn->error . "\n";
    }
} else {
    echo "Column Poster_URL already exists.\n";
}

// Add nombre_max column if it doesn't exist
if (!columnExists($conn, 'activite', 'nombre_max')) {
    // Ensure Poster_URL exists before trying to add nombre_max AFTER it, or place it differently if Poster_URL might not exist
    $sql_add_nombre_max = "ALTER TABLE activite ADD nombre_max INT DEFAULT NULL COMMENT 'Nombre maximum de participants' AFTER Poster_URL";
    if (columnExists($conn, 'activite', 'Poster_URL')) { // Check again in case it was just added
        if ($conn->query($sql_add_nombre_max) === TRUE) {
            echo "Column nombre_max added successfully.\n";
        } else {
            echo "Error adding column nombre_max: (" . $conn->errno . ") " . $conn->error . "\n";
        }
    } else {
        // Fallback if Poster_URL couldn't be added or wasn't there for some reason
        $sql_add_nombre_max_fallback = "ALTER TABLE activite ADD nombre_max INT DEFAULT NULL COMMENT 'Nombre maximum de participants' AFTER responsable_notifie";
        if ($conn->query($sql_add_nombre_max_fallback) === TRUE) {
            echo "Column nombre_max added successfully (after responsable_notifie as Poster_URL was not found).\n";
        } else {
            echo "Error adding column nombre_max (fallback): (" . $conn->errno . ") " . $conn->error . "\n";
        }
    }
} else {
    echo "Column nombre_max already exists.\n";
}

$conn->close();
?>