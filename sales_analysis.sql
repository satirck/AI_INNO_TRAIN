-- Create and populate the orders table
CREATE TABLE orders (
    id INTEGER PRIMARY KEY,
    customer TEXT,
    amount REAL,
    order_date DATE
);

INSERT INTO orders (customer, amount, order_date) VALUES
('Alice', 5000, '2024-03-01'),
('Bob', 8000, '2024-03-05'),
('Alice', 3000, '2024-03-15'),
('Charlie', 7000, '2024-02-20'),
('Alice', 10000, '2024-02-28'),
('Bob', 4000, '2024-02-10'),
('Charlie', 9000, '2024-03-22'),
('Alice', 2000, '2024-03-30');

-- Task 1: Calculate total sales volume for March 2024
SELECT SUM(amount) as total_march_sales
FROM orders
WHERE strftime('%Y-%m', order_date) = '2024-03';

-- Task 2: Find the customer who spent the most overall
SELECT 
    customer,
    SUM(amount) as total_spent
FROM orders
GROUP BY customer
ORDER BY total_spent DESC
LIMIT 1;

-- Task 3: Calculate average order value for the last three months
SELECT 
    AVG(amount) as average_order_value,
    COUNT(*) as total_orders,
    SUM(amount) as total_sales
FROM orders
WHERE order_date >= date('2024-02-01')
AND order_date <= date('2024-03-31');

-- Additional verification queries
-- Verify March sales breakdown
SELECT 
    customer,
    SUM(amount) as march_sales
FROM orders
WHERE strftime('%Y-%m', order_date) = '2024-03'
GROUP BY customer
ORDER BY march_sales DESC;

-- Verify customer totals
SELECT 
    customer,
    COUNT(*) as number_of_orders,
    SUM(amount) as total_spent,
    AVG(amount) as average_order_value
FROM orders
GROUP BY customer
ORDER BY total_spent DESC; 
