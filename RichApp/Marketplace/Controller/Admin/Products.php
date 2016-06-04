<?php
	
namespace Marketplace\Controller\Admin;
use RichApp\Controller;
use RichApp\Interfaces;
use Marketplace\Library as MLibrary;
use Marketplace\Model as MModel;
use RichApp\Model;

class Products extends Controller\Admin\Pages {
	
	use MLibrary\ProductsEvents;
	
	public function __construct()
	{
		parent::__construct();
		$this->pages_model = new Model\Pages();
		$this->products_model = new MModel\Products();
		$this->series_model = new MModel\Series();
		$this->atts_model = new MModel\Attributes();
		$this->img_links = new Model\ImageLinks();
		$this->man_model = new MModel\Manufacturers();
		
		$this->setPageType('product');
		
	}
	
	public function index($page=1)
	{

		$all = $this->products_model->findAllProducts(
				array(
					'select'=> 'pages.title, pages.id, pages.pagetype, pages.created_on, pages.published, pages.published_on, products.id as pid',
					'from'=> 'products',
					'join' => array('table'=>'pages', 'on'=>'pages.id=products.page_id', 'type'=>'left'),
					'where'=> array(
						array('field'=>'pages.pagetype', 'op'=>'=', 'value'=>'product')
					)
				)
			);

		$this->setData('page_title', 'All Products');
		$this->setData('listing', $all);
		$this->render('listing');
	}
	
	public function create()
	{
		
		$this->buildForm(array('action'=>$this->data['redirectlink'].DS.'save'));
		$this->setData('form', $this->forms->get());
		$this->setData('page_title', 'New Product');
		$this->addAsset('script', '/assets/admin/js/marketplace.js');
		
		$this->render('add-edit');
	}
	
	public function edit($id=null)
	{
		$data = $this->products_model->getOne('page_id', $id);
		$page = $this->pages_model->getOne('id', $id);
		$page->product = $data;
		$attributes = $this->atts_model->getAll();
		
		//$this->startForm('/admin/mp/products/save/'.$id, $page);
		$this->buildForm(array('action'=>$this->data['redirectlink'].DS.'save'.DS.$id, 'pagedata'=>$page));

		$this->setData('form', $this->forms->get());
		$this->setData('page_title', $page->title);
		$this->addAsset('script', '/assets/admin/js/marketplace.js');
		$this->render('add-edit');
	}
	
	public function delete($id=null)
	{
		$file = $this->products_model->getOne('page_id', $id);
		$this->products_model->delete($file->id);
		$this->pages_model->delete($id);
		$this->app->redirect('/admin/marketplace/products');
	}
	
	public function showProductList()
	{
		$list = $this->products_model->getListing();
		print_r($list);
		$listing = '';
		$listing = '<div id="product-listing" class="grid-listing">';
        $listing .= '<div class="close-grid clearfix"><a href="#"><span class="fa fa-times btn btn-default"></span></a></div>';
		$listing .= '<div class="clearfix listing-holder">';
        $listing .= '<div class="product-list-header row">';
        $listing .= '<span class="prod-list-title col-xs-4">Product Title</span>';
		$listing .= '<span class="prod-list-available col-xs-4 text-right pull-right">Available to Add</span>';
        $listing .= '</div>';
		$listing .= '<div class="list-group">';
        foreach($list as $k => $v)
        {	
			 $listing .= '<a data-product-id="'.$v['prod_id'].'" data-inventory="'.$v['inventory'].'" data-product-title="'.$v['title'].'" class="add-product list-group-item">'.$v['title'].'<span class="badge">'.$v['inventory'].'</span></a>';
        }
		$listing .= '</div>';
       	$listing .= '</div>';
        $listing .= '</div>';
		$listing .= '</div>';
		echo $listing;
	}
	
}
?>