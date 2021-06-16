
<input id="amount" type="text" name="amount" placeholder="Enter Amount">
<div id="paypal-button-container"></div>


<script src="https://www.paypal.com/sdk/js?client-id=Ab-A65V5JSFgj2SE1gpWKkRd_ZI2Ij0ICIU7H-2tLO-UWbTVS-8XCagHKS7IrBDr3jPyJ-btRwAPpHwb"></script>

<script>
// const amount =document.getElementById("amount").value;
const amount =100;

const params={
    amount:amount,
}
const options = {
    method: 'POST',
    body: JSON.stringify( params )
};
paypal.Buttons({

    env: 'sandbox', /* sandbox | production */
    style: {
                layout: 'horizontal',   // horizontal | vertical
                size:   'responsive',   /* medium | large | responsive*/
                shape:  'pill',         /* pill | rect*/
                color:  'gold',         /* gold | blue | silver | black*/
                fundingicons: false,    /* true | false */
                tagline: false          /* true | false */
            },

      /* createOrder() is called when the button is clicked */

    createOrder: function() {


        /* Set up a url on your server to create the order */

        var CREATE_URL = 'https://37bd1cd74363.ngrok.io/api/paypal/create';


        /* Make a call to your server to set up the payment */

        return fetch(CREATE_URL,options)
         .then(function(res) {
          return res.json();
         }).then(function(data) {
          return data.id;
         });

    },




    /* onApprove() is called when the buyer approves the payment */

    onApprove: function(data,actions) {

        /* Set up a url on your server to execute the payment */

        var EXECUTE_URL = 'https://37bd1cd74363.ngrok.io/api/paypal/transaction/'+data.orderID;

        /* Set up the data you need to pass to your server */

        /* Make a call to your server to execute the payment */

        return fetch(EXECUTE_URL, {
        method: 'POST',
        }).then(function(res) {
         return res.json();
        }).then(function(details) {
         alert('Transaction funds captured from ' + details.payer_given_name);
        });

    }

}).render('#paypal-button-container');

</script>
