SaleStock Backend Dev Test
==========================

Introduction
------------
This is a submission for Salestock Backend Dev test built using Zend Framework 2.
This application acts as webservice endpoints for the requested business scenario (Order Processing) using HTTP protocol.
Application is built using the foundation from [Zend Skeleton Application](https://github.com/zendframework/ZendSkeletonApplication).
The ZF2 version used is 2.4.11.

Showcase
--------
This application is built using Domain Driven Design approach where business logic are made into entities and valueobjects.
e.g. 'Product' is considered as an entity, while an 'order item' is a valueobject. The 'Order' itself is an aggregate root for accessing a collection of 'order items'.
The domain models are located at '**/module/Emoneygw/src/Emoneygw/Model/Concrete/**' and '**/module/Emoneygw/src/Emoneygw/ValueObject/Concrete/**'

This is a fully functional application where all state changes are persisted to database (Mysql). 
This utilizes ZF2 components such as '**Zend Db**' and '**Zend Hydrator**'.
The data persistence layer is architectured using the Data Mapper pattern.
The data mappers are located at '**/module/Emoneygw/src/Emoneygw/DataMapper/Concrete/**' and **/module/Emoneygw/src/Emoneygw/DataMapper/Hydrator/**'

Dependency Injection is handled by utlizing ZF2's built in '**Zend ServiceManager**' using Factory classes located at '**/module/Emoneygw/src/Emoneygw/Factory/DataMapper/**'

Online Version
--------------
An online version has been deployed [here](http://128.199.104.220/~andi/public/).
Refer to the below for details on testing.

Offline installation
--------------------
Refer to [INSTALL.md](INSTALL.md) for installation instructions.

Running Unit Test
-----------------
Unit tests are performed by using PHPUnit which will become available after running '**composer install**' during offline installation.
The test configuration is located at '/tests' and the unit test code is located at 'test' folder under '/module' (e.g. */module/Emoneygw/test/*)

Running unit tests requires console access. You can perform this after following offline installation steps above.
1. Open your console, navigate to the '**/test/**' folder
2. Run PHPUnit using the following command:.

e.g. (on Windows):

    ..\vendor\bin\phpunit.bat --debug

e.g (on Linux):

    ../vendor/bin/./phpunit --debug

**Note**: The command will try to execute PHPUnit located in '*/vendor/bin/*' folder and parses the '**phpunit.xml**' configuration file in the '**/test/**' folder.
Alternatively you can navigate to the '**/vendor/bin**' folder and run the command and provide the '**phpunit.xml**' file path in the command:

e.g. (on Windows):

    phpunit.bat --debug --configuration \path\to\test\phpunit.xml

e.g (on Linux):

    ./phpunit --debug --configuration \path\to\test\phpunit.xml

After the unit tests is run you will get a result similiar to the following screenshot:

![Alt text](unit_test_capture.JPG?raw=true "Sample unit test run results")

API Documentation
=================

List of services
----------------
The following services are provided:
1. **Create Order** (for creating new empty order)
2. **Add Item** (for adding item to an order)
3. **Edit Item** (for modifying item quantity in an order)
4. **Delete Item** (for removing item from an order)
5. **Apply Coupon** (for applying discount from coupon to the order amount)
6. **Submit Order** (finalizes order and submits it for further processing)
7. **Process Order** (performed by admin, process submitted order)
8. **Submit Payment** (for submitting payment proof to the order)
9. **Add Shipping** (performed by admin, add shipping information to the order)
10. **Update Shipping** (performed by admin, update shipping information on the order)
11. **Inquiry Shipping** (for inquiring shipping information of the order)
12. **View Order** (for viewing order details) 

Background
----------
The typical business flow steps assumed in this application are as follows:
1. Customer creates a new empty order by calling '**Create Order**' service
2. Customer can add new item into the order by calling '**Add Item**' service, modify item quantity by calling '**Edit Item**' service, or remove the item from order by calling '**Remove Item**' service.
3. Customer can also apply a coupon id to the order by calling '**Apply Coupon**' service.
4. Customer then places the order by calling '**Submit Order**' service.
5. Admin then approves the order by calling '**Process Order**' service.
6. Customer submits payment proof by calling '**Submit Payment**' service.
7. Admin then can add shipping information by calling '**Add Shipping**' service, and can then update it by calling '**Update Shipping**' service
8. Customer can inquire shipping information by calling '**Shipping Inquiry**' serrvice.
9. Anytime during the whole process both Admin and Customer can view order details by calling '**View Order**' service.

API Format
----------
**NOTE**: 
+ Hostname assumed in example is from the online version: '*http://128.199.104.220/~andi/public/*', when testing the offline version, replace this with your web server's hostname.
+ You can send HTTP requests to the URLs listed below by running curl command or using software such as [Postman](https://www.getpostman.com/)
+ All services performed by admin requires HTTP Basic Auth in order to protect them being executed by the customer. Use username '**admin**' and password '**admin**' when sending the HTTP request. If HTTP Auth information is not sent, you will get a HTTP 401 response.
+ All service responses are JSON string and contain field named 'code' with the possible values: **S** (success), **F** (failed), **U** (unauthorized).
+ The URLs given are treated in a case sensitive manner, if even only 1 character differs in case, the application will respond with a HTTP 404 page.
e.g. Acessing *http://128.199.104.220/~andi/public/createorder* instead of *http://128.199.104.220/~andi/public/CreateOrder* will result in HTTP 404 page.
+ Accessing the URLs with the incorrect HTTP method will resul in a failed response.


### 1. Create Order

URL: `http://128.199.104.220/~andi/public/CreateOrder`

METHOD: `HTTP POST`

Post Variables in request: None

Returns a JSON format containing the Order id

e.g:
```javascript
{
    "code": "S",
    "orderId": "ORD3a0549b544"
}
````


### 2. Add Item

URL: `http://128.199.104.220/~andi/public/AddItem`

METHOD: `HTTP POST`

Post Variables in request:
+ **orderId** : id of the order to add the product into. Use the order id returned from 'Create Order' service.
+ **itemId** : id of product to add. Refer to the 'product_id' in table 'm_product' in the database. Some values to try are: 'MBP01' and 'MBA01'.
+ **quantity** : quantity of item to add


### 3. Edit Item

URL: `http://128.199.104.220/~andi/public/EditItem`

METHOD: `HTTP POST`

Post Variables in request:
+ **orderId** : the id of order to modify.
+ **itemId** : the id of product to modify.
+ **quantity** : quantity of item (the product quantity in the order will be updated with this)


### 4. Remove Item

URL: `http://128.199.104.220/~andi/public/RemoveItem`

METHOD: `HTTP POST`

Post Variables in request:
+ **orderId** : the id of order to modify.
+ **itemId** : the id of product to be deleted.


### 5. Apply Coupon

URL: `http://128.199.104.220/~andi/public/ApplyCoupon`

METHOD: `HTTP POST`

Post Variables in request:
+ **orderId** : the id of order to modify.
+ **couponId** : the id of the coupon to be applied. Refer to the 'coupon_id' in table 'm_coupon' in the database. Some values to try are: 'CP123' and 'CP345'.


### 6. Submit Order

URL: `http://128.199.104.220/~andi/public/SubmitOrder`

METHOD: `HTTP POST`

Post Variables in request:
+ **orderId** : the id of order to submit.
+ **customerName** : name of customer to be added into the order.
+ **customerPhone** : customer phone no to be added into the order.
+ **customerEmail** : customer e-mail address to be added into the order.
+ **customerAddress** : customer address to be added into the order.


### 7. Process Order

**Note**: Requires HTTP Basic Auth, use username: 'admin' and password: 'admin'

URL: `http://128.199.104.220/~andi/public/ProcessOrder`

METHOD: `HTTP POST`

Post Variables in request:
+ **orderId** : the id of order to process.


### 8. Submit Payment

URL: `http://128.199.104.220/~andi/public/Payment`

METHOD: `HTTP POST`

Post Variables in request:
+ **orderId** : the id of order to process

+ **paymentProof** : the proof of payment. You can input any value in text format


### 9. Add Shipping

**Note**: Requires HTTP Basic Auth, use username: 'admin' and password: 'admin'

URL: `http://128.199.104.220/~andi/public/AddShipping`

METHOD: `HTTP POST`

Post Variables in request:
+ **orderId** : the id of order to add shipping information to.
+ **shippingId** : the shipping id info. You can input any value in text format.


### 10. Update Shipping

**Note**: Requires HTTP Basic Auth, use username: 'admin' and password: 'admin'

URL: `http://128.199.104.220/~andi/public/UpdateShipping`

METHOD: `HTTP POST`

Post Variables in request:
+ **orderId** : the id of order to add shipping information to.
+ **shippingId** : the shipping id info. You can input any value in text format.
+ **shippingStatus** : the shipping status info. Valid values are: 'O' (On process) and 'D' (delivered).


### 11. Inquiry Shipping

URL: `http://128.199.104.220/~andi/public/InquiryShipping?shippingId=shippingIdValue`

METHOD: `HTTP GET`

Variables in request query string:
+ **shippingId** : the shipping id info of the order.

Returns a JSON format containing the shipping information

e.g:
```javascript
{
    "code": "S",
    "message": {
        "orderId": "ORD3ec6fd5ceb",
        "shippingId": "NEWSHI967",
        "shippingStatus": "Delivered"
    }
}
````


### 12. View Order

URL: `http://128.199.104.220/~andi/public/ViewOrder?orderId=orderIdValue`

METHOD: `HTTP GET`

Variables in request query string:
+ **orderId** : the order id  to be inquired.

Returns a JSON format describing the order details

e.g:
```javascript
{
    "code": "S",
    "message": {
        "id": "ORD001",
        "createdDate": "2017-07-03 15:56:29",
        "submittedDate": "2017-07-30 00:15:06",
        "processedDate": "2017-07-30 00:27:11",
        "status": "Delivered",
        "totalAmount": "200000.00",
        "coupon": {
            "id": "CP345",
            "quantity": "19",
            "value": "20.00",
            "type": "Percentage",
            "status": "Valid",
            "startDate": "2017-07-20 00:00:00",
            "expiryDate": "2017-08-31 00:00:00"
        },
        "customerName": "John Smith",
        "customerEmail": "johnsmith@somewhere.com",
        "customerPhone": "08123456789",
        "customerAddress": "Somewhere street 123 avenue",
        "shippingId": "X7YHG",
        "shippingStatus": "Delivered",
        "orderItems": [
            {
                "product": {
                    "id": "MBA01",
                    "name": "Apple Macbook Air MJVM2",
                    "description": "Intel Core i5 - 1.6Ghz, RAM 4GB, HDD 128GB SSD, VGA Intel HD 6000, WebCam, Bluetooth, Screen 11\" Wide LED, OS X Yosemite",
                    "status": "Active",
                    "stock": "30",
                    "price": "50000.00"
                },
                "productQuantity": "3"
            },
            {
                "product": {
                    "id": "MBP01",
                    "name": "Apple Macbook Pro MJLT2 Pro Retina (Upgrade Version)",
                    "description": "2.8GHz quad-core Intel Core i7 (Turbo Boost up to 3.7GHz), 16GB 1600MHz memory; 1TB PCIe-based flash storage, 15 inch IPS Retina, Intel Iris Graphics, AMD Radeon R9 M370X with 2GB GDDR5 memory, Force Touch trackpad, OS X Yosemite",
                    "status": "Active",
                    "stock": "27",
                    "price": "100000.00"
                },
                "productQuantity": "1"
            }
        ]
    }
}
````
