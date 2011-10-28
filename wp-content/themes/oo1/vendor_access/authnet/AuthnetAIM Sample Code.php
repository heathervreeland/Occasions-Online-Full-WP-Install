require('AuthnetAIM.class.php');
 
try
{
    $user_id = 1;
    $email   = 'johnny@example.com';
    $product = 'A test transaction';
    $business_firstname = 'John';
    $business_lastname  = 'Smith';
    $business_address   = '123 Main Street';
    $business_city      = 'Townsville';
    $business_state     = 'NJ';
    $business_zipcode   = '12345';
    $business_telephone = '800-555-1234';
    $shipping_firstname = 'John';
    $shipping_lastname  = 'Smith';
    $shipping_address   = '100 Business Rd';
    $shipping_city      = 'Big City';
    $shipping_state     = 'NY';
    $shipping_zipcode   = '10101';
 
    $creditcard = '4111-1111-1111-1111';
    $expiration = '12-2016';
    $total      = 1.00;
    $cvv        = 123;
    $invoice    = substr(time(), 0, 6);
    $tax        = 0.00;
 
    $payment = new AuthnetAIM('7jE3f8DhGK6', '9rkC8QgF349Jg48k', true); // true == TESTING
    $payment->setTransaction($creditcard, $expiration, $total, $cvv, $invoice, $tax);
    $payment->setParameter("x_duplicate_window", 180);
    $payment->setParameter("x_cust_id", $user_id);
    $payment->setParameter("x_customer_ip", $_SERVER['REMOTE_ADDR']);
    $payment->setParameter("x_email", $email);
    $payment->setParameter("x_email_customer", FALSE);
    $payment->setParameter("x_first_name", $business_firstname);
    $payment->setParameter("x_last_name", $business_lastname);
    $payment->setParameter("x_address", $business_address);
    $payment->setParameter("x_city", $business_city);
    $payment->setParameter("x_state", $business_state);
    $payment->setParameter("x_zip", $business_zipcode);
    $payment->setParameter("x_phone", $business_telephone);
    $payment->setParameter("x_ship_to_first_name", $shipping_firstname);
    $payment->setParameter("x_ship_to_last_name", $shipping_lastname);
    $payment->setParameter("x_ship_to_address", $shipping_address);
    $payment->setParameter("x_ship_to_city", $shipping_city);
    $payment->setParameter("x_ship_to_state", $shipping_state);
    $payment->setParameter("x_ship_to_zip", $shipping_zipcode);
    $payment->setParameter("x_description", $product);
    $payment->process();
 
    if ($payment->isApproved())
    {
        // Get info from Authnet to store in the database
        $approval_code  = $payment->getAuthCode();
        $avs_result     = $payment->getAVSResponse();
        $cvv_result     = $payment->getCVVResponse();
        $transaction_id = $payment->getTransactionID();
 
        // Do stuff with this. Most likely store it in a database.
        // Direct the user to a receipt or something similiar.
    }
    else if ($payment->isDeclined())
    {
        // Get reason for the decline from the bank. This always says,
        // "This credit card has been declined". Not very useful.
        $reason = $payment->getResponseText();
 
        // Politely tell the customer their card was declined
        // and to try a different form of payment.
    }
    else if ($payment->isError())
    {
        // Get the error number so we can reference the Authnet
        // documentation and get an error description.
        $error_number  = $payment->getResponseSubcode();
        $error_message = $payment->getResponseText();
 
        // OR
 
        // Capture a detailed error message. No need to refer to the manual
        // with this one as it tells you everything the manual does.
        $full_error_message =  $payment->getResponseMessage();
 
        // We can tell what kind of error it is and handle it appropriately.
        if ($payment->isConfigError())
        {
            // We misconfigured something on our end.
        }
        else if ($payment->isTempError())
        {
            // Some kind of temporary error on Authorize.Net's end. 
            // It should work properly "soon".
        }
        else
        {
            // All other errors.
        }
 
        // Report the error to someone who can investigate it
        // and hopefully fix it
 
        // Notify the user of the error and request they contact
        // us for further assistance
    }
}
catch (AuthnetAIMException $e)
{
    echo 'There was an error processing the transaction. Here is the error message: ';
    echo $e->__toString();
}