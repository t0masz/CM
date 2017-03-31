<?php

namespace Model;

use Nette,
	Nette\Utils\Strings;


/**
* Setup management.
*/
class SetupManager extends Nette\Object
{

	public $paging;
	public $mail;
	public $items;
	public $shop;
	public $postage;
	public $admin;

	public function __construct(array $config)
	{
		$this->paging = $config['paging'];
		$this->mail = $config['mail'];
		$this->items = $config['items'];
		$this->shop = $config['shop'];
		$this->postage = $config['postage'];
		$this->admin = $config['admin'];
	}

}
