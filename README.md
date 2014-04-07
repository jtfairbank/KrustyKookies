Krusty Kookies - Production Pipeline Pilot
===============================================================================

EDA216 Datbases: Final Project

http://fileadmin.cs.lth.se/cs/Education/EDA216/project/dbtproject.pdf

Implement a pilot program for the new Krusty Kookies production and delivery
software system.  The pilot will focus on the production side.

**Table of Contents**

 0. Intro
 1. Specifications
 2. Setup
 3. Testing
 4. Lib


Specifications
------------------------------------------------------------
KK needs three distinct systems that all work together.  This pilot program will implement the full database and the functionality described in the second system (Cookie Monster).

### General ###

This section describes requirements that apply to each of the programs described below.

Requirements:

  * The database must contain relevant sample data to demonstrate the UI functionality to Krusty Kookies.  Instructions to import sample datasets must be included in Setup (below), so that the system can be tested in different scenarios by Krusty Kookies.

### Bakers ###

The cookie production team needs a program that handles raw materials and recipes.  For the pilot, only the database needs to be setup.

Requirements:

  * `Raw Materials` need Read and Update functionality.
      - They are initially entered into the system manually.  It is assumed that the order history is also entered manually when new materials come in.
      - When a pallet of cookies is produced, the materials used to make it should be debited for the existing stock.  Error checking should ensure that enough materials exist to create the pallet.
        + This functionality will be present in the full system, but not implemented for the pilot since the pallets are actually created before being checked into our system.
      - They "must be able to check the amount in store of each ingredient, and to see when, and how much of, an ingredient was last delivered into storage."  This implies that a raw materials order history must be maintained.

  * `Recipes` need full CRUD functionality.
      - Recipes aren't changed during production.  Bakers cannot update or delete a used recipe.
      - Each recipe contains ingredients for one pallet of cookies.

### Cookie Monster ###

The cookie storage team needs a program that handles pallet production, blocking, and access (searching).  For the pilot, both the database and a UI should be created.

Requirements:

  * The system should be self-contained.  UI actions to simulate the barcode scanners, for pallet entry and exit in the deepfreezer, should be included so that the system can be tested independently.

  * `Pallets` need Create, Read, and Update functionality.
      - A pallet is the minimum unit of purchase.
      - Each pallet contains 36 boxes of 10 bags of 5 cookies = 1800 cookies.
      - Pallets are produced on the assembly line, then checked into the deepfreezer.  When they are ready to be shipped out, they are checked out of the deep freezer and loaded onto the truck.
          + As noted above, the checkin and checkout actions are simulated in our pilot.
          + Pallets must be loaded into the truck in the order they are produced (oldest pallets first).
      - Pallets can be blocked from shipment if a sample fails the quality assessment test.  In this case, all pallets of the sample's cookie type within a certain date range are blocked.  Blocked pallets cannot be delivered.
      - Pallets must be searchable by date range, so that Krusty Kookies can see all of the pallets produced during a specific time.
      - Pallets must be searchable by cookie type.
      - Delivered pallets must be searchable by customer destination.
      - Pallets must be traceable- they need a history with production, delivery, and blocked entries.

### Girl Scouts ###

The sales team needs a program that handles orders and deliveries.  For the pilot, only the database needs to be setup.

Requirements:

  * `Customers`
      - Must be in the system to place an order.
      - There is already a system in place to check and register customers.  Assume this is applied to every order.  Assume that our DB is kept in sync with their system, so that we can safely reference customers if needed.

  * `Orders` need Create, Read, and Update functionality.
      - They cannot be deleted, only canceled.
      - Orders can be placed by web or telephone.
      - Orders should be searchable by delivery date range.
      - Production planning remains a manual process.  "At the end of each week, production for the following week is planned, using the orders for the following weeks as input."

  * `Trucks`
      - Can hold up to 60 pallets of cookies.
      - Can deliver multiple orders.
      - Assume that trucks have some sort of ID, but another system handles them.  Ours simply uses the *truckID* in Loading Orders, but provides no concept of Trucks themselves.

  * `Loading Orders` need Create and Read functionality.
      - Dictate what goes on the truck.
      - The driver receives a `Loading Bill`, which is identical to a loading order but contains a field for the customer to acknowledge receipt of their cookies.  This does not need to be saved in the database.
      - When the loading bill is created, the pallets are considered delivered and should be updated with customer data and date of delivery.


Setup
------------------------------------------------------------

This software has been tested with PHP 5.4 and MySQL 5.5.

### Installation ###

 1. [Clone the source.](https://github.com/jtfairbank/KrustyKookies)

### Database ###

 1. Make sure that no database `KrustyKookies` exists.
 2. Run `setup/DB_setup.sql` to create the database, tables, and relational structure.
 3. Import sample data found in the Apendices: `setup/apendice_data.sql`.
      - (optional) Instead, import sample data for a full system demonstration: `setup/demo_data.sql`
      - (optinoal) Instead, import your own sample data.
 4. Copy `build/dynamic/api/lib/settings.php.skel` to `build/dynamic/api/lib/settings.php` and fill it in with the mysql connection info.

### Web App ###

 1. Point your webserver to `build/`.


Testing
------------------------------------------------------------
You can test the project by hand using the web interface.  Simply start up a webserver pointed to `build/` an navigate around the site.  It's entirely self explanatory.

Alterantively, you can use automated tests with [PHP Unit](http://phpunit.de/) to test the application logic and database interactions.


Lib
------------------------------------------------------------

### Twitter Bootstrap ###

A CSS library that provides basic styles.

http://getbootstrap.com/

### jQuery ###

A js library that makes DOM manipulation, ajax, and other things super easy.

http://jquery.com/

### Twig ###

A php based templating library.

http://twig.sensiolabs.org/

### Images ###

  * `static/img/cookies.jpg` taken with permission from [Wikipedia](http://en.wikipedia.org/wiki/File:Chocolate_Chip_Cookies_-_kimberlykv.jpg).  Thanks to [kimberlykv](http://flickr.com/photos/87542849@N00/4643536339) for the original.