-- Car Catalog database
-- Import with phpMyAdmin (Import tab) or command line:
--   C:\xampp\mysql\bin\mysql.exe -u root < database\cars.sql
-- Re-running this file is safe: it recreates the `car_catalog` database
-- and re-seeds the original 10 cars with stable ids (1-10).

CREATE DATABASE IF NOT EXISTS `car_catalog`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `car_catalog`;

DROP TABLE IF EXISTS `cars`;

CREATE TABLE `cars` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `year` SMALLINT UNSIGNED NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `engine` VARCHAR(100) NOT NULL,
  `horsepower` SMALLINT UNSIGNED NOT NULL,
  `torque_nm` SMALLINT UNSIGNED NOT NULL,
  `drivetrain` VARCHAR(50) NOT NULL,
  `transmission` VARCHAR(100) NOT NULL,
  `zero_to_hundred_sec` DECIMAL(4,1) NOT NULL,
  `top_speed_kmh` SMALLINT UNSIGNED NOT NULL,
  `fuel_economy_l_100km` DECIMAL(4,1) NOT NULL,
  `seats` TINYINT UNSIGNED NOT NULL,
  `price_usd` INT UNSIGNED NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed data (stable ids; matches the legacy cars.json sample,
-- with the "Couuce" typo corrected to "Coupe").
INSERT INTO `cars`
  (`id`, `name`, `year`, `type`, `engine`, `horsepower`, `torque_nm`, `drivetrain`, `transmission`, `zero_to_hundred_sec`, `top_speed_kmh`, `fuel_economy_l_100km`, `seats`, `price_usd`, `image`, `description`)
VALUES
  (1, 'Toyota Corolla', 2024, 'Sedan', '1.8L I4', 139, 171, 'FWD', 'CVT', 9.3, 195, 6.5, 5, 23000, 'uploads/corolla.jpg', 'Reliable compact sedan known for efficiency and low running costs.'),
  (2, 'Honda Civic', 2024, 'Sedan', '1.5L Turbo I4', 180, 240, 'FWD', 'CVT', 7.8, 210, 6.3, 5, 26000, 'uploads/civic.jpg', 'Sporty compact sedan with a refined interior and strong safety scores.'),
  (3, 'Ford Mustang', 2024, 'Coupe', '5.0L V8', 450, 529, 'RWD', '6-speed manual', 4.5, 250, 12.0, 4, 45000, 'uploads/mustang.jpg', 'Iconic American muscle car with powerful V8 performance.'),
  (4, 'BMW 3 Series', 2024, 'Sedan', '2.0L Turbo I4', 255, 400, 'RWD', '8-speed automatic', 5.8, 250, 7.0, 5, 47000, 'uploads/bmw3.jpg', 'Luxury sport sedan balancing performance, comfort, and technology.'),
  (5, 'Mercedes-Benz C-Class', 2024, 'Sedan', '2.0L Turbo I4 (mild hybrid)', 255, 400, 'RWD', '9-speed automatic', 6.0, 250, 7.1, 5, 52000, 'uploads/mercedes_cclass.jpg', 'Premium compact luxury sedan with advanced infotainment.'),
  (6, 'Audi A4', 2024, 'Sedan', '2.0L Turbo I4', 201, 320, 'AWD (quattro)', '7-speed dual-clutch', 6.9, 210, 7.2, 5, 46000, 'uploads/audi_a4.jpg', 'Well-rounded luxury sedan with confident handling and AWD traction.'),
  (7, 'Tesla Model 3', 2024, 'Electric Sedan', 'Dual Motor EV', 430, 550, 'AWD', 'Single-speed', 4.2, 233, 0.0, 5, 42000, 'uploads/tesla_model3.jpg', 'All-electric sedan with quick acceleration and over-the-air updates.'),
  (8, 'Hyundai Tucson', 2024, 'SUV', '2.5L I4', 187, 241, 'FWD', '8-speed automatic', 9.0, 190, 7.9, 5, 30000, 'uploads/hyundai_tucson.jpg', 'Practical compact SUV with ample safety tech and cargo space.'),
  (9, 'Kia Sportage', 2024, 'SUV', '1.6L Turbo I4 (hybrid)', 227, 350, 'FWD', '6-speed automatic', 7.9, 200, 6.0, 5, 32000, 'uploads/kia_sportage.jpg', 'Stylish compact SUV with efficient hybrid option.'),
  (10, 'Jeep Wrangler', 2024, 'SUV', '3.6L V6', 285, 353, '4x4', '8-speed automatic', 7.6, 180, 11.5, 5, 39000, 'uploads/jeep_wrangler.jpg', 'Rugged off-roader with removable doors and roof for open-air fun.');
