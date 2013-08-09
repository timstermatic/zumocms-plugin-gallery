<div>
<?php echo $this->Html->link('<i class="icon-upload"></i> ' . __('Upload a new image'), array('action'=>'upload', $this->params['pass'][0]), array('class'=>'btn pull-right', 'escape'=>false));?> 
<h3><i class="icon-picture"></i> Gallery images <small><?php echo $gallery_title?></small></h3>
</div>
<?php echo $this->Session->flash()?>
<div class="alert alert-info">You can drag and drop images to reorder their position</div>
<ul class="unstyled inline sortable">
<?php foreach($images as $i) { ?>
<li class="thumbnail" id="row_<?php echo $i['GalleryImage']['id']?>"><?php echo $this->Image->resize(Configure::read('Gallery.path') . '/' . $i['GalleryImage']['id'] . '_' . $i['GalleryImage']['filename'] , 100, 100, array(), false)?>
<p>
<br>
<?php echo $this->Html->link('<i class="icon-pencil"></i>', array('action'=>'edit_image', $i['GalleryImage']['id']), array('escape'=>false, 'class'=>'btn btn-small'))?> 
<?php echo $this->Html->link('<i class="icon-trash"></i>', array('action'=>'delete_image', $i['GalleryImage']['id']), array('escape'=>false, 'class'=>'btn btn-small'),'Are you sure that you want to delete this image?')?>
</p>

</li>
<?php } ?>
</ul>

<?php echo $this->Html->script('/gallery/js/jquery.sortable.min')?>

<script>
$('.sortable').sortable().bind('sortupdate', function() {
	var items = {}
	$(this).find('li').each( function(key) {
			items[key] = $(this).attr('id')
	})
	$.post('/cms/gallery/gallery/reorder_images', items );
});
</script>
