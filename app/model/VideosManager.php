<?php
namespace Model;
use Nette;

/**
 * Tabulka videos
 */
class VideosManager extends Nette\Object
{

	/** 
	 * @var Model\VideosRepository
	 */
	public $videosRepository;

	public function __construct(VideosRepository $repository)
	{
		$this->videosRepository = $repository;
	}

	/**
	 * Get count of all items by 
	 * @return number of rows
	 */
	public function getCount(array $by)
	{
		return $this->videosRepository->count($by);
	}

	/**
	 * Get count of all items 
	 * @return number of rows
	 */
	public function getCountAll()
	{
		return $this->videosRepository->countAll();
	}

	/**
	 * Find and get item by ID
	 * @return Nette\Database\Table\IRow
	 */
	public function getByID($id)
	{
		return $this->videosRepository->findBy(array('id' => (int)$id))->fetch();
	}

	/**
	 * Find and get item by ID
	 * @return Nette\Database\Table\IRow
	 */
	public function getBy(array $by)
	{
		return $this->videosRepository->findBy($by)->fetch();
	}

	/**
	 * Find all items by
	 * @return Nette\Database\Table\Selection
	 */
	public function findBy(array $by)
	{
		return $this->videosRepository->findBy($by);
	}

	/**
	 * Find all items
	 * @return Nette\Database\Table\Selection
	 */
	public function findAll()
	{
		return $this->videosRepository->findAll();
	}

	/**
	 * Delete rows by ID
	 * @return number of deleted rows
	 */
	public function deleteById($id)
	{
		return $this->videosRepository->findBy(array('id' => (int)$id))->delete();
	}

	/**
	 * Save values
	 * @return string (inserted/updated) or FALSE on error
	 */
	public function save($values)
	{
		if (isset($values['id']) && ($values['id'] > 0)) {
			$id = $values['id'];
			unset($values['id']);
			$result = $this->videosRepository->findBy(array('id' => (int)$id))->update($values);
			$return = $result > 0 ? 'updated' : FALSE;
		} else {
			$result = $this->videosRepository->insert($values);
			$return = $result ? 'inserted' : FALSE;
		}
		return $return;
	}



}
