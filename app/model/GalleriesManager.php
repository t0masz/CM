<?php
namespace Model;
use Nette;

/**
 * Tabulka galleries
 */
class GalleriesManager
{

	/** 
	 * @var Model\GalleriesRepository
	 */
	public $galleriesRepository;

	public function __construct(GalleriesRepository $repository)
	{
		$this->galleriesRepository = $repository;
	}

	/**
	 * Get count of all items by 
	 * @return number of rows
	 */
	public function getCount(array $by)
	{
		return $this->galleriesRepository->count($by);
	}

	/**
	 * Get count of all items
	 * @return number of rows
	 */
	public function getCountAll()
	{
		return $this->galleriesRepository->countAll();
	}

	/**
	 * Find and get item by ID
	 * @return Nette\Database\Table\IRow
	 */
	public function getByID($id)
	{
		return $this->galleriesRepository->findBy(array('id' => (int)$id))->fetch();
	}

	/**
	 * Find and get item by ID
	 * @return Nette\Database\Table\IRow
	 */
	public function getBy(array $by)
	{
		return $this->galleriesRepository->findBy($by)->fetch();
	}

	/**
	 * Find all items by
	 * @return Nette\Database\Table\Selection
	 */
	public function findBy(array $by)
	{
		return $this->galleriesRepository->findBy($by);
	}

	/**
	 * Find all items
	 * @return Nette\Database\Table\Selection
	 */
	public function findAll()
	{
		return $this->galleriesRepository->findAll();
	}

	/**
	 * Delete rows by ID
	 * @return number of deleted rows
	 */
	public function deleteById($id)
	{
		return $this->galleriesRepository->findBy(array('id' => (int)$id))->delete();
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
			$values['order'] = $values['order'] == 0 ? $id : $values['order'];
			$result = $this->galleriesRepository->findBy(array('id' => (int)$id))->update($values);
			$return = $result > 0 ? 'updated' : FALSE;
		} else {
			$values['create'] = date('Y-m-d H:i:s');
			$result = $this->galleriesRepository->insert($values);
			$result = $this->galleriesRepository->findBy(array('id' => (int)$result->id))->update(array('order' => $result->id));
			$return = $result ? 'inserted' : FALSE;
		}
		return $return;
	}



}
