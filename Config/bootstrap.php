<?php
Configure::write('CmsNav.Gallery', array(
	'icon'=>'icon-picture',
	'href'=>array('cms'=>true,'plugin'=>'gallery','controller'=>'gallery','action'=>'index')
));

Configure::write('Gallery.path', 'files/gallery');
