<?php
namespace Model;
use Nette;

/**
 * Tabulka concerts
 */
class ConcertsManager extends Nette\Object
{

	/** 
	 * @var Model\ConcertsRepository
	 */
	public $concertsRepository;

	public function __construct(ConcertsRepository $repository)
	{
		$this->concertsRepository = $repository;
	}

	/**
	 * Get count of all items by 
	 * @return number of rows
	 */
	public function getCountAll()
	{
		return $this->concertsRepository->countAll();
	}

	/**
	 * Get count of all items by 
	 * @return number of rows
	 */
	public function getCount(array$by)
	{
		return $this->concertsRepository->findBy($by)->count();
	}

	/**
	 * Find and get item by ID
	 * @return Nette\Database\Table\IRow
	 */
	public function getByID($id)
	{
		return $this->concertsRepository->findBy(array('id' => (int)$id))->fetch();
	}

	/**
	 * Find and get item by ID
	 * @return Nette\Database\Table\IRow
	 */
	public function getBy(array $by)
	{
		return $this->concertsRepository->findBy($by)->fetch();
	}

	/**
	 * Find all items by
	 * @return Nette\Database\Table\Selection
	 */
	public function findBy(array $by)
	{
		return $this->concertsRepository->findBy($by);
	}

	/**
	 * Find all items
	 * @return Nette\Database\Table\Selection
	 */
	public function findAll()
	{
		return $this->concertsRepository->findAll();
	}

	/**
	 * Delete rows by ID
	 * @return number of deleted rows
	 */
	public function deleteById($id)
	{
		return $this->concertsRepository->findBy(array('id' => (int)$id))->delete();
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
			$result = $this->concertsRepository->findBy(array('id' => (int)$id))->update($values);
			$return = $result > 0 ? 'updated' : FALSE;
		} else {
			$result = $this->concertsRepository->insert($values);
			$return = $result ? 'inserted' : FALSE;
		}
		return $return;
	}



}
