<div>
<?php echo $this->Html->link('<i class="icon-plus-sign"></i> ' . __('Add a gallery'), array('action'=>'create'), array('class'=>'btn pull-right', 'escape'=>false));?> 
<h3><i class="icon-picture"></i> Galleries</h3>
</div>

<?php echo $this->Session->flash()?>

<table class="table table-striped">
<tr>
	<th><?php echo $this->Paginator->sort('title', 'Title')?></th>
	<th></th>
</tr>
<tbody id="sortable">
<?php foreach($galleries as $g) { ?>
<tr id="row_<?php echo $g['Gallery']['id']?>">
	<td><?php echo $g['Gallery']['title']?></td>
	<td>
		<?php echo $this->Html->link('<i class="icon-pencil"></i>', array('action'=>'edit', $g['Gallery']['id']), 
			array('class'=>'btn btn-small', 'escape'=>false));?> 

		<?php echo $this->Html->link('<i class="icon-picture"></i>', array('action'=>'images', $g['Gallery']['id']), 
			array('class'=>'btn btn-small', 'escape'=>false));?> 

		<?php echo $this->Html->link('<i class="icon-trash"></i>', array('action'=>'delete', $g['Gallery']['id']), 
			array('class'=>'btn btn-small', 'escape'=>false), 'Are you sure that you want to delete this gallery?');?> 
	</td>
</tr>
<?php } ?>
</tbody>
</table>

<?php echo $this->Html->script('/gallery/js/sort-table')?>
<script>
$("#sortable").tableDnD({onDragClass: "being_dragged",onDrop: function(table, row) {
	$.ajax({
	type: "POST",
	url: "/cms/gallery/gallery/reorder",
	data: $.tableDnD.serialize()
});
}});

</script>
