# monri-standalone

### Environment Variables

```
MONRI_SUCCESS_URL=
MONRI_CANCEL_URL=
MONRI_CALLBACK_URL=
MONRI_TOKEN=
MONRI_KEY=
MONRI_LANG=
```

### Transaction types

* Authorization
* Purchase
* Capture
* Refund
* Void


### Redirect on Success Parameters

`https://ipgtest.monri.com/shop/success?acquirer=integration_acq&amount=100&approval_code=629762&authentication=Y&cc_type=visa&ch_full_name=John+Doe&currency=USD&custom_params=%7Ba%3Ab%2C+c%3Ad%7D&enrollment=Y&language=en&masked_pan=434179-xxx-xxx-0044&number_of_installments=&order_number=02beded6e6106a0&response_code=0000&digest=575 c64b2f5a0701997c8f9cfe4293706e88203cd911695ab747ce45830e4e3cbf71577c401e476988e4a4e1b0b 5f3ecbc56277394df07fa51fbe05869d1b067a`

* acquirer
* amount
* approval_code
* authentication
* cc_type
* ch_full_name
* currency
* custom_params
* enrollment
* language
* masked_pan
* number_of_installments
* order_number
* response_code
* digest (returned digest)

### Callback Parameters

```json
{
   "id":186562,
   "acquirer":"integration_acq",
   "order_number":"a6b62d07cc89aa0",
   "amount":100,
   "currency":"EUR",
   "ch_full_name":"John Doe",
   "outgoing_amount":100,
   "outgoing_currency":"EUR",
   "approval_code":"914783",
   "response_code":"0000",
   "response_message":"approved",
   "reference_number":"000002902038",
   "systan":"186561",
   "eci":"05",
   "xid":"fake authenticated xid +=",
   "acsv":"fake authenticated cavv +=",
   "cc_type":"visa",
   "status":"approved",
   "created_at":"2019-09-06T14:24:44.906+02:00",
   "transaction_type":"purchase",
   "enrollment":"Y",
   "authentication":"Y",
   "pan_token":null,
   "masked_pan":"434179-xxx-xxx-0044",
   "issuer":"zaba-hr",
   "number_of_installments":null,
   "custom_params":"{a:b, c:d}"
}
```
