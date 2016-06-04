Marketplace = {
	addAttribute: function()
	{
		
		var html = '';
		html = '<div class="form-group attribute col-xs-12">';
		html = html + '<div class="col-xs-5">';
		html = html + '<label>Name</label><input type="text" name="attribute-name[]" value="" class="form-control" />';
		html = html + '</div>';
		html = html + '<div class="col-xs-5">';
		html = html + '<label>Value</label><input type="text" name="attribute-value[]" value="" class="form-control" />';
		html = html + '</div>';
		html = html + '<button id="attribute-remove" class="btn btn-danger" role="X" "="">X</button>';
		html = html + '</div>';
		console.log(html)
		$('#attribute-listing').append(html);
		
		return false;
	},
	
	removeAttribute: function(el)
	{
		$(el).closest('.attribute').slideUp(250, function()
		{
			$(this).remove();
		});
		return false;
	},
	
	addProductImage: function()
	{
        if($('#overlay').length == 0)
            $('<div id="overlay" />').insertBefore('#upload-form');
        	$("#overlay").fadeIn().css('display', 'block');
        $.ajax({
             url: '/admin/images/imageGrid/product',
             success: function(html)
             {
                 $('#overlay').html(html);
             }
          });
	},
	
	showProducts: function()
	{
        if($('#overlay').length == 0)
            $('<div id="overlay" />').insertBefore('#upload-form');
        	$("#overlay").fadeIn().css('display', 'block');
        $.ajax({
             url: '/admin/marketplace/products/showProductList',
             success: function(html)
             {
				 console.log(html);
                 $('#overlay').html(html);
             }
          });
		  
		  return false;
	},
	
    showImages: function()
    {
        if($('#overlay').length == 0)
              $('<div id="overlay" />').insertBefore('#upload-form');
        
        $("#overlay").fadeIn().css('display', 'table');
        $.ajax({
             url: '/admin/images/imageGrid/default',
             success: function(html)
             {
                 $('#overlay').html(html);
             }
          });
    }
};

$(document).ready(function(){
	
	/*
	* Attributes
	*/
	//append attribute wrapper block
	if($('#attribute-listing').length == 0)
	{
		$('#attributes').append('<div id="attribute-listing"></div>');
	}
	//add new attribute form
	$('#attribute-add').click(function()
	{
		return Marketplace.addAttribute();
	});
	//remove attribute form
	$('#attributes').on('click', 'button#attribute-remove', function(){
		return Marketplace.removeAttribute(this);
	});
	
	$('.datepicker').datepicker({
		dateFormat: "yy-mm-dd"
	});
	
	//seller lookup
	$('#usersearch').keyup(function()
	{
		var str = $(this).val();
		
		if (str.length >= 3) {
			$.ajax({
				url: '/admin/marketplace/sellers/search/json',
				data: {"query":str},
				type: "post",
				success: function(r) {
					i = 0;
					result = $.parseJSON(r);
					
					html = '<ul class="list-unstyled list-striped">';
					for (var prop in result) {
					    if (result.hasOwnProperty(prop)) {
							name = (result[prop].name == '') ? result[prop].username : result[prop].name;
					        html += '<li><a href="javascript:;" class="useritem" data-user-id="'+result[prop].id+'">'+name+'</a></li>';
					    }
					}
					html += '</ul>';
					$('#foundusers').html(html);
				}
			})
		}
	});

	$('#foundusers').on('click', '.useritem', function()
	{
		
		user = $(this).data('user-id');
		$.ajax({
			url:"/admin/marketplace/sellers/saveUser",
			data: {"user":user},
			type:"post",
			success: function() {
				$('#selecteduser').html('<p>User now seller</p>');
			}
		})
	})
	
	/*
	* Product Images
	*/
	$('.add-featured-image').click(function()
	{
		
        if($('#overlay').length == 0)
              $('<div id="overlay" />').insertBefore('#upload-form');

        $.ajax({
             url: '/admin/images/imageGrid/featured',
             success: function(html)
             {
                 $('#overlay').html(html).fadeIn().css('display', 'block');
             }
          });
	});
	
	$('body').on('click', '.set-featured-image', function() {
	    uid = $(this).data('uid');
		imgsrc = '<div class="featured-image"><img src="'+$(this).data('image-src')+'" /><a href="#" class="remove-product-image btn btn-danger fa fa-times"></a><input type="hidden" name="featured-image" value="'+uid+'" /></div>';
		$('#featured_images_show').prepend(imgsrc);
	    return false;
		
	});
	
	
	$('body').on('click', '.remove-featured-image', function() {
		$(this).closest('.featured-image').remove();
	});
	
	//load image
	$('#product-images').on('click', '.add-product-image', function(){
		Marketplace.addProductImage();
	});
	//add new product to listing
	$('#product-add').click(function()
	{
		return Marketplace.showProducts();
	});
	$('body').on('click', '.add-product', function(){
		
		var available = $(this).data('available');
		$(this).addClass('used');
		html = '<tr class="product-list-item">';
		html = html + '<input type="hidden" name="product_listing_item[]" value="'+$(this).data('product-id')+'" />';
		html = html + '<td class="col-lg-6">'+$(this).data('product-title')+'</td>';
		html = html + '<td><label>Inventory</label><input type="text" name="product_listing_qty[]" value="1"></td>';
		html = html + '<td><a class="btn btn-default fa fa-times remove-product-listing-item"></a></td>';
		html = html + '</tr>';
		
		$('#products-listing table').append(html);
	});
	
	$('#products').on('click', '.remove-product-listing-item', function(){
		$(this).closest('tr').remove();
	});
	//include image
	  $('body').on('click', 'a.set-product-image', function()
	{
	    uid = $(this).data('uid');
		imgsrc = '<div class="product-image"><img src="'+$(this).data('image-src')+'" /><a href="#" class="remove-product-image btn btn-danger fa fa-times"></a><input type="hidden" name="product-image[]" value="'+uid+'" /></div>';
		$('#product_images_show').prepend(imgsrc);
	    return false;
	});
	//append product listing block
	if($('#products-listing').length == 0)
	{
		$('#products').append('<div id="products-listing"><table class="table table-striped"></table></div>');
	}
	//remove product image
	$('#product_images_show').on('click', '.remove-product-image', function(){
		$(this).closest('.product-image').remove();
		return false;
	});
	//remove overlay
	$('body').on('click', '#overlay', function(){
		$('#overlay').fadeOut(300, function()
		{
			$(this).remove();
		});
	});
	
});