/* Load Sample Data
 * ========================================================================== */

USE `KrustyKookies`;

/* Customers
 * ------------------------------------------------------ */
INSERT INTO `customers` (
  `name`,
  `address`
)
VALUES ("Finkakor AB", " Helsingborg"),
       ("Småbröd AB", "Malmö"),
       ("Kaffebröd AB", "Landskrona"),
       ("Bjudkakor AB", "Ystad"),
       ("Kalaskakor AB", "Trelleborg"),
       ("Partykakor AB", "Kristianstad"),
       ("Gästkakor AB", "Hässleholm"),
       ("Skånekakor AB", "Perstorp");


/* Raw Materials
 * ------------------------------------------------------
 * Add the basic raw materials needed for recipes, below.
 *
 * It is assumed that autoincrement starts at 1.
 */
INSERT INTO `raw_materials` (
  `name`,
  `amount_in_stock`
) VALUES ("Bread crumbs", 10000),           # id == 1
         ("Butter", 10000),                 # id == 2
         ("Chopped almonds", 10000),        # id == 3
         ("Chocolate", 10000),              # id == 4
         ("Cinnamon", 10000),               # id == 5
         ("Eggs", 10000),                   # id == 6
         ("Egg whites", 10000),             # id == 7
         ("Fine-ground nuts", 10000),       # id == 8
         ("Flour", 10000),                  # id == 9
         ("Ground, roasted nuts", 10000),   # id == 10
         ("Icing sugar", 10000),            # id == 11
         ("Marzipan", 10000),               # id == 12
         ("Potato starch", 10000),          # id == 13
         ("Roasted, chop nuts", 10000),     # id == 14
         ("Sodium bicarbonate", 10000),     # id == 15
         ("Sugar", 10000),                  # id == 16
         ("Vanilla", 10000),                # id == 17
         ("Vanilla sugar", 10000),          # id == 18
         ("Wheat flour", 10000);            # id == 19


/* Recipes
 * ------------------------------------------------------
 * Each recipe is added to `recipes`, then ingredients are added to the recipe
 * via `recipe_ingredients`.
 *
 * It is assumed that autoincrement starts at 1.
 *
 * 1 dl = 10 grams, keep everything in grams
 */
INSERT INTO `recipes` (
  `name`
)
VALUES ("Nut Ring"),                        # id == 1
       ("Nut Cookie"),                      # id == 2
       ("Amneris"),                         # id == 3
       ("Tango"),                           # id == 4
       ("Almond delight"),                  # id == 5
       ("Berliner");                        # id == 6

INSERT INTO `recipe_ingredients` (
  `recipe_id`,
  `raw_material_id`,
  `amount`
)
VALUES (1, 9, 450),                         # Nut ring
       (1, 2, 450),
       (1, 11, 190),
       (1, 14, 225),

       (2, 8, 750),                         # Nut cookie
       (2, 10, 625),
       (2, 1, 125),
       (2, 16, 375),
       (2, 7, 350),
       (2, 4, 50),

       (3, 12, 750),                        # Amneris
       (3, 2, 250),
       (3, 6, 250),
       (3, 13, 25),
       (3, 19, 25),

       (4, 2, 200),                         # Tango
       (4, 16, 250),
       (4, 9, 300),
       (4, 15, 4),
       (4, 17, 2),

       (5, 2, 400),                         # Almond delight
       (5, 16, 270),
       (5, 3, 279),
       (5, 9, 400),
       (5, 5, 10),

       (6, 9, 350),                         # Berliner
       (6, 2, 250),
       (6, 11, 100),
       (6, 6, 50),
       (6, 18, 5),
       (6, 4, 50);