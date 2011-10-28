// Instantiate our class
    $payment = new Authnet();
    

    // Set transaction variables
    // $total = 1.00;
    // ...
    

    // Set our basic transaction information
    $payment->setTransaction($creditcard, $expiration, $total, $cvv, $invoice, $tax);
    

    // Set other transaction parameters. Set the transaction to be AUTH_ONLY.
    $payment->setTransactionType("AUTH_ONLY");
    // ...
    

    // Process the initial payment
    $payment->process();
    

    if ($payment->isApproved())
    {
        // Setup recurring billing here
        

        // Instantiate our ARB class
        $arb = new AuthnetARB();
        

        // Set recurring billing variables
        // ...
        

        // Set recurring billing parameters
        $arb->setParameter('amount', $total);
        $arb->setParameter('cardNumber', $creditcard);
        $arb->setParameter('expirationDate', $expiration);
        $arb->setParameter('firstName', $firstname);
        $arb->setParameter('lastName', $lastname);
        $arb->setParameter('address', $address);
        $arb->setParameter('city', $city);
        $arb->setParameter('state', $state);
        $arb->setParameter('zip', $zip);
        $arb->setParameter('email', $email);
        $arb->setParameter('subscrName', $userid);
        

        // Create the recurring billing subscription
        $arb->createAccount();
        

        // If successful let's get the subscription ID
        if ($arb->isSuccessful())
        {
            $arb_id = $arb->getSubscriberID();
        }
    }