CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL,
    product_type VARCHAR(50) NOT NULL
);

-- Inserting product details into the products table
INSERT INTO products (name, price, quantity, product_type)
VALUES 
    ('Product A', 50.00, 100, 'Type 1'),
    ('Product B', 100.00, 150, 'Type 2'),
    ('Product C', 75.00, 80, 'Type 1'),
    ('Product D', 120.00, 200, 'Type 2');

CREATE USER 'admin'@'localhost' IDENTIFIED BY 'spacetech';
