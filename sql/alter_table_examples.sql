-- ALTER TABLE statement template
-- Replace the placeholders with your specific requirements

-- Example 1: Add a simple column
-- ALTER TABLE table_name ADD COLUMN column_name data_type;

-- Example 2: Add column with constraints
-- ALTER TABLE table_name ADD COLUMN column_name data_type NOT NULL DEFAULT 'default_value';

-- Example 3: Add column at specific position
-- ALTER TABLE table_name ADD COLUMN column_name data_type AFTER existing_column;

-- Common examples for your database:

-- Add phone column to usuarios table
-- ALTER TABLE usuarios ADD COLUMN telefone VARCHAR(20) DEFAULT NULL AFTER nome;

-- Add description column to predios table
-- ALTER TABLE predios ADD COLUMN descricao TEXT DEFAULT NULL AFTER nome;

-- Add created_at timestamp to ambientes table
-- ALTER TABLE ambientes ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER checksum;

-- Add last_login to usuarios table
-- ALTER TABLE usuarios ADD COLUMN last_login TIMESTAMP NULL DEFAULT NULL AFTER created_at;

-- Add status column to predios table
-- ALTER TABLE predios ADD COLUMN status TINYINT(1) DEFAULT 1 AFTER responsavel_id;
