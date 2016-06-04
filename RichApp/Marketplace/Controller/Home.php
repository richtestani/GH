<?php
	
namespace Marketplace\Controller;
use RichApp\Core\Controller;
use RichApp\Core\Auth;
use RichApp\Model;

class Home extends Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new \Marketplace\Library\Marketplace_API();
	}
	
	public function index()
	{
		$this->setData('api', $this->api);
		$this->render('home');
	}
	
    private function setListingForm($data = null)
    {
        $values = new \stdClass();
        $properties = $this->listings_model->getProperties();
        foreach($properties as $prop)
        {
            $values->{$prop} = (!is_null($data)) ? $data->$prop : '';
        }
		
        $this->forms->insertRow('price');
        $this->forms->insertInput('text', 'price', $values->price, array('class'=>'form-control', 'id'=>'price'));
		$this->forms->setLabel('Listing Price', 'price');
        $this->forms->endRow();
        $this->forms->insertRow('qty');
        $this->forms->insertInput('text', 'qty', $values->qty, array('class'=>'form-control', 'id'=>'qty'));
		$this->forms->setLabel('How Many For Sale?', 'qty');
        $this->forms->endRow();
        $this->forms->insertRow('products');
        $this->forms->insertInput('button', 'product-add', 'Add Product', array('class'=>'btn btn-default product-add'));
		if(isset($data->bundles))
		{
			$this->forms->insertHTML('<div id="products-listing"><table class="table table-striped">');
			foreach($data->bundles as $b)
			{
				$this->forms->insertHTML('<tr class="product-list-item">');
				$prod = $this->bundles_model->findProductBundle($b->product_id);
				$this->forms->insertInput('hidden', 'product_listing_item[]', $b->product_id);
				$this->forms->insertHTML('<td class="col-lg-6">'.$prod[0]['title'].'</td>');
				$this->forms->insertHTML('<td>');
				$this->forms->insertInput('text', 'product_listing_qty[]', $prod[0]['inventory']);
				$this->forms->setLabel('Inventory', 'inventory');
				$this->forms->insertHTML('</td>');
				$this->forms->insertHTML('<td><a class="btn btn-default fa fa-times remove-product-listing-item"></a></td>');
				$this->forms->insertHTML('</tr>');
			}
			$this->forms->insertHTML('</table></div>');
		}
        $this->forms->endRow();
        $this->forms->insertRow('attributes');
        $this->forms->insertInput('button', 'attribute-add', 'Add Attribute', array('class'=>'btn btn-default attribute-add', 'type'=>'button'));
        $this->forms->endRow();
       
    }
	
}
?>