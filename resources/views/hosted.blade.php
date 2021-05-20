<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
</head>
<body>
<form action="{{url('/checkout')}}" id="my-sample-form" method="post">
    <label for="card-number">Card Number</label>

    <div id="card-number">
{{--        <input id="ccn" type="tel" inputmode="numeric" pattern="[0-9\s]{13,19}" autocomplete="cc-number" maxlength="19" placeholder="xxxx xxxx xxxx xxxx">--}}
    </div>

    <label for="cvv">CVV</label>
    <div id="cvv">
{{--        <input type="number" min="1">--}}
    </div>

    <label for="expiration-date">Expiration Date</label>
    <div id="expiration-date">
{{--        <input type="date">--}}
    </div>
    <br>
    <input type="submit" value="Pay" disabled />
</form>

<script src="https://js.braintreegateway.com/web/3.73.1/js/client.min.js"></script>
<script src="https://js.braintreegateway.com/web/3.73.1/js/hosted-fields.min.js"></script>
<script>
    var form = document.querySelector('#my-sample-form');
    var submit = document.querySelector('input[type="submit"]');

    braintree.client.create({
        authorization: '{{$token}}'
    }, function (clientErr, clientInstance) {
        if (clientErr) {
            console.error(clientErr);
            return;
        }

        // This example shows Hosted Fields, but you can also use this
        // client instance to create additional components here, such as
        // PayPal or Data Collector.

        braintree.hostedFields.create({
            client: clientInstance,
            styles: {
                'input': {
                    'font-size': '14px'
                },
                'input.invalid': {
                    'color': 'red'
                },
                'input.valid': {
                    'color': 'green'
                }
            },
            fields: {
                number: {
                    selector: '#card-number',
                    placeholder: '4111 1111 1111 1111'
                },
                cvv: {
                    selector: '#cvv',
                    placeholder: '123'
                },
                expirationDate: {
                    selector: '#expiration-date',
                    placeholder: '10/2022'
                }
            }
        }, function (hostedFieldsErr, hostedFieldsInstance) {
            if (hostedFieldsErr) {
                console.error(hostedFieldsErr);
                return;
            }

            submit.removeAttribute('disabled');

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                hostedFieldsInstance.tokenize(function (tokenizeErr, payload) {
                    if (tokenizeErr) {
                        console.error(tokenizeErr);
                        return;
                    }

                    // If this was a real integration, this is where you would
                    // send the nonce to your server.
                    console.log('Got a nonce: ' + payload.nonce);
                });
            }, false);
        });
    });
</script>
</body>
</html>
