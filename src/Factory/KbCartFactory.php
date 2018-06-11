<?php
/**
 * ShoppingCart Factory
 * Initializate Shopping Cart
 * 
 * @package KbCart
 * @subpackage Factory
 * @author Kian William Nowrouzian
 */
//namespace KbCart\Factory;
namespace Kbcart\Factory;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Zend\Session\SessionManager;

use Zend\Session\Container;
use kbcart\Controller\Plugin\KbCart;



class KbCartFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('ServiceManager')->get('Configuration');
        if (! isset($config['kb_cart'])) {
            throw new \Exception('Configuration KbCart not set.');
        }
				$sessionManager = $container->get(SessionManager::class);



		$kbcart = new KbCart();
		$session_container = new Container($config['kb_cart']['session_name']);
      //  $session_container->last_login = date('Y-m-d H:i:s');
        //$session_container->sess_token = trim(base64_encode(md5(microtime())), "=");
		$kbcart->setSession($session_container);
		$kbcart->setConfig( $config['kb_cart']);
	
       // return new KbCart(new Container($config['kb_cart']['session_name']), $config['kb_cart']);
	   return $kbcart;
    }
}