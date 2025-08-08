-- Initialize FHIR-DICOM database
-- This script creates the initial database setup for the FHIR-DICOM system

-- Create extensions if needed
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- Create initial database if it doesn't exist
-- (This is typically handled by the POSTGRES_DB environment variable)

-- Set up initial configuration
DO $$
BEGIN
    -- Create application user if needed
    IF NOT EXISTS (SELECT 1 FROM pg_roles WHERE rolname = 'fhir_app') THEN
        CREATE ROLE fhir_app WITH LOGIN PASSWORD 'fhir_app_password';
    END IF;
    
    -- Grant permissions
    GRANT CONNECT ON DATABASE fhir_dicom_db TO fhir_app;
    GRANT USAGE ON SCHEMA public TO fhir_app;
    GRANT CREATE ON SCHEMA public TO fhir_app;
END
$$;