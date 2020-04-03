 
 # Product Cart and WitchList  
 this package very esay to use operation cart or withList and moved Products between
 in this package can use three type opreation 
 ```
 1.opreation Cart or WitchList with Product Model 
 2. opreation Cart or WitchList with Cart Item Collection
 3. opreation Cart or Witch List with Index of Collection 
 ````
# 1 Configuration 
1.this Package can work with to session or Database by Driver attribute value in config file productcart.php
2.the configuration can migration an Cart Table and Cart Items Table by one command and migration the witch List Table
## 1.1 generate the config file
to generate the config file using this command 
```
php artisan ProductCart:Config
```
## 1.2 Driver 
Driver config the package use a session Driver or Database Deiver 
can change between session and database Driver by this commands the defalut Driver is 
a Database Driver 
## 1.2.1 Database Driver 
this command a default driver is database driver 
can use this command
```
php artisan ProductCart:Driver
```
or using command
```
php artisan ProductCart:Driver  --driver=database
```
if you will use the Database Driver can be generate the migration files by this command

if use only Cart run This Command can migration the Items Table to use with Witch List and Cart
```
php artisan ProductCart:CartTables
```
if use WitchList can run this command
```
php artisan  ProductCart:WitchListTable
```
## 1.2.2 session Driver 
if you work as session can only run this command 
```
php artisan ProductCart:Driver  --driver=session
```
## 1.3 Attribute of the Configuration file
1.shipping charges: default value is a 10 setting a shipping charges to customer the default  value is a 0 and order amount = subtotal-discount for example:
```
100 product each product 3$ 
      * the subtotal = 100*3$ =300$;
      * the discount  = 5% of total subtotal
      * net total = subtotal - subtotal*(5/100) 
      * net total price after discount = 300$  - 300$*(5/100) =285
      *if net total larger than threshold value of shipping charges and by add the shipping charges 
      * if defalut value = 10 and net total > shipping charges threshold 
      *net total + shipping charges = total price before add tax value
   ```  
2. shipping charges threshold :threshold value of shipping value threshold value specify the minimum orders 
to shipping charges if 100 order the shipping charges value = 0 , more than the shipping charge increment

3.tax precentage :default value is a 3  setting the tax value
```
example:
     * total price = 285$ if add tax = 6% 
     * the  total price = 285 + 285 *(6/100) = 302.1$
 ```
4.round off  default value = .05
```
example
     * setting round off of total price such as 
     * 99.95$+.05 =100$
     * 99.85$ +.05 = 99.90$
 ```
# 2 Cart Opreation 
this opreation is :
```
1.add product to Product 
2.move to WitchList 
3.Clear Cart 
4.Incremment Item quantity  
5.Decremment Item quantity
6.Remove Item From Cart
7.Print  Cart
8.Apply Discount
```
this opreations work with three type method for example:
```
1.add Cart by Proudct Model
2.add Cart by Cart Items collection from database or session
3.add Cart by Index 
```
## 2.1 Opreation Cart With Product model id
 in first be add this trait to Product model 
 ```php
 .............
 use Heesapp\Productcart\Traits\Cartable;
 .............
 class Product extends Model {
    
    use Cartable ;
    .....
    }
  ```
## 2.1.1 add Item Opreation by model id

can add item to cart  by id of model
addTo cart the arg of method id of model and quantity value the default quantity value =  1
and this method return $cart is to Array  with Cart Items
```php
  ......
  
  $product = Product::where('id', $id)->first();
  //with default quantity value
  $cart = Product::addToCart($product->id);
  //with change quantity value 
  $cart = Product::addToCart($product->id , 5);
  
  .....
```
## 2.1.2 remove Item opreation with model id
   can remove item from cart  by id of model
   return is cart  array with items after removed
   ```php
   .....
    $product = Product::where('id', $id)->first();
    $cart = Product::removeFromCart($product->id);
    ......
   ```
   ## 2.1.3 Increment Item quantity by model id
   can  iecrement  item  quantity in the  cart  by id of model
   return is cart  array with items after incremented
   ```php
   .....
    $product = Product::where('id', $id)->first();
    //with default quantity
    $cart = Product::IncrementQuntity($product->id);
    
    //with change quntity (increment quantity by 5)
    $cart = Product::IncrementQuntity($product->id , 5);
    ......
   ```
   ## 2.1.4 decrement Item quantity by model id
   can  decrement  item  quantity in the  cart  by id of model
   return is cart  array with items after decremented
   ```php
   .....
    $product = Product::where('id', $id)->first();
    //with default quantity
    $cart = Product::DecrementQuntity($product->id);
    
    //with change quntity (decrement quantity by 5)
    $cart = Product::DecrementQuntity($product->id ,5);
    ......
   ```
## 2.1.5 move Cart Item to WitchList 
if you user mastik to add the product to cart and in preduce the order detect this product can 
remove or can move to witchList by model id 
```php
   .....
    $product = Product::where('id', $id)->first();
    $cart = Product::moveToWitchList($product->id);
    ......
