-- Adds a table to store legally required right-of-withdrawal (Widerruf)
-- form submissions from buyers, used by the new widerruf.php page and
-- admin/view_refunds.php.

CREATE TABLE IF NOT EXISTS `withdrawal_notices` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `seller_id` INT NOT NULL,
  `order_number` VARCHAR(100) NULL,
  `full_name` VARCHAR(255) NOT NULL,
  `address` TEXT NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `ordered_date` VARCHAR(50) NULL,
  `received_date` VARCHAR(50) NULL,
  `reason` TEXT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'new',
  `date` VARCHAR(50) NOT NULL
);
