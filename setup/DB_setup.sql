START TRANSACTION;

/* Create Database
 * ========================================================================== */

CREATE DATABASE IF NOT EXISTS `KrustyKookies`
CHARSET=utf8
COLLATE=utf8_unicode_ci ;

USE `KrustyKookies`;


/* Create Tables
 * ========================================================================== */

/* Customers
 * ------------------------------------------------------
 * A replication of the customer list.  Assume it's kept in sync with the
 * existing customer checking and registration process.
 */
CREATE TABLE `customers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) NOT NULL,
  `address` text NOT NULL,
  PRIMARY KEY (`id`)
);

/* Loading Order
 * ------------------------------------------------------
 * Common information for each loading order (packing the truck up).
 */
CREATE TABLE `loading_order` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `truck_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
);

/* Loading Order Items
 * ------------------------------------------------------
 * Specific items in each loading order, tied together by `loading_order_id`.
 */
CREATE TABLE `loading_order_items` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `loading_order_id` int(10) NOT NULL,
  `order_item_id` int(10) NOT NULL,
  `pallet_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `loading_order_id` (`loading_order_id`),
  KEY `order_item_id` (`order_item_id`),
  KEY `pallet_id` (`pallet_id`)
);

/* Order
 * ------------------------------------------------------
 * Common information for each order placed by a customer.
 *
 * `delivery_date` should remain null until the order is delivered.
 */
CREATE TABLE `order` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) NOT NULL,
  `delivery_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`)
);

/* Order Items
 * ------------------------------------------------------
 * Specific items in each order, tied together by `order_id`.
 */
CREATE TABLE `order_items` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `recipe_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `recipe_id` (`recipe_id`)
);

/* Pallets
 * ------------------------------------------------------
 * An inventory of Pallets made in the production process.
 *
 * `blocked` should remain null until a pallet is blocked from delivery.
 */
CREATE TABLE `pallets` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `recipe_id` int(10) NOT NULL,
  `produced_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `checked_out_on` timestamp NULL DEFAULT NULL,
  `blocked` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recipe_id` (`recipe_id`)
);

/* Raw Materials
 * ------------------------------------------------------
 * Tracks the amount of raw materials available for the production process.
 */
CREATE TABLE `raw_materials` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) NOT NULL,
  `amount_in_stock` int(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
);

/* Raw Material Deliveries
 * ------------------------------------------------------
 * Provides a delivery history for each addition to the stock of raw materials.
 */
CREATE TABLE `raw_material_deliveries` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `raw_material_id` int(10) NOT NULL,
  `deliveredAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amount` int(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `raw_material_id` (`raw_material_id`)
);

/* Recipes
 * ------------------------------------------------------
 * Mama's old fashion recipe book, in a database.  So not so old fashioned I
 * guess.
 *
 * Common information for recipes.
 */
CREATE TABLE `recipes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) NOT NULL,
  PRIMARY KEY (`id`)
);

/* Recipe Ingredients
 * ------------------------------------------------------
 * Specific ingredients in each recipe (and the amount), tied together by
 * `recipe_id`.
 */
CREATE TABLE `recipe_ingredients` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `recipe_id` int(10) NOT NULL,
  `raw_material_id` int(10) NOT NULL,
  `amount` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `raw_material_id` (`raw_material_id`),
  KEY `recipe_id` (`recipe_id`)
);


/* Add Constraints
 * ========================================================================== */
ALTER TABLE `loading_order_items`
  ADD CONSTRAINT `loading_order_items_group` FOREIGN KEY (`loading_order_id`) REFERENCES `loading_order` (`id`),
  ADD CONSTRAINT `order_item_fulfilled` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`),
  ADD CONSTRAINT `loading_order_item_pallet` FOREIGN KEY (`pallet_id`) REFERENCES `pallets` (`id`);

ALTER TABLE `order`
  ADD CONSTRAINT `order_for_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_group` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_item_kookie_type` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`);

ALTER TABLE `pallets`
  ADD CONSTRAINT `pallet_kookie_type` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`);

ALTER TABLE `raw_material_deliveries`
  ADD CONSTRAINT `raw_material_type` FOREIGN KEY (`raw_material_id`) REFERENCES `raw_materials` (`id`);

ALTER TABLE `recipe_ingredients`
  ADD CONSTRAINT `recipe_ingredients_group` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recipe_ingredient_materials` FOREIGN KEY (`raw_material_id`) REFERENCES `raw_materials` (`id`);


COMMIT;