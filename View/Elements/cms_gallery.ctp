<?php echo $this->Form->create()?>
<?php echo $this->Form->input('Gallery.title', array('class'=>'span6'))?>
<?php echo $this->Form->input('Gallery.description', array('class'=>'span6'))?>
<?php echo $this->Form->submit('Save changes', array('class'=>'btn'))?>
<?php echo $this->Form->end()?>
