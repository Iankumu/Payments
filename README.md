## Payments API

This is an API implementation of the various Payment Gateways including `Mpesa`, `Paypal` and `Braintree`.

## Setup
To run this project locally clone the repository and in the project directory,run the following commands:

```
$ cp.env.example .env
$ composer install
$ php artisan key:generate
$ php artisan migrate
$ php artisan serve
```
## Payment Gateways
The application consists of a couple of endpoints that facilitate the capture and settling of the payments

### Mpesa STK Push
-   #### **Simulate a Transaction**
    This resource consists the implementation of the Mpesa Express API. The endpoint for the resource is `POST` `/api/v1/mpesatest/stk/push`. It is responsible for simulating the transaction and sending the payment prompt to a specific phone number.

    Before any simulation, there are some configurations that are required for the API to function seamlessly. These configurations and variables include:
    - Safaricom PassKey(Provided by Safaricom)
    - MPESA Business Shortcode(This is the paybill number) `174379` for testing purposes
    - MPESA Consumer Key(Provided by Safaricom)
    - MPESA Consumer Secret(Provided by Safaricom)
    These variables are required to be added on the .env file

    Some of the inputs required include:
    -  amount
    -  phonenumber

    The resource performs formatting the phone number into the required format before initiating the payment request

    Upon Success the transaction details will be stored in the database.

    -   #### **Query a Transaction**
        This resource is responsible for querying the status of a transaction. It is accessed through the `POST` `/api/v1/callback/query` endpoint.

        The input required is
        -  CheckoutRequestID

        It returns the details of the transaction whether it was successful or cancelled.

### Mpesa C2B
This API is responsible for tranfer of funds from Client to Business. It supports both Paybill and Buy Goods services.
The configuration required is:   
-   MPESA Business Shortcode(This is a Paybill number or a Till Number)

-   #### **Register URL**
    This resource is responsible for registering the confirmation and validation urls on Mpesa which will then be used to receive the payment responses.
    It is accessed through the `GET` `/api/register-urls` endpoint.

-   #### **Simulate a Transaction**
    This response is responsible for making payment requests from a Client to a Business.
    It is accessed through the `POST` `/api/c2b/simulate` enpoint. In this instance, the API supports the Paybill service of requesting funds.
    Inputs required include:
    - phonenumber
    - amount
    - account (account_number to a paybill)

    It captures the funds and tranfers them to the merchant's business shortcode and then stores the transaction details to the database upon success.

### Paypal
This resource consists of the implementation of Paypal checkout API. Some of the configurations required include:
- Paypal Client Id
- Paypal Client Secret
They are provided by paypal and are required to be added on the .env file

-   #### **Initiate a Transaction**
    To initiate a transaction, the `POST` `/api/paypal/create` endpoint has to be accessed. The input required is
    -  amount
    
    This will fire the paypal checkout API which will return a response containing various links and the transaction ID.
    The sample response is in the format below:
    ```
        {
            "id": "8NG28868RK554011N",
            "status": "CREATED",
            "links": [
                {
                    "href": "https://api.sandbox.paypal.com/v2/checkout/orders/8NG28868RK554011N",
                    "rel": "self",
                    "method": "GET"
                },
                {
                    "href": "https://www.sandbox.paypal.com/checkoutnow?token=8NG28868RK554011N",
                    "rel": "approve",
                    "method": "GET"
                },
                {
                    "href": "https://api.sandbox.paypal.com/v2/checkout/orders/8NG28868RK554011N",
                    "rel": "update",
                    "method": "PATCH"
                },
                {
                    "href": "https://api.sandbox.paypal.com/v2/checkout/orders/8NG28868RK554011N/capture",
                    "rel": "capture",
                    "method": "POST"
                }
            ]
        }

    ```

    To capture the transaction, it has to be approved and this is done by accessing the approve link present in the response.

-   #### **Process the Transaction**
    Once the transaction has been approved, It can be processed for capture in the `POST` `/api/paypal/transaction/{id}` endpoint. The id required in this case is the transaction id  provided by paypal. This will then capture the required amount from a client and send them to the paypal merchant's account and also store the transaction details to the database upon success.

### Braintree
Consists of the braintree implementation which captures credit/debit card payments.
Some configurations required include:
- Braintree Environment
- Braintree Merchant ID
- Braintree Public Key
- Braintree Private Key

They are all provided by Braintree and should be added to the .env file
-   #### **Initiate a Transaction**
To initiate a transaction, the endpoint `POST` `/api/braintree` has to be accessed.
The Inputs required include:
   - nonce (This is a payment method available in Braintree e.g paypal,card etc)
   - amount
   - fname (First name of the Client)
   - lname (Last name of the Client)
   - email (Email of the Client)

The Transaction is executed and the records can be viewed on the Braintree dashboard
