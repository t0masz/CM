<?php

namespace Model;

use Nette,
	Nette\Mail\Message,
	Nette\Utils\Strings,
	Tracy\Debugger;


/**
* Images Manager.
*/
class ImagesManager
{

	/** 
	 * @var Model\ImagesRepository
	 */
	public $imagesRepository;

	public function __construct(ImagesRepository $repository)
	{
		$this->imagesRepository = $repository;
	}

	/**
	 * Get count of all images
	 * @return number of rows
	 */
	public function getCountAll()
	{
		return $this->imagesRepository->countAll();
	}

	/**
	 * Find and get image by ID
	 * @return Nette\Database\Table\IRow
	 */
	public function getByID($id)
	{
		return $this->imagesRepository->findBy(array('id' => (int)$id))->fetch();
	}

	/**
	 * Find all images
	 * @return Nette\Database\Table\Selection
	 */
	public function findAll()
	{
		return $this->imagesRepository->findAll();
	}

	/**
	 * Find all items by
	 * @return Nette\Database\Table\Selection
	 */
	public function findBy(array $by)
	{
		return $this->imagesRepository->findBy($by);
	}

	/**
	 * Delete rows by ID
	 * @return number of deleted rows
	 */
	public function deleteById($id)
	{
		return $this->imagesRepository->findBy(array('id' => (int)$id))->delete();
	}

	/**
	 * Save values
	 * @return string (inserted/updated) or FALSE on error
	 */
	public function save($values, $id = NULL)
	{
		if (isset($values['id']) && ($values['id'] > 0)) {
			$result = $this->getByID($values['id'])->update($values);
			$return = $result > 0 ? 'updated' : FALSE;
		} else {
			$result = $this->imagesRepository->insert((array)$values);
			$return = $result ? 'inserted' : FALSE;
		}
		return $return;
	}

}
