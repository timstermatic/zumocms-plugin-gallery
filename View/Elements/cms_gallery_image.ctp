<?php echo $this->Form->create(array('type'=>'file'))?>
<?php if($this->params['action']=='cms_edit_image') { ?>
<div class="alert alert-info">Current image: (Don't select a new image unless you want to change the existing one.)</div>
<span id="row_<?php echo $this->request->data['GalleryImage']['id']?>"><?php echo $this->Image->resize(Configure::read('Gallery.path') . '/' . $this->request->data['GalleryImage']['id'] . '_' . $this->request->data['GalleryImage']['filename'] , 100, 100, array(), false)?></span>
<hr>
<?php } ?>
<?php echo $this->Form->input('GalleryImage.file', array('type'=>'file', 'label'=>false))?>
<?php echo $this->Form->input('GalleryImage.title', array('class'=>'span6'))?>
<?php echo $this->Form->input('GalleryImage.description', array('class'=>'span6'))?>
<?php echo $this->Form->submit('Upload', array('class'=>'btn'))?>
<?php echo $this->Form->end()?>