```
## 2.2 Opreation with Helper method  
can use ProductCart() without use Cartable trait 
```
helper method ProductCart()
```
```
1.add item by model only avalibale
2.remove item by model or by database collection or index 
3.Increment of quantity by model or by database collection or by index  
4.decrement of quntity by  by model or by database collection or index 
5.move to witchList by  by model or by database collection or index
6.Apply discount by presantge or value 
7. print cart by two mtehod
```
## 2.2.1 Helper method opreation by model 
## 2.2.1.1 add to Cart by model 
```php 

....

//with default quantity 
 $product = Product::where('id', $id)->first();
 ProductCart()->addCart($product);
  //with quantity value 
   $product = Product::where('id', $id)->first();
   ProductCart()->addCart($product,5);
 ....
```
## 2.1.1.2 remove cart by model
```php
.....

  $product = Product::where('id', $id)->first();
  ProductCart()->removeMItem($product);
.....
```
## 2.1.1.3 Increment cart by model
```php
....
//with defalut quantity 
    $product = Product::where('id', $id)->first();
    ProductCart()->IncrementItem($product);
 // with quantity value =5
    $product = Product::where('id', $id)->first();
    ProductCart()->IncrementItem($product,5);
    ....
```
## 2.1.1.4 decrement cart by model
```php
....
//with defalut quantity 
    $product = Product::where('id', $id)->first();
    ProductCart()->DecrementItem($product);
 // with quantity value =5
    $product = Product::where('id', $id)->first();
    ProductCart()->DecrementItem($product,5);
    ....
```
## 2.1.1.5 move to witchList by model
```php
....
    $product = Product::where('id', $id)->first();
    ProductCart()->moveMToWitchList($product);

    ....
```
## 2.2.2 Helper method opreation by Item database collection 

## 2.1.2.1 remove cart by Item database collection
```php
.....

    $Item = DB::table('items_cart')->where('model_id', $product->id)->first();
    ProductCart()->removeXItem($Item);
.....
```
## 2.1.2.2 Increment cart by Item database collection
```php
....
//with defalut quantity 
    $Item = DB::table('items_cart')->where('model_id', $product->id)->first();
    ProductCart()->IncremenXtItem($Item);
 // with quantity value =5
    $Item = DB::table('items_cart')->where('model_id', $product->id)->first();
    ProductCart()->IncremenXtItem($Item,5);
    ....
```
## 2.1.2.3 decrement cart by Item database collection
```php
....
//with defalut quantity 
    $Item = DB::table('items_cart')->where('model_id', $product->id)->first();
    ProductCart()->DecremenXtItem($Item);
 // with quantity value =5
    $Item = DB::table('items_cart')->where('model_id', $product->id)->first();
    ProductCart()->DecremenXtItem($Item,5);
    ....
```
## 2.1.2.4 move to witchList by Item database collection
```php
....
    $Item = DB::table('items_cart')->where('model_id', $product->id)->first();
    ProductCart()->moveXToWitchList($Item);

    ....
```
## 2.2.3 Helper method opreation by Item index 
## 2.1.3.1 remove cart by   Item index 
```php
.....

    $Item = DB::table('items_cart')->where('model_id', $product->id)->first();
    ProductCart()->removeXItem($Item);
.....
```
## 2.1.3.2 Increment cart by   Item index 
```php
....
// increment first item  with defalut quantity 
      $cartItems = ProductCart()->toArray(true)['CartItems'];
    foreach ($cartItems as $key=>$items){
        return ProductCart()->IncrementQuntity($key);
    }
 //increment first with quantity value =5
     $cartItems = ProductCart()->toArray(true)['CartItems'];
    foreach ($cartItems as $key=>$items){
        return ProductCart()->IncrementQuntity($key , 5); 
    }
    ....
```
## 2.1.3.3 decrement cart by Item index 
```php
....
//with defalut quantity 
     $cartItems = ProductCart()->toArray(true)['CartItems'];
    foreach ($cartItems as $key=>$items){
        return ProductCart()->DecrementQuntity($key);
    }
 // with quantity value =5
     $cartItems = ProductCart()->toArray(true)['CartItems'];
    foreach ($cartItems as $key=>$items){
        return ProductCart()->DecrementQuntity($key,5);
    }
    ....
```
## 2.1.3.4 move to witchList by Item index
```php
....
    $cartItems = ProductCart()->toArray(true)['CartItems'];
    foreach ($cartItems as $key=>$items){
        return ProductCart()->moveToWitchList($key);
    }

    ....
