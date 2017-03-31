<?php
namespace Model;
use Nette;

/**
 * Tabulka galleries
 */
class GalleriesRepository extends Repository
{

	/**
	 * Vrací top fotogalerie.
	 * @return Nette\Database\Table\Selection
	 */
	public function top($count)
	{
		return $this->getTable()->select('*')->where('top',1)->order('create DESC')->limit($count,0);
	}

	/**
	 * Vrací naposledy pøidané fotogalerie.
	 * @return Nette\Database\Table\Selection
	 */
	public function last($count)
	{
		return $this->getTable()->select('*')->order('create DESC')->limit($count,0);
	}
}
