# Payeer NoVerify library!

Payeer made all ban on the API and asks for verification, it is better to avoid unnecessary surveillance and use this library

## Connect

Connect this:
```require 'payeer_noverify.php';```

## Use

```$payeer = new PayeerNoVerify('you_payeer_cookie');```

You need to insert the PHPSESSID cookie in the first parameter. Go to payeer.com and in the developer tools find the PHPSESSID in storage and cookie and copy its value.

Useragent can be passed in the second parameter, by default it is: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36

## History transactions

```$payeer->history();```

Returns a list of transactions. The result is similar to: https://payeercom.docs.apiary.io/#reference/0/authorization-check/history-of-transactions

But not all data is returned, but only: date, type, from, creditedAmount, creditedCurrency, to, debitedAmount, debitedCurrency, paySystem, status, id, shopId, shopOrderId, isApi, comment. And also shopUrl which is not in the documentation.

## Contributing

You can contribute via pull request
