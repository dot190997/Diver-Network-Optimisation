# Diver-Network-Optimisation
A web portal for employees of Just Delivery which helps them to keep a track of orders assignment and give suggestions accordingly.
Just Delivery is a small delivery start up. They have hired 14 drivers and offer food ordering and delivery services.

This project uses php and MySQL. To calculate driving ditance between any two points, it uses Google Distance Matrix.

1. Check the active drivers and feed in their current locations. Default current location is Just Delivery Office.
(You'll have to manually feed the current location each time as we are not using any API to get current location yet.)
Activate the drivers.

2. Type in the Source Location and Destination Area and check if the order is self order or not.

3. Select either Undelivered or All in Get Orders field to see the list of orders and order details.

4. Show/Suggest button will how the current path each driver has to take in order to make trip shortest and deliver orders in time.
   It will also suggest whom to assign a particular order to.

5. Order Id can be used to change the status of the order or to assign that particular order to any driver.