```
## 2.2.4 Helper method with Apply Discount
1.Apply discount by presantage
    
```php 
 //discount = subtotal * discount_percentage /100
....
 ProductCart()->ApplyDiscount(30); //30 %
....
```
 2.Apply discount by price
       ```php
    ....
    ProductCart()->ApplyDiscountValue(200); //200$
    ....
       ```
       
## 2.2.5 Helper method Print Cart Values 
print the total price and tax value and shipping charges and net total and tax value 
```
ProductCart()->PrintCartData();
or
 ProductCart()->data();
 or
 ProductCart()->toArray(); // with not print Itemes
 ProductCart()->toArray(true); with print Items //
 ```
out put 
```
with discount value
{"subtotal":"572.97","discount":"30.00","discout_percentage":0,"coupon_id":null,"shipping_charges":0,"net_total":0,"tax":"16.29",
"total":"559.26","round_off":0,"payable":"559.30"}

with discount presantage
{"subtotal":"954.95","discount":"286.49","discout_percentage":0,"coupon_id":null,"shipping_charges":0,"net_total":0,"tax":"20.05",
"total":"688.51","round_off":0,"payable":"688.60"}
```
## Clear Cart 
```
    ProductCart()->clearCart();
```
# 3 Witch List 
witch List the same cart with simple different the witch list work with this operation.
```
1.add product to Witch List 
2.move to Cart  by model or database collection or index
3.Clear witchList 
6.Remove Item From Witch List  by model or database collection or index
7.print items 
```
## 3.1 witch List with model 
 can use this code
 ```php
 ....
 
 use Heesapp\Productcart\Traits\WitchListable;
 
 ....
 class Product extends Model {
        use WitchListable; 
 ....
 }
 
 ```
## 3.1.1 add item to witchList by modle 
```php 
....
    $product = Product::where('id', $id)->first();
    $witchList = Product::addToWithList($product->id);
    ....
````
## 3.1.2 remove item from witchList 
```php 
....
    $product = Product::where('id', $id)->first();
    $witchList = Product::removeFromWitchList($product->id);
    ....
````
## 3.1.3 move item to cart 
```php 
....
    $product = Product::where('id', $id)->first();
    $witchList = Product::moveToCart($product->id);
    ....
````
## 3.2 Opreation with Helper method  
use Helper method
````
ProductWitchList()
````
## 3.2.1 add to WitchList by model 
```php
....
  $product = Product::where('id', $id)->first();
    ProductWitchList()->addWhitcList($product);
    ...

```
## 3.2.2 remove witch list by model 
```php
.....
   $product = Product::where('id', $id)->first();
   $witchList = ProductWitchList()->removeMWItem($product);
    .....
  ```
  ## 3.2.3 move to cart by model 
  ```php
  ....
      $product = Product::where('id', $id)->first();
      $witchList = ProductWitchList()->moveMToCart($product);
    ....
 ```
 ## 3.2.4 remove from witch List by database collection 
 
 ```php
 .....
  $Item = DB::table('items_cart')->where('model_id', $product->id)->first();
  $witchList = ProductWitchList()->removeXWItem($Item);
 ....
 ```
 ## 3.2.5 move to witchList by database collection 
 ```php 
....
$cart_item = DB::table('items_cart')->where('id' , $id)->first();
$witchList = ProductWitchList()->moveXToCart($product);
....
```
## 3.2.6 remove from witchList by index
 ```php 
....
   $witchListItems = ProductWitchList()->data()['WitchListItems'];
   foreach ($witchListItems as $key=>$item){
    ProductWitchList()->removeWItem($key);
   }
....
```
##  3.2.7 move to cart by index

 ```php 
....
    $witchListItems = ProductWitchList()->data()['WitchListItems'];
   foreach ($witchListItems as $key=>$item){
    ProductWitchList()->moveToCart($key);
   }
....
```
## 3.2.8 Print  Witch List 
  ```php
  ProductWitchList()->data(); 
 or 
  ProductWitchList()->toArray();
  ```
  out put 
  ```
 print =  {"WitchListItems":[{"model_type":"App\\Product","model_id":4,"name":"Bl.dfg","price":"190.99","image":null,"quantity":"1.00","id":39},{"model_type":"App\\Product","model_id":3,"name":"sdfcdhf","price":"189.99","image":null,"quantity":"1.00","id":40},{"model_type":"App\\Product","model_id":2,"name":"dsfg","price":"1300.00","image":null,"quantity":"1.00","id":41},{"model_type":"App\\Product","model_id":1,"name":"Al.kjsa","price":"200.25","image":null,"quantity":"1.00","id":42}]}
  ```
 


