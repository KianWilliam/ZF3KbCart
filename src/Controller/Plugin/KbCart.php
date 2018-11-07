<?php 
namespace Kbcart\Controller\Plugin;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Config\Factory;
use Zend\Crypt\Password\Bcrypt;
use Zend\Session\SessionManager;


class KbCart extends AbstractPlugin
{
	    private $session;
		private $config;
		private $counter=0;
		
		
		private $cartval = 0;
		
		
		public function setSession($session)
		{
			$this->session = $session;
		}
		 public function setConfig($config)
        {
           $this->config = $config;
		}
		
		public function insert($filename)
		{
				$item= Factory::fromFile($filename);
			    if (! is_array($item) or empty($item)) {
                      throw new \Exception('Arrange the xml file structure first to have an array.');
                 }
				
				if(! is_array($this->session['cart'])){
				 		       $this->session['cart'] = array();
                            
						
				 }						   

				 
					  $token = $this->generateToken($item);
					  foreach($item as $key=>$value)
							 $itemTotalPrice = floatval($item[$key]['price'])*intval($item[$key]['quantity']);	
						 
						 $item[$key]['itemtotalprice'] = $itemTotalPrice;
						 
                         $this->session['cart'][$token] = $item;
						 $this->cartTotalPrice();
						 unlink($filename);
						 return true;
						 

		}
        public function update($token, $quantity)
		{
			$item = $this->session['cart'][$token];
//			var_dump($item);
			$cat = array_keys($item);
	//		var_dump($cat);
		//	echo $item[$cat[0]]['quantity'];
			if(intval($item[$cat[0]]['quantity'])!== intval($quantity))
			{
				$this->session['cart'][$token][$cat[0]]['itemtotalprice']=floatval($item[$cat[0]]['price'])*intval($quantity);
				$this->session['cart'][$token][$cat[0]]['quantity']=intval($quantity);

				$this->cartTotalPrice();
				return true;
			}
			return false;
		}

		 public function remove($token)
        {
           unset($this->session['cart'][$token]);
		   $this->cartTotalPrice();
           return true;
        }
		 public function destroy()
         {
            $this->session->offsetUnset('cart');
            return true;
         }
		
		 private function generateToken($item)
        {
			$category = array_keys($item);	
			$value = array_values($item);
			$vals = array_values($value[0]);
			
           if (! is_array($item) or empty($item)) {
            throw new \Exception('The item is not an array of data.');
          }
		  $bcrypt = new Bcrypt();
          $token = $bcrypt->create($category[0] . $vals[0]);
		  return $token;
        }
		
		private function cartTotalPrice()
		{
			foreach($this->session['cart'] as $key=>$value)
			if(is_array($value)){
			  foreach($value as $subkey=>$sval)
			       foreach($sval as $ids=>$vals)
			             if($ids==='itemtotalprice')
						 {
							$this->cartval += $vals;
						 }
			}


						 if(!empty($this->config['vat']))
						 {
							  $vat = ($this->cartval/100)*floatval($this->config['vat']);
							  $this->cartval += $vat;
						 }
			   
			  $this->session['cart']['cartval'] = $this->cartval;
			   
		}
		public function cart()
		{
			return $this->session['cart'];
		}
	
		


}

