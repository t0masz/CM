<?php
namespace Model;
use Nette;

/**
 * Tabulka articles
 */
class ArticlesManager extends Nette\Object
{

	/** 
	 * @var Model\ArticlesRepository
	 */
	public $articlesRepository;

	/** 
	 * @var Model\RubricsRepository
	 */
	public $rubricsRepository;

	public function __construct(ArticlesRepository $repository, RubricsRepository $rubricsRepository)
	{
		$this->articlesRepository = $repository;
		$this->rubricsRepository = $rubricsRepository;
	}

	/**
	 * Get count of all items by 
	 * @return number of rows
	 */
	public function getCount(array $by)
	{
		return $this->articlesRepository->count($by);
	}

	/**
	 * Get count of all items
	 * @return number of rows
	 */
	public function getCountAll()
	{
		return $this->articlesRepository->countAll();
	}

	/**
	 * Get count of all rubrics
	 * @return number of rows
	 */
	public function getCountAllRubrics()
	{
		return $this->rubricsRepository->countAll();
	}

	/**
	 * Find and get item by ID
	 * @return Nette\Database\Table\IRow
	 */
	public function getByID($id)
	{
		return $this->articlesRepository->findBy(array('id' => (int)$id))->fetch();
	}

	/**
	 * Find and get item by ID
	 * @return Nette\Database\Table\IRow
	 */
	public function getBy(array $by)
	{
		return $this->articlesRepository->findBy($by)->fetch();
	}

	/**
	 * Find all items by
	 * @return Nette\Database\Table\Selection
	 */
	public function findBy(array $by)
	{
		return $this->articlesRepository->findBy($by);
	}

	/**
	 * Find all rubrics items by
	 * @return Nette\Database\Table\Selection
	 */
	public function findRubricsBy(array $by)
	{
		return $this->rubricsRepository->findBy($by);
	}

	/**
	 * Find all items
	 * @return Nette\Database\Table\Selection
	 */
	public function findAll()
	{
		return $this->articlesRepository->findAll();
	}

	/**
	 * Find all rubrics
	 * @return Nette\Database\Table\Selection
	 */
	public function findAllRubrics()
	{
		return $this->rubricsRepository->findAll();
	}

	/**
	 * Delete rows by ID
	 * @return number of deleted rows
	 */
	public function deleteById($id)
	{
		return $this->articlesRepository->findBy(array('id' => (int)$id))->delete();
	}

	/**
	 * Delete rubric rows by ID
	 * @return number of deleted rows
	 */
	public function deleteRubricById($id)
	{
		return $this->rubricsRepository->findBy(array('id' => (int)$id))->delete();
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
			$result = $this->articlesRepository->findBy(array('id' => (int)$id))->update($values);
			$return = $result > 0 ? 'updated' : FALSE;
		} else {
			$result = $this->articlesRepository->insert($values);
			$return = $result ? 'inserted' : FALSE;
		}
		return $return;
	}

	/**
	 * Save rubric values
	 * @return string (insert/update) or FALSE on error
	 */
	public function saveRubric($values)
	{
		if (isset($values['id']) && ($values['id'] > 0)) {
			$id = $values['id'];
			unset($values['id']);
			$result = $this->rubricsRepository->findBy(array('id' => (int)$id))->update($values);
			$return = $result > 0 ? 'updated' : FALSE;
		} else {
			$result = $this->rubricsRepository->insert($values);
			$return = $result ? 'inserted' : FALSE;
		}
		return $return;
	}



}
