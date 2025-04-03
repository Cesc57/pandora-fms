<?php

include('../includes/db_connection.php');

if (isset($_POST['dni'])) {
    $dni = $_POST['dni'];

    $sql = "SELECT id FROM patients WHERE dni = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {

        $stmt->bind_param("s", $dni);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo 'exists';
        } else {
            echo 'not-exists';
        }

        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
