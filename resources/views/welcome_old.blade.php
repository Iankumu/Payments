    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Payments</title>
</head>
<body>
@if(session('success_message'))
    <div class="alert alert-success">
        {{session('success_message')}}
    </div>
@endif

@if(count($errors)>0)
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach

        </ul>
    </div>
@endif

<div class="content">
    <form method="post" id="payment-form" action="{{url('/checkout')}}">
        @csrf
        <section>
            <label for="amount">
                <span class="input-label">Amount</span>
                <div class="input-wrapper amount-wrapper">
                    <input id="amount" name="amount" type="tel" min="1" placeholder="Amount" value="10">
                </div>
            </label>

            <div class="bt-drop-in-wrapper">
                <div id="bt-dropin"></div>
            </div>
        </section>

        <input id="nonce" name="nonce" type="hidden"/>

        <input id="fname" name="fname" type="hidden" value="Ian" />
        <input id="lname" name="lname" type="hidden" value="Kumu" />
        <input id="email" name="email" type="hidden" value="ian.kumu@gmail.com" />
        <button class="button" type="submit"><span>Test Transaction</span></button>
    </form>
</div>
<script src="https://js.braintreegateway.com/web/dropin/1.29.0/js/dropin.min.js"></script>
<script>
    var form = document.querySelector('#payment-form');
    var client_token = "{{$token}}";



    braintree.dropin.create({
        authorization: client_token,
        container: '#bt-dropin',
    }, function (createErr, instance) {
        if (createErr) {
            console.log('Create Error', createErr);
            return;
        }
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            instance.requestPaymentMethod(function (err, payload) {
                if (err) {
                    console.log('Request Payment Method Error', err);
                    return;
                }

                console.log(payload.nonce)

                // Add the nonce to the form and submit
                document.querySelector('#nonce').value = payload.nonce;
                form.submit();
            });
        });
    });
</script>
</body>
</html>
