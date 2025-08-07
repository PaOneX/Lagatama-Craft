-- Lagatama Craft sample / mock data
-- Prefer: php tools/seed.php --fresh
-- Or import after schema: mysql -u root -p lagatama_craft < database/seed.sql

USE `lagatama_craft`;

-- Demo credentials (bcrypt):
--   Admin:    admin@lagatama.com / admin123
--   Customer: kamal@example.com  / customer123

INSERT IGNORE INTO `user` (`id`, `fname`, `lname`, `email`, `password`, `mobile`, `joined_date`, `gender_id`, `status`, `user_type_id`, `no`, `line_1`, `line_2`) VALUES
(100, 'Admin', 'User', 'admin@lagatama.com', '$2y$12$KQUSJi/2dRyU45RY244qquhUR.xmV1EwQ2raOuekgih9yq6KtENty', '0771111111', NOW(), 1, 1, 1, '12', 'Main Street', 'Colombo');

-- Product images are copied by tools/seed.php into resources/productImg/
