
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'client',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL
);


CREATE TABLE IF NOT EXISTS prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefix VARCHAR(3) NOT NULL UNIQUE,
    operator_name VARCHAR(50) NOT NULL DEFAULT 'MyMoney',
    is_active BOOLEAN NOT NULL DEFAULT 1,
    is_external BOOLEAN DEFAULT 0,
    external_fee_percent DECIMAL(5,2) DEFAULT 0,
    external_min_fee DECIMAL(15,2) DEFAULT 0,
    external_max_fee DECIMAL(15,2) DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL
);


CREATE TABLE IF NOT EXISTS fee_configs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    operation_type VARCHAR(20) NOT NULL,
    min_amount DECIMAL(15,2) NOT NULL,
    max_amount DECIMAL(15,2) NOT NULL,
    fee_amount DECIMAL(15,2) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL
);


CREATE TABLE IF NOT EXISTS clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    msisdn VARCHAR(20) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NULL,
    balance DECIMAL(15,2) NOT NULL DEFAULT 0,
    pin_code VARCHAR(10) NOT NULL DEFAULT '0000',
    status VARCHAR(20) NOT NULL DEFAULT 'active',
    user_id INTEGER NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);


CREATE TABLE IF NOT EXISTS transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    transaction_id VARCHAR(50) NOT NULL UNIQUE,
    sender_id INTEGER NULL,
    receiver_id INTEGER NULL,
    operation_type VARCHAR(20) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    fee_amount DECIMAL(15,2) NOT NULL DEFAULT 0,
    total_amount DECIMAL(15,2) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'completed',
    description TEXT NULL,
    is_external BOOLEAN DEFAULT 0,
    external_operator VARCHAR(50) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES clients(id) ON DELETE SET NULL,
    FOREIGN KEY (receiver_id) REFERENCES clients(id) ON DELETE SET NULL
);


CREATE TABLE IF NOT EXISTS operator_gains (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    transaction_id INTEGER NOT NULL,
    operation_type VARCHAR(20) NOT NULL,
    fee_amount DECIMAL(15,2) NOT NULL,
    gain_date DATE NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS external_transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    transaction_id INTEGER NULL,
    sender_id INTEGER NOT NULL,
    receiver_msisdn VARCHAR(20) NOT NULL,
    receiver_prefix VARCHAR(3) NOT NULL,
    receiver_operator VARCHAR(50) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    base_fee DECIMAL(15,2) NOT NULL DEFAULT 0,
    external_fee DECIMAL(15,2) NOT NULL DEFAULT 0,
    total_fee DECIMAL(15,2) NOT NULL,
    fee_percent DECIMAL(5,2) NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'completed',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES clients(id) ON DELETE CASCADE
);