<?php
namespace Model;
use Nette;

/**
 * Tabulka pages
 */
class PagesManager
{

	/** 
	 * @var Model\PagesRepository
	 */
	public $pagesRepository;

	public function __construct(PagesRepository $repository)
	{
		$this->pagesRepository = $repository;
	}

	/**
	 * Get count of all items by 
	 * @return number of rows
	 */
	public function getCount(array $by)
	{
		return $this->pagesRepository->count($by);
	}

	/**
	 * Get count of all items
	 * @return number of rows
	 */
	public function getCountAll()
	{
		return $this->pagesRepository->countAll();
	}

	/**
	 * Find and get item by ID
	 * @return Nette\Database\Table\IRow
	 */
	public function getByID($id)
	{
		return $this->pagesRepository->findBy(array('id' => (int)$id))->fetch();
	}

	/**
	 * Find and get item by ID
	 * @return Nette\Database\Table\IRow
	 */
	public function getBy(array $by)
	{
		return $this->pagesRepository->findBy($by)->fetch();
	}

	/**
	 * Find all items by
	 * @return Nette\Database\Table\Selection
	 */
	public function findBy(array $by)
	{
		return $this->pagesRepository->findBy($by);
	}

	/**
	 * Find all items
	 * @return Nette\Database\Table\Selection
	 */
	public function findAll()
	{
		return $this->pagesRepository->findAll();
	}

	/**
	 * Delete rows by ID
	 * @return number of deleted rows
	 */
	public function deleteById($id)
	{
		return $this->pagesRepository->findBy(array('id' => (int)$id))->delete();
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
			$result = $this->pagesRepository->findBy(array('id' => (int)$id))->update($values);
			$return = $result > 0 ? 'updated' : FALSE;
		} else {
			$result = $this->pagesRepository->insert($values);
			$return = $result ? 'inserted' : FALSE;
		}
		return $return;
	}

}
