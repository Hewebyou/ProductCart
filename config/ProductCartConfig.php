<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
return [
    /**
     * manage the cart default driver as a Database 
     * if you a user guest the driver change to Session 
     */
    'driver' => Heesapp\Productcart\Controllers\DatabaseController::class,
    /**
     * number format
     */
    'number' => [
        'decimals' => 0,
        'dec_point' => '.',
        'thousands_sep' => ','
    ],
    /**
     * The authentication guard that should be identify the user logged
     */
    'guard_name' => 'web',
    /**
     * setting a shipping charges to customer the default
     * value is a 0 and order amount = subtotal-discount 
     * for example 100 product each product 3$ 
     * the subtotal = 100*3$ =300$;
     * the discount  = 5% of total subtotal
     * shipping charges = subtotal - subtotal*(5/100) 
     * net total price after discount = 300$  - 300$*(5/100) =285$
     * shipping charges  add to net total price 
     */
    'shipping_charges' => 10,
    /**
     * threshold value of shipping value 
     * threshold value specify the minimum orders 
     * to shipping charges if 100 order the shipping charges
     * value = 0 , more than the shipping charge increment
     */
    'shipping_charges_threshold' => 100,
    /**
     * setting the tax value  
     * total price = 285$ if add tax = 6% 
     * the  total price = 285 + 285 *(6/100) = 302.1$
     */
    'tax_precentage' => 3,
    /**
     * setting round off of total price such as 
     * 99.95$+.05 =100$
     * 99.85$ +.05 = 99.90$ 
     */
    'round_off' => .05,
    /**
     * Name of the cookie that is used to identify a user session
     */
    'cookie_name' => 'cart_session',
  
 
    /**
     * cookie time  in a week 
     * 7 day * 24 hour * 60 min = 10080 sec 
     */
    'cookie_lifetime' => 10080,
    /**
     * To set the currency symbol. We use php's 
     * native money_format() function
     * in combination with setlocale() 
     * to display currency with amounts
     */
    'LC_MONETARY' => 'en_US.UTF-8',
    /**
     * For Database driver only: Number of hours for which the cart data is considered valid
     * You can run/schedule the lcm_cart:clear command to remove old/invalid data
     */
    'cart_data_validity' => 24 * 7, // a week
];
