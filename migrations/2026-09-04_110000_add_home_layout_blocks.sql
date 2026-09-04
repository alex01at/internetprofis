-- Adds a configurable homepage layout: which sections appear, in what
-- order, and whether they're enabled, plus the ability to have more than
-- one independent "Cards" or "Boxes" section with different content.
-- Selectable in Admin -> Settings -> Theme Settings -> Homepage Layout.

CREATE TABLE IF NOT EXISTS `home_layout_blocks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `language_id` INT NOT NULL,
  `block_type` VARCHAR(20) NOT NULL,
  `position` INT NOT NULL,
  `enabled` VARCHAR(3) NOT NULL DEFAULT 'yes'
);

ALTER TABLE `home_cards` ADD COLUMN `block_id` INT NULL;
ALTER TABLE `section_boxes` ADD COLUMN `block_id` INT NULL;

-- One default block per section type per language that already has a
-- home_section row, in today's fixed render order. Reproduces current
-- behavior exactly - nothing changes visually until an admin uses the
-- new controls.
INSERT INTO `home_layout_blocks` (`language_id`, `block_type`, `position`, `enabled`)
SELECT `language_id`, 'hero', 1, 'yes' FROM `home_section`
UNION ALL
SELECT `language_id`, 'cards', 2, 'yes' FROM `home_section`
UNION ALL
SELECT `language_id`, 'categories', 3, 'yes' FROM `home_section`
UNION ALL
SELECT `language_id`, 'boxes', 4, 'yes' FROM `home_section`
UNION ALL
SELECT `language_id`, 'proposals', 5, 'yes' FROM `home_section`;

-- Point existing cards/boxes at their language's new default block.
UPDATE `home_cards` hc
JOIN `home_layout_blocks` b ON b.language_id = hc.language_id AND b.block_type = 'cards'
SET hc.block_id = b.id
WHERE hc.block_id IS NULL;

UPDATE `section_boxes` sb
JOIN `home_layout_blocks` b ON b.language_id = sb.language_id AND b.block_type = 'boxes'
SET sb.block_id = b.id
WHERE sb.block_id IS NULL;
