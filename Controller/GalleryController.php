<?php
class GalleryController extends GalleryAppController {

/**
 * helpers property
 */
	var $helpers = array('Gallery.Image');
/**
 * index
 */
	public function cms_index()
	{
		$this->set('galleries', $this->paginate());
	}

/**
 * create a gallery
 */
	public function cms_create()
	{
		if(!empty($this->request->data)) {
			if($this->Gallery->save($this->request->data)) {
				$this->Session->setFlash('Your gallery was created','flash_success');
				$this->redirect(array('action'=>'images', $this->Gallery->id));
			}
		}
	}


/**
 * edit a gallery
 */
	public function cms_edit($id=null)
	{
		$this->Gallery->id = $id;
		if(!$this->Gallery->exists()) {
			throw new NotFoundException('Gallery record not found');
		}
		if(!empty($this->request->data)) {
			if($this->Gallery->save($this->request->data)) {
				$this->Session->setFlash('Your gallery was updated','flash_success');
				$this->redirect(array('action'=>'images', $this->Gallery->id));
			}
		}
		$this->request->data = $this->Gallery->read(null, $id);
	}


/**
 * delete a gallery
 */
	public function cms_delete($id=null)
	{
		$this->Gallery->id = $id;
		if(!$this->Gallery->exists()) {
			throw new NotFoundException('Gallery record not found');
		}
		$this->loadModel('GalleryImage');
		$images = $this->GalleryImage->findAllByGalleryId($id);
		foreach($images as $i) {
			$this->_deleteImage($i['GalleryImage']['id']);
		}
		$this->Gallery->delete($id);
		$this->Session->setFlash('The selected gallery was deleted','flash_success');
		$this->redirect(array('action'=>'index'));
	}


/**
 * images
 */
	public function cms_images($id=null)
	{
		$this->Gallery->id = $id;
		if(!$this->Gallery->exists()) {
			throw new NotFoundException('Gallery record not found');
		}
		$this->loadModel('Gallery.GalleryImage');
		$this->set('gallery_title', $this->Gallery->field('title'));
		$this->set('images', $this->GalleryImage->findAllByGalleryId($id));
	}

/**
 * upload
 */
	public function cms_upload($id=null)
	{
		$this->Gallery->id = $id;
		if(!$this->Gallery->exists()) {
			throw new NotFoundException('Gallery record not found');
		}
		if(!empty($this->request->data)) {
			$this->loadModel('Gallery.GalleryImage');
			if(!is_dir(Configure::read('Gallery.path'))) {
				if(!mkdir(Configure::read('Gallery.path'))) {
					throw new Exception('Insufficent permissions to create gallery directory');
				}
			}
			$this->request->data['GalleryImage']['filename'] = $this->request->data['GalleryImage']['file']['name'];
			$this->request->data['GalleryImage']['gallery_id'] = $id;
			if($this->GalleryImage->save($this->request->data)) {
				if(move_uploaded_file($this->request->data['GalleryImage']['file']['tmp_name'],
						Configure::read('Gallery.path') . '/' . $this->GalleryImage->id . '_' . 
						$this->request->data['GalleryImage']['file']['name'])) {
						$this->Session->setFlash('Image uploaded', 'flash_success');
						$this->redirect(array('action'=>'images', $id));			
				} else {
					$this->GalleryImage->delete($this->GalleryImage->id);
					throw new Exception('Something went badly wrong. Unable to upload image');		
				}				
			}
		}
		$this->set('gallery_title', $this->Gallery->field('title'));
	}

/**
 * cms image edit
 */
	public function cms_edit_image($id=null) {
		$this->loadModel('Gallery.GalleryImage');
		$image = $this->GalleryImage->read(null, $id);
		$this->GalleryImage->id = $id;
		if(!$this->GalleryImage->exists()) {
			throw new NotFoundException('Gallery image not found');
		}
		$this->Gallery->id = $image['GalleryImage']['gallery_id'];
		$this->set('gallery_title', $this->Gallery->field('title'));
		if(!empty($this->request->data)) {
			if(empty($this->request->data['GalleryImage']['file']['name'])) {
				unset($this->GalleryImage->validate['file']);
			} else {
				$this->request->data['GalleryImage']['filename'] = $this->request->data['GalleryImage']['file']['name'];
			}
			if($this->GalleryImage->save($this->request->data)) {
				if(is_array(($this->GalleryImage->validate['file']))) {
				if(move_uploaded_file($this->request->data['GalleryImage']['file']['tmp_name'],
						Configure::read('Gallery.path') . '/' . $id . '_' . 
						$this->request->data['GalleryImage']['file']['name'])) {
						$this->Session->setFlash('Image uploaded', 'flash_success');
						$this->redirect(array('action'=>'images', $this->Gallery->id));			
				} else {
					$this->GalleryImage->delete($this->GalleryImage->id);
					throw new Exception('Something went badly wrong. Unable to upload image');		
				}}
				$this->Session->setFlash('Image edited', 'flash_success');
				$this->redirect(array('action'=>'images', $this->Gallery->id));			
			}
		}
		$this->request->data = $this->GalleryImage->read(null, $id);
	}

/**
 * delete an image
 */
	public function cms_delete_image($id=null) {
		$this->_deleteImage($id);
		$this->Session->setFlash('Image deleted', 'flash_success');
		$this->redirect(array('action'=>'images', $image['GalleryImage']['gallery_id']));
	}

/**
 * reusable function to purge an image from system
 */
	private function _deleteImage($id) {
		$this->loadModel('GalleryImage');
		// fetch the gallery id
		$image = $this->GalleryImage->read(null, $id);
		// delete original
		if(file_exists(Configure::read('Gallery.path') . '/' . $id . '_' . $image['GalleryImage']['filename'])) {
			@unlink(Configure::read('Gallery.path') . '/' . $id . '_' . $image['GalleryImage']['filename']);
		} 
		// delete cache
		$cached = glob(Configure::read('Gallery.path') . '/cache/*x*_*' . $id . '_*');
		foreach($cached as $c) {
			@unlink($c);
		}	
		// remove database reference
		$this->GalleryImage->delete($id);
		// send back to gallery
	}

/**
 * cms reorder gallery
 **/
 	public function cms_reorder() {
		$this->autoRender = false;
		foreach($this->request->data['sortable'] as $k=>$v) {
			$this->Gallery->updateAll(
				array('Gallery.position' => $k),
				array('Gallery.id' => str_replace('row_','',$v))
			);
		}
	}

/**
 * reorder images
 */
	public function cms_reorder_images() {
		$this->autoRender = false;
		$this->loadModel('GalleryImage');
		if(!empty($this->request->data)) {
			foreach($this->request->data as $k=>$v) {
				echo "SET $v to $k\n";
				$this->GalleryImage->id = (int)str_replace('row_', '', $v);
				$this->GalleryImage->saveField('position', $k);
			}
		}
	}




}
