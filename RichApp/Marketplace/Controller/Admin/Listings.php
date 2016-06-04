<?php
	
namespace Marketplace\Controller\Admin;
use RichApp\Controller;
use RichApp\Library;
use Marketplace\Library as MLibrary;
use Marketplace\Model as MModel;

class Listings extends Controller\Admin\Pages {
	
	use MLibrary\ListingsEvents;
	
	public function __construct()
	{
		parent::__construct();
		$this->pages_model = new \RichApp\Model\Pages();
		$this->listings_model = new MModel\Listings();
		$this->products_model = new MModel\Products();
		$this->bundles_model = new MModel\Bundles();
		$this->html_listing = new Library\HTML\HTMLListing();
		$this->atts_model = new MModel\Attributes();
		$this->setPageType('listing');
	}
	
	public function index($page=1)
	{
		$args = array(
					'select'=> 'pages.title, pages.id, pages.pagetype, pages.created_on, pages.published, pages.published_on, listings.id as lid',
					'from'=> 'listings',
					'join' => array('table'=>'pages', 'on'=>'pages.id=listings.page_id', 'type'=>'left'),
					'where'=> array(
						array('field'=>'pages.pagetype', 'op'=>'=', 'value'=>'listing')
					)
				);
		$all = $this->listings_model->getAll($args);
		$this->setData('page_title', 'All Listings');
		$this->addAsset('script', '/assets/admin/js/marketplace.js');
		$this->setData('listing', $all);
		$this->render('listing');
	}
	
	public function create()
	{
		$this->buildForm(array('action'=>'/admin/marketplace/listings/save'));

		$this->setData('form', $this->forms->get());
		$this->setData('page_title', 'New Listing');
		$this->addAsset('script', '/assets/admin/js/marketplace.js');
		$this->render('add-edit');
	}
	
	public function edit($id=0)
	{
		$data = $this->listings_model->getOne('page_id', $id);
		
		$page = $this->pages_model->getOne('id', $id);
		$page->listing = $data;
		
		$args = array(
			'where'=>array(
				array('field'=>'item_id', 'op'=>'=', 'value'=>$data->id),
				array('field'=>'type', 'op'=>'=', 'value'=>'listing', 'andor'=>'AND')
			)
		);

		$this->buildForm(array('action'=>'/admin/marketplace/listings/save/'.$id, 'pagedata'=>$page));
		$this->setData('form', $this->forms->get());
		$this->setData('page_title', $page->title);
		$this->addAsset('script', '/assets/admin/js/marketplace.js');
		$this->render('add-edit');
	}
	
    private function setListingForm($data = null)
    {
        $values = new \stdClass();
        $properties = $this->listings_model->getProperties();
        foreach($properties as $prop)
        {
            $values->{$prop} = (!is_null($data)) ? $data->$prop : '';
        }
		
        $this->forms->insertDiv('price');
        $this->forms->insertInput('text', 'price', $values->price, array('class'=>'form-control', 'id'=>'price'));
		$this->forms->setLabel('Listing Price', 'price');
        $this->forms->endDiv();
        $this->forms->insertDiv('qty');
        $this->forms->insertInput('text', 'qty', $values->qty, array('class'=>'form-control', 'id'=>'qty'));
		$this->forms->setLabel('How Many For Sale?', 'qty');
        $this->forms->endDiv();
        $this->forms->insertDiv('products');
        $this->forms->insertInput('button', 'product-add', 'Add Product', array('class'=>'btn btn-default product-add'));
		if(isset($data->bundles))
		{
			$this->forms->insertHTML('<div id="products-listing"><table class="table table-striped">');
			foreach($data->bundles as $b)
			{
				$this->forms->insertHTML('<tr class="product-list-item">');
				$prod = $this->bundles_model->findProductBundle($b->product_id);

				if(!empty($prod))
				{
					$this->forms->insertInput('hidden', 'product_listing_item[]', $b->product_id);
					$this->forms->insertHTML('<td class="col-lg-6">'.$prod[0]['title'].'</td>');
					$this->forms->insertHTML('<td>');
					$this->forms->insertInput('text', 'product_listing_qty[]', $b->qty);
					$this->forms->setLabel('Inventory', 'inventory');
					$this->forms->insertHTML('</td>');
					$this->forms->insertHTML('<td><a class="btn btn-default fa fa-times remove-product-listing-item"></a></td>');
					$this->forms->insertHTML('</tr>');
				}
			}
			$this->forms->insertHTML('</table></div>');
		}
        $this->forms->endDiv();
        //$this->forms->insertRow('attributes');
        //$this->forms->insertInput('button', 'attribute-add', 'Add Attribute', array('class'=>'btn btn-default attribute-add', 'type'=>'button'));
        //$this->forms->endRow();
       
    }
	
}
?>