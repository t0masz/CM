<?php
namespace Model;
use Nette;

/**
 * Tabulka events
 */
class EventsRepository extends Repository
{

	/**
	 * Vrac� ��dky podle filtru, nap�. array('name' => 'John').
	 * @return Nette\Database\Table\Selection
	 */
	public function findByStr($by)
	{
#		\Nette\Diagnostics\Debugger::dump($by);
		return $this->getTable()->where($by);
	}

}
