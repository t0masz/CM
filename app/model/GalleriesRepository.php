<?php
namespace Model;
use Nette;

/**
 * Tabulka galleries
 */
class GalleriesRepository extends Repository
{

	/**
	 * Vrac� top fotogalerie.
	 * @return Nette\Database\Table\Selection
	 */
	public function top($count)
	{
		return $this->getTable()->select('*')->where('top',1)->order('create DESC')->limit($count,0);
	}

	/**
	 * Vrac� naposledy p�idan� fotogalerie.
	 * @return Nette\Database\Table\Selection
	 */
	public function last($count)
	{
		return $this->getTable()->select('*')->order('create DESC')->limit($count,0);
	}
}
