CREATE DATABASE IF NOT EXISTS mydatabase;
USE mydatabase;

CREATE TABLE IF NOT EXISTS patients
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(255) NOT NULL,
    dni        VARCHAR(20)  NOT NULL UNIQUE,
    phone      VARCHAR(20)  NOT NULL,
    email      VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE appointments
(
    id               INT AUTO_INCREMENT PRIMARY KEY,
    patient_id       INT,
    appointment_type VARCHAR(50),
    appointment_date DATE,
    appointment_hour TIME,
    FOREIGN KEY (patient_id) REFERENCES patients (id)
);