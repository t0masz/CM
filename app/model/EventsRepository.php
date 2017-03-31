<?php
namespace Model;
use Nette;

/**
 * Tabulka events
 */
class EventsRepository extends Repository
{

	/**
	 * Vrací øádky podle filtru, napø. array('name' => 'John').
	 * @return Nette\Database\Table\Selection
	 */
	public function findByStr($by)
	{
#		\Nette\Diagnostics\Debugger::dump($by);
		return $this->getTable()->where($by);
	}

}
