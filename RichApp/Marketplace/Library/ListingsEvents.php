<?php

namespace Marketplace\Library;

trait ListingsEvents {
	
	public function afterDelete($data=array())
	{
		$id = $data[0];
		
		$file = $this->listings_model->getOne('page_id', $id);
		$this->listings_model->delete('listings', $file->id);
		$this->pages_model->delete('pages', $id);
		$this->app->redirect('/admin/marketplace/listings');
		
	}
	
	public function afterForm($data=array())
	{
		extract($data);
		$listing = null;
		
		$listing = (isset($pagedata->listing)) ? $pagedata->listing : $listing;
        $values = new \stdClass();
		
        $properties = $this->listings_model->getProperties();
        foreach($properties as $k => $prop)
        {	
            $values->{$prop} = (!is_null($listing)) ? $listing->$prop : '';
        }

		$attributes = (is_object($listing) && $listing->id > 0) ? $this->atts_model->getItemAttributes('listing', $pagedata->id) : array();
		$bundles = (is_null($listing)) ? array() : $this->bundles_model->findBundle($listing->id);
		
        $this->forms->insertDiv('price');
        $this->forms->insertInput('text', 'price', $values->price, array('class'=>'form-control', 'id'=>'price'));
		$this->forms->setLabel('Listing Price', 'price');
        $this->forms->endDiv();
        $this->forms->insertDiv('qty');
        $this->forms->insertInput('text', 'qty', $values->qty, array('class'=>'form-control', 'id'=>'qty'));
		$this->forms->setLabel('How Many For Sale?', 'qty');
        $this->forms->endDiv();
        $this->forms->insertDiv('attributes');
		$this->forms->insertHTML('<h4>Listing Attribtues</h4>');
        $this->forms->insertInput('button', 'attribute-add', 'Add Attribute', array('class'=>'btn btn-default attribute-add', 'type'=>'button'));
		//attribute form
		$this->forms->insertDiv('attribute-listing');
		$i = 1;
		foreach($attributes as $att)
		{
			$this->forms->insertDiv('', 'form-group attribute col-xs-12');
	        	$this->forms->insertDiv('', 'col-xs-5');
	        		$this->forms->insertInputLast('text', 'attribute-name[]', $att['name'], array('class'=>'form-control'));
	        		$this->forms->setLabel('Name', 'name');
				$this->forms->endDiv();
				$this->forms->insertDiv('', 'col-xs-5');
	        		$this->forms->insertInputLast('text', 'attribute-value[]', $att['value'], array('class'=>'form-control'));
	        		$this->forms->setLabel('Value', 'value');
				$this->forms->endDiv();
				$this->forms->insertInput('button', 'attribute-remove-'.$i, 'X', array('class'=>'btn btn-default attribute-remove', 'type'=>'button'));
			$this->forms->endDiv();
			$i++;
		}
		$this->forms->endDiv();
        $this->forms->endDiv();
		
		//product listing
        $this->forms->insertDiv('products');
		$this->forms->insertHTML('<h4>Listing Products</h4>');
        $this->forms->insertInput('button', 'product-add', 'Add Product', array('class'=>'btn btn-default product-add'));
		if(!empty($bundles))
		{
			$this->forms->insertHTML('<div id="products-listing"><table class="table table-striped">');
			foreach($bundles as $b)
			{
				
				$this->forms->insertHTML('<tr class="product-list-item">');
				$prod = $this->bundles_model->findProductBundle($b['product_id']);
				
				if(!empty($prod))
				{
					$this->forms->insertInput('hidden', 'product_listing_item[]', $b['product_id']);
					$this->forms->insertHTML('<td class="col-lg-6">'.$prod[0]['title'].'</td>');
					$this->forms->insertHTML('<td>');
					$this->forms->insertInput('text', 'product_listing_qty[]', $b['qty']);
					$this->forms->setLabel('Inventory', 'inventory');
					$this->forms->insertHTML('</td>');
					$this->forms->insertHTML('<td><a class="btn btn-default fa fa-times remove-product-listing-item"></a></td>');
					$this->forms->insertHTML('</tr>');
				}
			}
			$this->forms->insertHTML('</table></div>');
		}
        $this->forms->endDiv();
	}
	
	public function afterSave($data=array()) {
		extract($data);
		$id = (isset($id)) ? $id : 0;
		$this->form_data['type'] = 'listing';
		//$page_id = $this->savePage($id);
		$listing = [];
		$listing['price'] = $_POST['price'];
		$listing['qty'] = $_POST['qty'];
		$listing['page_id'] = $id;
		
		$l = $this->listings_model->getOne('page_id', $id);

		if(empty($l))
		{
			$listing['created_by'] = $this->user['id'];
			$listing['created_on'] = $this->listings_model->created_on();
			$listing_id = $this->listings_model->create($listing);
		}
		else
		{
			
			$listing_id = $l->id;
			$listing['modified_by'] = $this->user['id'];
			$listing['id'] = $listing_id;
			$this->listings_model->update($listing);
		}
		
		//handle attribute
		//record attributes
		$attribute_names = (empty($this->request->post('attribute-name'))) ? array() : $this->request->post('attribute-name');
		
		$attribute_values = $this->request->post('attribute-value');
		$atts = array();
		foreach($attribute_names as $v => $n) {
			$atts[$n] = $attribute_values[$v];
		}
		
		$this->atts_model->addAttributes($atts, $id, 'listing');
		
		//handle product bundle
		$bundles = (isset($_POST['product_listing_item'])) ? $_POST['product_listing_item'] : array();
		$this->bundles_model->deleteWhere(
			array('where'=>array(
					array('field'=>'listing_id', 'op'=>'=', 'value'=>$listing_id)
				)
			)
		);
		foreach($bundles as $k=>$b)
		{
			
			//build bundle
			$bundle = [];
			$bundle['listing_id'] = $listing_id;
			$bundle['product_id'] = $b;
			$bundle['qty'] = $_POST['product_listing_qty'][$k];
			
			//check for product line
			//$saved_bundle = $this->bundles_model->find('product_id=? AND listing_id', [$b, $listing_id]);
			//$prod = $this->products_model->findOne('id', $b);
			//$prod->inventory = 1;
			//print_r($saved_bundle);
			$this->bundles_model->create($bundle);
			
		}

		$this->app->redirect('/admin/marketplace/listings/edit/'.$id);
	}
	
}	

?>