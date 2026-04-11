-- Sprint 3 - US29 + US30
-- Feature: Revenue statistics + Top selling products

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'pending',
    total_amount DECIMAL(15,2) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_orders_created_at (created_at),
    INDEX idx_orders_status (status),
    INDEX idx_orders_user_id (user_id)
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(15,2) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_order_items_order_id (order_id),
    INDEX idx_order_items_product_id (product_id),
    CONSTRAINT fk_order_items_order
        FOREIGN KEY (order_id) REFERENCES orders(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_order_items_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON DELETE RESTRICT
);

Optional sample data for quick visualization
 INSERT INTO orders (user_id, status, total_amount, created_at) VALUES
 (1, 'completed', 250000, '2026-04-01 10:15:00'),
 (1, 'paid',      180000, '2026-04-02 11:20:00');

 INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES
 (1, 1, 2, 50000),
 (1, 2, 3, 50000),
 (2, 1, 1, 50000),
 (2, 3, 2, 65000);
