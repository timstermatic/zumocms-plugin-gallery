<?php   
/**
 * Gallery Model
 */
class GalleryImage extends GalleryAppModel {
/**
 * order property
 */
	var $order = array('GalleryImage.position'=>'asc');
/**
 * validate property
 */
	public $validate = array(
		'title' => array(
			'rule' => 'notEmpty',
			'message' => 'Please enter a title for your image'
		),
		'file' => array(
			'rule'=>array('allowed_mime_types'),
			'message'=>'Please enter a valid image file for upload'
		)
	);

	public function allowed_mime_types()
	{
	
		if($this->data[$this->alias]['file']['name']=='') {
			return false;
		} 
		
		$allowed_mime_types = array(
			'image/jpeg',
			'image/jpg',
			'image/png',
			'image/gif'
		);
	

		if(!in_array(mime_content_type($this->data[$this->alias]['file']['tmp_name']), $allowed_mime_types)) {
			return false;
		} else {
			return true;
		}

	}



}
