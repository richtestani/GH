<?php
return [
	'version' => '0.7.0',
	'Dashboard' => [
		'modules' => [
		]
	],
	'Controllers' => [
		'settings' => [
			'actions'=> [
				'Marketplace'=> [
					'route' => '/admin/settings/edit/marketplace'
				]
			]
		],
		'marketplace' => [
			'label' => 'Marketplace',
			'icon' => 'fa fa-shopping-basket',
			'actions' => [
				'Products' => [
					'group' => [
						'All' => [
							'route' => '/admin/marketplace/products'
						],
						'New' => [
							'route' => '/admin/marketplace/products/create'
						]
					]
				],
				'Listings' => [
					'group' => [
						'All' => [
							'route' => '/admin/marketplace/listings'
						],
						'New' => [
							'route' => '/admin/marketplace/listings/create'
						]
					]
				],
				'Series' => [
					'group' => [
						'All' => [
							'route' => '/admin/marketplace/series'
						],
						'New' => [
							'route' => '/admin/marketplace/series/create'
						]
					]
				],
				'Featured' => [
					'group' => [
						'All' => [
							'route' => '/admin/marketplace/featured'
						],
						'New' => [
							'route' => '/admin/marketplace/featured/create'
						]
					]
				],
				'Manufacturers' => [
					'group' => [
						'All' => [
							'route' => '/admin/marketplace/manufacturers'
						],
						'New' => [
							'route' => '/admin/marketplace/manufacturers/create'
						]
					]
				],
				'Sellers' => [
					'group' => [
						'All' => [
							'route' => '/admin/marketplace/sellers'
						],
						'Find & Add' => [
							'route' => '/admin/marketplace/sellers/find'
						]
					]
				]
			]
		]
	]
];
?>