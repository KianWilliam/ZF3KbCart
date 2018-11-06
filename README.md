# ZF3KbCart
simple shopping cart for zend frame work 3
KbCart
============================

A Simple shopping cart for zf3. Based on the information provided by routes in your website, use the functionality of it. I did not use hydrator in this version just simple array to array with no stuff attached.
I got the basic idea from Aleksander Cyrkulewski who used hydrator, I used zend-config xml file creation and delete and collect the stuff in an array,
obtaining data directly from array, here the user could specify as many as subcategories needed by following my way and reflech it in shopping cart view
of her/his website. 
Do not forget you need zend-config and zend-crypt to use this module. 

Zend Framework 2 and 3
------------
This module could be used both in zf2 and zf3

Installation
------------
For the installation uses composer [composer](http://getcomposer.org "composer - package manager").
Add this project in your composer.json:


    "require": {
        "kbcart/zf3-kbcart": "@dev"
    }
    

Post Installation
------------
Configuration:
- Add the module of `config/application.config.php` under the array `modules`, insert `kbcart`.
- Copy a file named `kbcart.global.php` to `config/autoload/`.
- You may change this file based on your application parameters.


Examples
=====================================

Before showing examples, few explanations could be to your benefit:
Supposing you have a store, each item is located in a page, clicking on it directs your customer to detailed page for that item with the help of its route.
the customer selects the item with the required quantity, all reflected in route.
To have category and subcategorie(s), this data must be reflected as an option or param in route (directly or via getting breadcrumb) then fixing
the param option in route for that form. The protocol must be like this: $this->url('routename', [], ['query'=>['category':'category-subcategory-...']]).
In related action controller: $this->kbcart();

Insert
------------
You can insert as many items to the cart as you want. Each entry in cart will have unique token to work with.
In related  controller include:
use Zend\Config\Config;
use Zend\Config\Writer\Xml;

in controller action:

 $kbcart = $this->kbcart();
	 
	
	 $config = new Config([], true);
	 GET $category from query as explained above. e.g:
	 $category='books-physic-newton';
	 $config->$category=[];
	 you MUST do exactly according below, the same order and words:
	 the values below is obtained by route, remember no money sign like Euro or dollar sign for price, just float numbers.
	 $config->$category->itemid="1";
	 $config->$category->name="principa";
	 $config->$category->price="19.19";
	 $config->$category->quantity="1";
	 
	 $writer = new Xml();
	 
	 a temporary file will be created, if two or more users apply for the same item at the same time,
	 randome functions prevent from being created the same file for different customers.
	 
	  srand(time());
	  $num=rand(1, 1000);
	 
	 $writer->toFile(__DIR__.'/../kbcart_'.$num.'.xml',$config);
	 $kbcart->insert(__DIR__.'/../kbcart_'.$num.'.xml');
	 
	 The created file shall be deleted automatically after insert into your cart.
	 Also you can use var_dump($this->kbcart()->cart) to check the content of basket.



Remove
------------
```php
In cart view template page, when you assign the session array to the form(s) route there, token will be assigned 
as a param or query and could be obtained from route.
$token : '4b848870240fd2e976ee59831b34314f7cfbb05b';
$this->kbcart()->remove($token);
```

Update
------------
```php
In cart view template page, when you assign the session array to the form(s) route there, token will be assigned 
as a param or query and could be obtained from route.
$token : '4b848870240fd2e976ee59831b34314f7cfbb05b';
$this->kbcart()->update($token, $amount);
```

Destroy
------------
Erase Kb Cart completely.
```php
$this->kbcart()->destroy();
```

Cart
------------
View all contents of cart:
```php
$this->kbcart()->cart();
```

Total Sum
------------
```php
In each update, insert or delete of an item shall be calculated automatically,
to view it: $kbcart = $this->kbcart()->cart();
echo $kbcart['cartval'];
```


To pass the cart to view template
------------
```php
return new ViewModel(['cart'=>$this->kbcart()->cart()]);
    
```

KbCart
=====================================

The provided cart is quite practical, having the itemid, all data to that item is accessible from database or file
no need to make cart more complex with other options like discount , ...
So the simpler the more practical.
For insert:
In action controller first check the cart and its items. suppose the customer selected the same item two different times
if not, then insert should be used.



Main Functions
------------
<table>
    <tr>
    <td>Function</td>
    <td>Description</td></tr>
    <tr><td>$this->kbcart()->insert();</td><td>Add item(s) to cart.</td></tr>
    <tr><td>$this->kbcart()->remove();</td><td>Delete the item from the cart.</td></tr>
    <tr><td>$this->kbcart()->destroy();</td><td>Delete all items from the cart.</td></tr>
    <tr><td>$this->kbcart()->cart();</td><td>Extracts all items from the cart.</td></tr>
    <tr><td>$this->kbcart()->cartTotalPrice();</td><td>Counts the total sum of all items in the cart.</td></tr>
    <tr><td>$this->kbcart()->update();</td><td>to update the quantity for an item.</td></tr>
</table>

Developed by
=====================================

Kian William Nowrouzian - mezmer121@gmail.com
