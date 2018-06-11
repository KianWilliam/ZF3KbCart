<?php
//namespace KbCart;
namespace kbcart;
return [
    'router' => [
        'routes' => [                   
        ],
    
	],
    'controller_plugins' => [
        'factories' => [
            Controller\Plugin\KbCart::class => Factory\KbCartFactory::class,
        ],
		'aliases'=>[
		    'kbcart' => Controller\Plugin\KbCart::class,
			 ],
    ],
	
];
