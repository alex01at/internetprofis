-- Adds a boxed/full-width layout preference for the frontend, selectable
-- in Admin -> Settings -> Theme Settings -> Basic Settings.

ALTER TABLE `general_settings` ADD COLUMN `site_layout` VARCHAR(10) NOT NULL DEFAULT 'full';
