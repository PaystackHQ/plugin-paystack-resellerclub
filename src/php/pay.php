<?php
session_start();
require("settings.php");    //file which has required settings
require("functions.php");    //file which has required functions

$settings = new settings();
?>

<html>

<head>
    <title>Reseller Club Payment Page | Powered By Paystack</title>
    <!-- <script language="JavaScript">
        function successClicked()
        {
            document.paymentpage.submit();
        }
        function failClicked()
        {
            document.paymentpage.status.value = "N";
            document.paymentpage.submit();
        }
        function pendingClicked()
        {
            document.paymentpage.status.value = "P";
            document.paymentpage.submit();
        }
</script> -->
</head>

<body bgcolor="white">

    <?php

    $key = $settings->hostkey;

    //This filter removes data that is potentially harmful for your application. It is used to strip tags and remove or encode unwanted characters.
    //FILTER_SANITIZE_STRING is deprecated since PHP 8.1, so the same sanitising is done here: strip tags and encode quotes.
    array_walk_recursive($_GET, function (&$getValue) {
        $getValue = str_replace(array("'", '"'), array("&#39;", "&#34;"), strip_tags((string) $getValue));
    });

    //Below are the  parameters which will be passed from foundation as http GET request
    $paymentTypeId = $_GET["paymenttypeid"] ?? null;  //payment type id
    $transId = $_GET["transid"] ?? null;               //This refers to a unique transaction ID which we generate for each transaction
    $userId = $_GET["userid"] ?? null;               //userid of the user who is trying to make the payment
    $userType = $_GET["usertype"] ?? null;             //This refers to the type of user perofrming this transaction. The possible values are "Customer" or "Reseller"
    $transactionType = $_GET["transactiontype"] ?? null;  //Type of transaction (ResellerAddFund/CustomerAddFund/ResellerPayment/CustomerPayment)
    $invoiceIds = $_GET["invoiceids"] ?? null;           //comma separated Invoice Ids, This will have a value only if the transactiontype is "ResellerPayment" or "CustomerPayment"
    $debitNoteIds = $_GET["debitnoteids"] ?? null;       //comma separated DebitNotes Ids, This will have a value only if the transactiontype is "ResellerPayment" or "CustomerPayment"
    $description = $_GET["description"] ?? null;
    $sellingCurrencyAmount = $_GET["sellingcurrencyamount"] ?? null; //This refers to the amount of transaction in your Selling Currency
    $accountingCurrencyAmount = $_GET["accountingcurrencyamount"] ?? null; //This refers to the amount of transaction in your Accounting Currency
    $redirectUrl = $_GET["redirecturl"] ?? null;  //This is the URL on our server, to which you need to send the user once you have finished charging him

    $checksum = $_GET["checksum"] ?? null;     //checksum for validation

    //extra information for the payment page and other things.
    $name = $_GET["name"] ?? null;
    $company = $_GET["company"] ?? null;
    $emailAddr = $_GET["emailAddr"] ?? null;
    $address1 = $_GET["address1"] ?? null;
    $address2 = $_GET["address2"] ?? null;
    $address3 = $_GET["address3"] ?? null;
    $city = $_GET["city"] ?? null;
    $state = $_GET["state"] ?? null;
    $country = $_GET["country"] ?? null;
    $zip = $_GET["zip"] ?? null;
    $telNoCc = $_GET["telNoCc"] ?? null;
    $telNo = $_GET["telNo"] ?? null;
    $faxNoCc = $_GET["faxNoCc"] ?? null;
    $faxNo = $_GET["faxNo"] ?? null;
    $resellerEmail = $_GET["resellerEmail"] ?? null;
    $resellerURL = $_GET["resellerURL"] ?? null;
    $resellerCompanyName = $_GET["resellerCompanyName"] ?? null;
    $resellerCurrency = $_GET["resellerCurrency"] ?? null;

    //get current page base URL
    $hostName = $_SERVER['HTTP_HOST']; // output: localhost
    $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https://' ? 'https://' : 'http://';  // output: http://

    $paymentChannels = [];
    // if ($resellerCurrency == "NGN") {
    //     $paymentChannels = ["card", "bank"];
    // } else {
    //     $paymentChannels = ["card"];
    // }


    if (verifyChecksum($paymentTypeId, $transId, $userId, $userType, $transactionType, $invoiceIds, $debitNoteIds, $description, $sellingCurrencyAmount, $accountingCurrencyAmount, $key, $checksum)) {
        $body = array(
            "email" => $emailAddr,
            "amount" => $sellingCurrencyAmount * 100,
            // "currency" => "NGN",
            "currency" => $resellerCurrency,
            "reference" => $transId,
            "callback_url" =>  $protocol . $hostName . "/paystack/confirm.php",
            "metadata" => array(
                array(
                    "display_name" => "Customer Name",
                    "variable_name" => "customer_name",
                    "value" => $name
                ),
                array(
                    "display_name" => "Purchase Description",
                    "variable_name" => "purchase_description",
                    "value" => $description
                ),
                array(
                    "display_name" => "Plugin",
                    "variable_name" => "plugin",
                    "value" => "reseller-club"
                )
            )
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $settings->paystack_base . "transaction/initialize",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => -1, //Maximum number of redirects
            CURLOPT_TIMEOUT => 0, //Timeout for request
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer "  . $settings->paystack_secretkey,
                "Content-Type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $result = json_decode($response);
            if (isset($result->status) && $result->status) {
                $auth_url = $result->data->authorization_url;

                $_SESSION['redirecturl'] = $redirectUrl;
                $_SESSION['transid'] = $transId;
                $_SESSION['sellingcurrencyamount'] = $sellingCurrencyAmount;
                $_SESSION['accountingcurencyamount'] = $accountingCurrencyAmount;

                echo "<script> location.href='" . $auth_url . "'; </script>";
            } else {
                echo isset($result->message) ? $result->message : "";
            }
        }
    } else {
        /**This message will be dispayed in any of the following case
         *
         * 1. You are not using a valid 32 bit secure key from your Reseller Control panel
         * 2. The data passed from foundation has been tampered.
         *
         * In both these cases the customer has to be shown error message and shound not
         * be allowed to proceed  and do the payment.
         *
         **/

        echo "Checksum mismatch !";
    }
    ?>
</body>

</html>