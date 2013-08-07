<?php
class GalleryController extends GalleryAppController {

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
		$this->Gallery->delete($id);
		// @TODO - ditch all the images
		$this->Session->setFlash('The selected gallery was deleted','flash_success');
		$this->redirect(array('action'=>'index'));
	}


/**
 * images
 */
	public function cms_images()
	{
		// code...
	}


}
