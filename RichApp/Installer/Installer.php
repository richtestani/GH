<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RichApp\Installer;
use RichApp\Core\Controller;
use RichApp\Core;
use RichApp\Library;
use RichApp\Model\Users;

/**
 * Description of Installer
 *
 * @author richardtestani
 */
class Installer extends Controller {
	
	protected $install_dirname = 'Installer';
	protected $config_filename = 'RAConfig.php';
    
    public function __construct()
    {
        parent::__construct(false);
    }
   
    public function welcome() {
        
        $this->render('welcome');
        
    }
    
    public function step1()
    {
        $this->render('step1');
    }
    
    public function step2()
    {

        if( !$this->request->isPost() )
        {
            $this->app->redirect('/');
        }
        
        //check readbility
        $status = '';
        $continue = true;
        $app_perms = substr(sprintf('%o', fileperms(APPPATH)), -4);
        $img_perms = substr(sprintf('%o', fileperms(PUBPATH.'/images/')), -4);


        if((int)$app_perms < 775)
        {
            $status .= '<p>Your folder:<br><strong>'. APPPATH.'</strong><br> is not writable, please change the permissions before continuing</p>';
            $continue = false;
        }
        
        if((int)$img_perms < 775)
        {
            $status .= '<p>Your folder:<br><strong>'. PUBPATH.'/images/'.'</strong><br>is not writable, please change the permissions before continuing</p>';
            $continue = false;
        }
        
        $password = password_hash($this->request->post('password'), PASSWORD_BCRYPT);
        $username = $this->request->post('username');
        $params = ['raun'=>$username, 'rapw'=>$password];
        $_SESSION['richapp'] = $params;
		unset($_SESSION['install__db_error']);
        $this->setData('status', $status);
        $this->setData('continue', $continue);
        $this->render('step2');
    }
    
    public function step3()
    {
        if( !$this->request->isPost() )
        {
            $this->app->redirect('/');
        }
        
        $host = $this->request->post('host');
        $dbname = $this->request->post('database');
        $dbuser = $this->request->post('dbuser');
        $dbpass = $this->request->post('dbpass');
		$config = [
	        'dsn' => 'mysql:host='.$host.'; dbname='.$dbname,
	        'username' => $dbuser,
	        'password' => $dbpass
		];
        if( $this->testConnection($host, $dbname, $dbuser, $dbpass) )
        {
            $db = [
                'host' => $host,
                'dbname' =>$dbname,
                'dbuser' => $dbuser,
                'dbpass'=>$dbpass
            ];

            $_SESSION['radb'] = $db;
			
			//install db
			$file =  APPPATH.DS.$this->install_dirname.DS.'RichApp.sql';
			$file = file_get_contents($file);

			$this->query = new Library\Query($config);
			$this->query->exec($file);
            $this->render('step3');
        }
        else
        {
			$_SESSION['install__db_error'] = true;
            $this->app->redirect('/installer/step2');
        }
    }
    
    public function finalStep()
    {
        $email = $this->request->post('email');

        //write config
        $filename = $this->config_filename;
        $db = $_SESSION['radb'];
		
        //unset($_SESSION['radb']);
        extract($db);
        $config = <<<CONFIG
<?php
 return [
	 'Dashabord' => [
		 'modules' => [
		 	'recent' => ['Pages'],
			'views' => ['Pages/views']
		 ]
	 ],
	 //default controllers
	 'Controllers' => [
		 'dashabord' => [
			 'label' => 'Dashboard',
			 'icon' => 'fa fa-dashboard',
			 'actions' => []
	 	],
		'pages' => [
			'label' => 'Pages',
			'icon' => 'fa fa-file',
			'actions' => [
				'All' => [
					'icon' => 'fa fa-bars',
					'route' => '/admin/pages/'
					],
				'New' => [
					'icon' => 'fa fa-plus',
					'route' => '/admin/pages/create'
				]
			]
		],
		'images' => [
			'label' => 'Images',
			'icon' => 'fa fa-image',
			'actions' => [
				'All' => [
					'icon' => 'fa fa-bars',
					'route' => '/admin/images/'
					],
				'New' => [
					'icon' => 'fa fa-plus',
					'route' => '/admin/images/create'
				]
			]
		],
		'categories' => [
			'label' => 'Categories',
			'icon' => 'fa fa-check',
			'actions' => [
				'All' => [
					'icon' => 'fa fa-bars',
					'route' => '/admin/categories/'
					],
				'New' => [
					'icon' => 'fa fa-plus',
					'route' => '/admin/categories/create'
				]
			]
		],
		'settings' => [
			'label' => 'Settings',
			'icon' => 'fa fa-gear',
			'actions' => [
				'All' => [
					'icon' => 'fa fa-bars',
					'route' => '/admin/settings/'
					],
				'New' => [
					'icon' => 'fa fa-plus',
					'route' => '/admin/settings/create'
				]
			]
		],
		'users' => [
			'label' => 'Users',
			'icon' => 'fa fa-user',
			'actions' => [
				'All' => [
					'icon' => 'fa fa-bars',
					'route' => '/admin/users/'
					],
				'New' => [
					'icon' => 'fa fa-plus',
					'route' => '/admin/users/create'
				]
			]
		],
	 ],
    //database configuration
    'db' => [
	    'host' => '$host',
	    'dbname' => '$dbname',
	    'dbuser' => '$dbuser',
	    'dbpass' => '$dbpass'
    ]
 ];
?>
CONFIG;

        $this->filesystem->write('./RichApp/'.$filename, $config);
        //save db config
        $config = require APPPATH.'/'.$filename;
		
        //setup user
        $users = new Users();
        $user = $_SESSION['richapp'];
        
        extract($user);
        $richuser = [
            'username' => $raun,
            'password' => $rapw,
            'email' => $email,
            'role' => 1,
            'slug' => $users->slug($raun),
            'created_on' => $users->created_on(),
            'created_by' => 1,
            'modified_by' => 1,
            'active' => 1,
            'first' => '',
            'last' => ''
        ];

        
        $users->create($richuser);
        unset($_SESSION['richapp']);
        //redirect to admin
        $this->app->redirect('/admin');
    }
    
    public function testConnection($host, $dbname, $dbuser, $dbpass)
    {
        $dsn = 'mysql:host='.$host.'; dbname='.$dbname;
        $user = $dbuser;
        $pass = $dbpass;
		$config = [
	        'dsn' => $dsn,
	        'username' => $dbuser,
	        'password' => $dbpass
		];
        $this->query = new Library\Query($config);
		
		return $this->query->connected();
    }
    
}
