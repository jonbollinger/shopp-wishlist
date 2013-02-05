<ul><?php if ( shopp('wishlist','has-wishlist') ) :
	while ( shopp('wishlist','get-wishlist') )
	{
	?>
		<li><a href="<?php shopp('product','link'); ?>"><?php shopp('product','name'); ?></a>
			<a href="<?php echo shoppwishlist_url('remove'); ?>"><?php _e('Remove','shoppwishlist'); ?></a></li>
	<?php
	}
else : // no items on the wishlist
?>
	<li><?php _e('There are no items on this wishlist.','shoppwishlist') ?></li>
<?php endif; ?></ul>