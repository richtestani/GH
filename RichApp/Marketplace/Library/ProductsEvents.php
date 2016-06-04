<?php

namespace Marketplace\Library;

trait ProductsEvents {
	
	public function afterForm($data=array())
	{

		extract($data);
		$product = (array_key_exists('pagedata', $data)) ? $data['pagedata']->product : array();
		
        $values = new \stdClass();
        $properties = $this->products_model->getProperties();
		$attributes = (!empty($product)) ? $this->atts_model->getItemAttributes('product', $pagedata->id) : array();
		
		if (!empty($product)) {
			$images = $this->img_links->getImageByLink($pagedata->id, 'product');
		}

        foreach($properties as $prop)
        {
            $values->{$prop} =(!empty($product)) ?  $product->{$prop} : '';
        }
		
		$ser = $this->series_model->getAll();
		
		
		$series = array(
			array('name'=>'Not Part of Series', 'value' => 0)
		);
		
		foreach($ser as $k => $v)
		{
			$series[] = array('name'=>$v['series'], 'value'=>$v['id']);
		}
		
		
        $this->forms->insertDiv('data');
        $this->forms->insertInputLast('text', 'sku', $values->sku, array('class'=>'form-control'));
        $this->forms->setLabel('SKU', 'sku');
        $this->forms->insertInput('select', 'series_id', $values->series_id, array('class'=>'form-control'), $series);
        $this->forms->setLabel('Is Part of Series', 'series_id');
        $this->forms->insertInput('text', 'num_in_series', $values->num_in_series, array('class'=>'form-control'), $series);
        $this->forms->setLabel('Number in series', 'num_in_series');
        $this->forms->endDiv();
		
		//images
		$images_json = [];
		$this->forms->insertDiv('product-images');
		$this->forms->insertDiv('image-link');
		$this->forms->insertHTML('<div class="form-group"><h3>Product Images</h3><a class="btn btn-default add-product-image" href="#">Add Product Image</a></div>');
		if(!empty($images))
		{
			$this->forms->insertDiv('product_images_show');
			foreach($images as $img)
			{
				$src = $img['public_path'].'/thumbnail/'.$img['filename'];
				$this->forms->insertHTML('<div class="product-image">');
				$this->forms->insertHTML('<img src="'.$src.'">');
				$this->forms->insertHTML('<a href="#" class="remove-product-image btn btn-danger fa fa-times"></a>');
				$this->forms->insertInput('hidden', 'product-image[]', $img['uid']);
				$this->forms->insertHTML('</div>');
			}
			$this->forms->endDiv();
		}
		else
		{
			$this->forms->insertHTML('<div id="product_images_show"></div>');
		}
		
		//attributes
        $this->forms->insertDiv('attributes');
		$this->forms->insertHTML('<h4>Listing Attribtues</h4>');
  
		//attribute form
        $this->forms->insertInput('button', 'attribute-add', 'Add Attribute', array('class'=>'btn btn-default attribute-add', 'type'=>'button'));
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
		
		
		//manufacturer
		$mans = $this->man_model->getAll(array('select'=>'manufacturer as name, id as value', 'limit'=>999));
		$mans = array_merge([array('name'=>'None/unknown', 'value'=>0)], $mans);
		$this->forms->insertDiv('manufacturers', 'clearfix');
		$this->forms->insertHTML('<h4 class="clearfix">Manufacturers</h4>');
        //$this->forms->setLabel('Manufacturer', 'manufacturer');
        $this->forms->insertInput('select', 'manufacturer_id', $values->manufacturer_id, array('class'=>'form-control'), $mans);
		$this->forms->endDiv();
		
		$this->forms->endDiv();
		$this->forms->endDiv();
	}
	
	public function afterSave($data=array())
	{
		$id = $data['id'];
		$product = $this->products_model->getOne('page_id', $id);
		
		$properties = $this->products_model->getProperties();

		//record product
		if(!$this->products_model->exists(['field'=>'page_id', 'value'=>$id]))
		{
			$new = [];
			foreach ($properties as $p) {
				$new[$p] = (empty($this->request->post($p))) ? 0 : $this->request->post($p);
			}
			$new['page_id'] = $id;
			$this->products_model->create($new);
		}
		else
		{
			$update = [];
			foreach ($properties as $p) {
				$update[$p] = (empty($this->request->post($p))) ? 0 : $this->request->post($p);
			}
			$update['id'] = $product->id;
			$update['page_id'] = $product->page_id;
			$this->products_model->update($update);
			$product->id;
		}
		
		//record attributes
		$attribute_names = (empty($this->request->post('attribute-name'))) ? array() : $this->request->post('attribute-name');
		$attribute_values = $this->request->post('attribute-value');
		$atts = array();
		foreach($attribute_names as $v => $n) {
			$atts[$n] = $attribute_values[$v];
		}
		
		$this->atts_model->addAttributes($atts, $id, 'product');
		
		foreach($_POST as $k => $v)
		{
			if(in_array($k, $properties))
			{
				$product[$k] = $v;
			}
		}
		
		//handle images
		$images = $this->request->post('product-image');
		if(!empty($images)) {
			$this->img_links->insertLinks($images, $id, 'product');
		}
		
		
		
		
	}
	
}	

?>