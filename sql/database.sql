-- =====================================================
-- Sunrise Community Hospital - Database Export
-- Task 5: Database Implementation
-- Task 11: Database export (.sql)
-- =====================================================

-- Create the database (skip this line if it already exists)
CREATE DATABASE IF NOT EXISTS hospital_management;
USE hospital_management;

-- Task 5: appointments table
CREATE TABLE IF NOT EXISTS appointments (
    appointment_id   INT AUTO_INCREMENT PRIMARY KEY,
    patient_name      VARCHAR(100) NOT NULL,
    national_id       VARCHAR(10)  NOT NULL,
    gender            VARCHAR(10)  NOT NULL,
    phone_number      VARCHAR(15)  NOT NULL,
    email             VARCHAR(100) NOT NULL,
    department        VARCHAR(50)  NOT NULL,
    appointment_date  DATE         NOT NULL,
    created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- A few sample rows so the admin page and API have something to show
-- straight after setup. Feel free to delete these once you test your own.
INSERT INTO appointments
    (patient_name, national_id, gender, phone_number, email, department, appointment_date)
VALUES
    ('Jane Mwangi', '12345678', 'Female', '0712345678', 'jane@example.com', 'Maternity', '2026-10-02'),
    ('Peter Otieno', '23456789', 'Male', '0722345678', 'peter@example.com', 'Dental', '2026-10-05'),
    ('Amina Yusuf', '34567890', 'Female', '0733345678', 'amina@example.com', 'General Medicine', '2026-10-10');
