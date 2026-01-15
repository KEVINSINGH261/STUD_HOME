-- Add security question columns to utilisateurs table
USE stud_home_db;

ALTER TABLE utilisateurs 
ADD COLUMN security_question VARCHAR(255) DEFAULT NULL AFTER type,
ADD COLUMN security_answer VARCHAR(255) DEFAULT NULL AFTER security_question;
