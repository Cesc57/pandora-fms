<?php

include('../includes/db_connection.php');

$name = $_POST['name'];
$dni = $_POST['dni'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$appointment_type = $_POST['appointment-type'];

function getNextAvailableAppointment($conn)
{
    $now = new DateTime();
    $currentDate = $now->format('Y-m-d');
    $currentTime = $now->format('H:i:s');

    if (strtotime($currentTime) >= strtotime('22:00:00')) {
        $now->modify('+1 day')->setTime(10, 0, 0);
    }

    $sql = "SELECT id, appointment_date, appointment_hour 
            FROM appointments
            WHERE appointment_date >= '$currentDate' 
              AND (appointment_date > '$currentDate' OR appointment_hour >= '$currentTime')
            ORDER BY appointment_date DESC, appointment_hour DESC
            LIMIT 1";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastAppointmentDate = $row['appointment_date'];
        $lastAppointmentHour = $row['appointment_hour'];

        $newTime = strtotime($lastAppointmentDate . ' ' . $lastAppointmentHour . ' +1 hour');
        $newTimeFormatted = date('Y-m-d H:i:s', $newTime);

        $sqlCheck = "SELECT id 
                    FROM appointments 
                    WHERE appointment_date = '" . date('Y-m-d', $newTime) . "' 
                        AND appointment_hour = '" . date('H:i:s', $newTime) . "'";
        $checkResult = $conn->query($sqlCheck);

        if ($checkResult->num_rows > 0) {
            $newTime = strtotime($newTimeFormatted . ' +1 hour');
            $newTimeFormatted = date('Y-m-d H:i:s', $newTime);
        }

        if (date('H:i:s', $newTime) >= '22:00:00') {
            $now = new DateTime($lastAppointmentDate);
            $now->modify('+1 day')->setTime(10, 0, 0);
            return $now;
        } else {
            return new DateTime($newTimeFormatted);
        }
    } else {
        return $now;
    }
}

$nextAvailableAppointment = getNextAvailableAppointment($conn);

$appointment_date = $nextAvailableAppointment->format('Y-m-d');
$appointment_hour = $nextAvailableAppointment->format('H:i:s');

// Begin transaction to ensure atomicity and avoid conflicts in appointment scheduling
$conn->begin_transaction();

try {
    $sql_check_patient = "SELECT id FROM patients WHERE dni = '$dni'";
    $result = $conn->query($sql_check_patient);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $patient_id = $row['id'];
    } else {
        $sql_insert_patient = "INSERT INTO patients (name, dni, phone, email) 
                                VALUES ('$name', '$dni', '$phone', '$email')";

        if ($conn->query($sql_insert_patient) === TRUE) {
            $patient_id = $conn->insert_id;
        } else {
            throw new Exception("Error: " . $conn->error);
        }
    }

    $sql_insert_appointment = "INSERT INTO appointments (patient_id, appointment_type, appointment_date, appointment_hour) 
                               VALUES ('$patient_id', '$appointment_type', '$appointment_date', '$appointment_hour')";

    if ($conn->query($sql_insert_appointment) !== TRUE) {
        throw new Exception("Error: " . $conn->error);
    }

    // Commit the transaction to save the changes and confirm the appointment
    $conn->commit();
    echo "Appointment booked successfully for $appointment_date at $appointment_hour";

} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

$conn->close();
