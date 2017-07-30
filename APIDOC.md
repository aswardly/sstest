SaleStock Backend Dev Test (API Docs)
=====================================

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
10. **Update Shipping** (performed by admin, add shipping information to the order)
11. **Inquiry Shipping** (for inquiring shipping information of the order)
12. **View Order** (for viewing order details) 

Background
----------
The typical business flow steps assumed in this application are as follows:
1. Customer creates a new empty order by calling 'Create Order' service
2. Customer can add new item into the order by calling 'Add Item' service, modify item quantity by calling 'Edit Item' service, or remove the item from order by calling 'Remove Item' service.
3. Customer can also apply a coupon id to the order by calling 'Apply Coupon' service.
4. Customer then places the order by calling 'Submit Order' service.
5. Admin then approves the order by calling 'Process Order' service.
6. Customer submits payment proof by calling 'Submit Payment' service.
7. Admin then can add shipping information by calling 'Add Shipping' service, and can then update it by calling 'Update Shipping' service
8. Customer can inquire shipping information by calling 'Shipping Inquiry' serrvice.
9. Anytime during the whole process both Admin and Customer can view order details by calling 'View Order' service.

API Format
----------
**NOTE**: 
+ Hostname assumed in example: http://localhost/
+ You can send HTTP requests to the URLs listed below by running curl command or using software such as [Postman](https://www.getpostman.com/)
+ All services performed by admin requires HTTP Basic auth. Use username '**admin**' and password '**admin**' when sending the HTTP request.
If HTTP Auth information is not sent, you will get a HTTP 401 response.
+ All service responses are JSON string and contain field named 'code' with the values: **S** (success), **F** (failed), *U* (unauthorized)
+ The URL given are treated case sensitively, if even only 1 character differs in case, the application will respond with a HTTP 404 page.

### 1. Create Order

URL: `http://localhost/CreateOrder`

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

URL: `http://localhost/AddItem`

METHOD: `HTTP POST`

Post Variables in request:
+ **orderId** : id of the order to add the product into. Use the order id returned from 'Create Order' service.
+ **itemId** : id of product to add. Refer to the 'product_id' in table 'm_product' in the database. Some values to try are: 'MBP01' and 'MBA01'.
+ **quantity** : quantity of item to add

### 3. Edit Item

URL: `http://localhost/EditItem`

METHOD: `HTTP POST`

Post Variables in request:
+ **orderId** : the id of order to modify.
+ **itemId** : the id of product to modify.
+ **quantity** : quantity of item (the product quantity in the order will be updated with this)

### 4. Remove Item

URL: `http://localhost/RemoveItem`

METHOD: `HTTP POST`

Post Variables in request:
+ **orderId** : the id of order to modify.
+ **itemId** : the id of product to be deleted.

### 5. Apply Coupon

URL: `http://localhost/ApplyCoupon`

METHOD: `HTTP POST`

Post Variables in request:
+ **orderId** : the id of order to modify.
+ **couponId** : the id of the coupon to be applied. Refer to the 'coupon_id' in table 'm_coupon' in the database. Some values to try are: 'CP123' and 'CP345'.

### 6. Submit Order

URL: `http://localhost/SubmitOrder`

METHOD: `HTTP POST`

Post Variables in request:
+ **orderId** : the id of order to submit.
+ **customerName** : name of customer to be added into the order.
+ **customerPhone** : customer phone no to be added into the order.
+ **customerEmail** : customer e-mail address to be added into the order.
+ **customerAddress** : customer address to be added into the order.

### 7. Process Order

**Note**: Requires HTTP Basic Auth, use username: 'admin' and password: 'admin'

URL: `http://localhost/ProcessOrder`

METHOD: `HTTP POST`

Post Variables in request:
+ **orderId** : the id of order to process.

### 8. Submit Payment

URL: `http://localhost/Payment`

METHOD: `HTTP POST`

Post Variables in request:
+ **orderId** : the id of order to process

+ **paymentProof** : the proof of payment. You can input any value in text format

### 9. Add Shipping

**Note**: Requires HTTP Basic Auth, use username: 'admin' and password: 'admin'

URL: `http://localhost/AddShipping`

METHOD: `HTTP POST`

Post Variables in request:
+ **orderId** : the id of order to add shipping information to.
+ **shippingId** : the shipping id info. You can input any value in text format.

### 10. Update Shipping

**Note**: Requires HTTP Basic Auth, use username: 'admin' and password: 'admin'

URL: `http://localhost/UpdateShipping`

METHOD: `HTTP POST`

Post Variables in request:
+ **orderId** : the id of order to add shipping information to.
+ **shippingId** : the shipping id info. You can input any value in text format.
+ **shippingStatus** : the shipping status info. Valid values are: 'O' (On process) and 'D' (delivered).

### 11. Inquiry Shipping

URL: `http://localhost/InquiryShipping?shippingId=shippingIdValue`

METHOD: `HTTP GET`

Variables in request query string:
+ **shippingId** : the shipping id info of the order.

Returns a JSON format containgin the shipping information

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

URL: `http://localhost/ViewOrder?orderId=orderIdValue`

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
