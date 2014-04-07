/* Load Sample Data
 * ==========================================================================
 * It is assumed that autoincrement starts at 1.
 */

USE `KrustyKookies`;

/* Customers
 * ------------------------------------------------------ */
INSERT INTO `customers` (
  `name`,
  `address`
)
VALUES ("Finkakor AB", " Helsingborg"),     # id == 1
       ("Småbröd AB", "Malmö"),             # id == 2
       ("Kaffebröd AB", "Landskrona"),      # id == 3
       ("Bjudkakor AB", "Ystad"),           # id == 4
       ("Kalaskakor AB", "Trelleborg"),     # id == 5
       ("Partykakor AB", "Kristianstad"),   # id == 6
       ("Gästkakor AB", "Hässleholm"),      # id == 7
       ("Skånekakor AB", "Perstorp");       # id == 8


/* Raw Materials
 * ------------------------------------------------------
 * Add the basic raw materials needed for recipes, below.
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


/* Orders
 * ------------------------------------------------------
 * Order 1 is unfulfilled, and has not pallets produced for it yet.
 *
 * Orders 2 & 3 are fulfilled below in Loading Orders.
 *
 * Order 4 is unfulfilled but has pallets produced for it- just waiting on
 * a loading order to be created.
 */
INSERT INTO `order` (
  `customer_id`,
  `delivery_date`
)
VALUES (1, DATE("2014-06-05")),             # id == 1
       (1, DATE("2013-04-20")),             # id == 2
       (2, DATE("2013-04-20")),             # id == 3
       (2, DATE("2014-04-27"));             # id == 4

INSERT INTO `order_items` (
  `order_id`,
  `recipe_id`
)
VALUES                            # order 1
                                       # 3 pallets of Nut Ring
       (1, 1),                              # id == 1
       (1, 1),                              # id == 2
       (1, 1),                              # id == 3
                                       # 2 pallets of Nut Cookie
       (1, 2),                              # id == 4
       (1, 2),                              # id == 5
                                       # 1 pallet of Almond delights
       (1, 5),                              # id == 6

                                  # order 2
                                       # moar nutty cookies
       (2, 1),                              # id == 7
       (2, 2),                              # id == 8
       (2, 5),                              # id == 9

                                  # order 3
                                       # 3 pallets of Tangos
       (3, 4),                              # id == 10
       (3, 4),                              # id == 11
       (3, 4),                              # id == 12

                                  # order 4
                                       # 3 pallet of Berliners
       (4, 6),                              # id == 13
       (4, 6),                              # id == 14
       (4, 6);                              # id == 15


/* Pallets
 * ------------------------------------------------------
 * Pallets have been created for:
 *
 *   * the fulfillment of orders 2 & 3 (see loading orders below)
 *   * order 4, which has pallets but is yet to be fulfilled
 *   * recent pallets, to test that oldest pallets are used first and searches
 *   * blocked pallets, to test that these are not used to fulfill an order and searches
 *   * some extra pallets to test searches and date orderings and things
 *
 * Note: there is not pallet for recipe #3 (Amneris), which is used to test
 * the empty result set when searching by type.
 */
INSERT INTO `pallets` (
  `recipe_id`,
  `produced_on`,
  `checked_out_on`,
  `blocked`
)
VALUES                                                # fill order_items for loading_order_items below
                                                          # order 2
       (1, "2013-04-15", CURRENT_TIMESTAMP, 0),                 # id == 1
       (2, "2013-04-15", CURRENT_TIMESTAMP, 0),                 # id == 2
       (5, "2013-04-15", CURRENT_TIMESTAMP, 0),                 # id == 3

                                                           # order 3
       (4, "2013-04-15", CURRENT_TIMESTAMP, 0),                 # id == 4
       (4, "2013-04-15", CURRENT_TIMESTAMP, 0),                 # id == 5
       (4, "2013-04-15", CURRENT_TIMESTAMP, 0),                 # id == 6

                                                           # order 4
       (6, "2014-04-17", CURRENT_TIMESTAMP, 0),                 # id == 7
       (6, "2014-04-16", CURRENT_TIMESTAMP, 0),                 # id == 8
       (6, "2014-04-15", CURRENT_TIMESTAMP, 0),                 # id == 9

                                                      # more recent pallets
       (6, "2014-04-19", CURRENT_TIMESTAMP, 0),                 # id == 10
                                                                # Note the date is more recent than those of order #4's pallets,
                                                                # so this pallet shouldn't be used to fulfill that order.

                                                      # some blocked pallets
       (1, "2014-04-21", NULL, 1),                              # id == 11
       (2, "2014-04-21", NULL, 1),                              # id == 12
       (4, "2014-04-21", NULL, 1),                              # id == 13
       (5, "2014-04-21", NULL, 1),                              # id == 14
       (6, "2014-04-14", NULL, 1),                              # id == 15
                                                                # Note the date is less recent than those of order #4's pallets,
                                                                # but this pallet is blocked so it shouldn't be used to fulfill that order.

                                                      # extra pallets
       (1, "2014-04-21", CURRENT_TIMESTAMP, 0),                 # id == 16
       (2, "2014-04-21", CURRENT_TIMESTAMP, 0),                 # id == 16
       (4, "2014-04-21", CURRENT_TIMESTAMP, 0),                 # id == 16
       (5, "2014-04-21", CURRENT_TIMESTAMP, 0),                 # id == 16
       (6, "2014-04-21", CURRENT_TIMESTAMP, 0);                 # id == 16



/* Loading Orders
 * ------------------------------------------------------
 * Orders 1 & 3 are fulfilled in the same loading order, to test that multiple
 * orders can be fulfilled by the same truck.
 */
INSERT INTO `loading_order` (
  `truck_id`
)
VALUES (1234);                              # id == 1

INSERT INTO `loading_order_items` (
  `loading_order_id`,
  `order_item_id`,
  `pallet_id`
)
VALUES (1, 7, 4),                           # order 3
       (1, 8, 5),
       (1, 9, 6),

       (1, 10, 7),                          # order 4
       (1, 11, 8),
       (1, 12, 9);