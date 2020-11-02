<?php
namespace Model;

use Nette,
	Nette\Mail,
	Nette\Mail\Message,
	Nette\Mail\IMailer,
	Nette\Utils\Strings,
	Latte,
	Tracy;

/**
 * Tabulka shop
 */
class ShopManager
{

	/** 
	 * @var ShopRepository
	 */
	public $shopRepository;

	/** 
	 * @var OrdersRepository
	 */
	public $ordersRepository;

	/** @var SetupManager */
	protected $config;

	/** @var IMailer */
	private $mailer;

	public function __construct(ShopRepository $repository, OrdersRepository $ordersRepository, SetupManager $setup, IMailer $mailer)
	{
		$this->shopRepository = $repository;
		$this->ordersRepository = $ordersRepository;
		$this->config = $setup;
		$this->mailer = $mailer;
	}

	/**
	 * Get count of all items by 
	 * @return number of rows
	 */
	public function getCount(array $by)
	{
		return $this->shopRepository->count($by);
	}

	/**
	 * Get count of all items
	 * @return number of rows
	 */
	public function getCountAll()
	{
		return $this->shopRepository->countAll();
	}

	/**
	 * Get count of all orders
	 * @return number of rows
	 */
	public function getCountAllOrders()
	{
		return $this->ordersRepository->countAll();
	}

	/**
	 * Find and get item by ID
	 * @return Nette\Database\Table\IRow
	 */
	public function getByID($id)
	{
		return $this->shopRepository->findBy(array('id' => (int)$id))->fetch();
	}

	/**
	 * Find and get item by ID
	 * @return Nette\Database\Table\IRow
	 */
	public function getBy(array $by)
	{
		return $this->shopRepository->findBy($by)->fetch();
	}

	/**
	 * Find all items by
	 * @return Nette\Database\Table\Selection
	 */
	public function findBy(array $by)
	{
		return $this->shopRepository->findBy($by);
	}

	/**
	 * Find order by
	 * @return Nette\Database\Table\Selection
	 */
	public function findOrderBy(array $by)
	{
		return $this->ordersRepository->findBy($by);
	}

	/**
	 * Find all items
	 * @return Nette\Database\Table\Selection
	 */
	public function findAll()
	{
		return $this->shopRepository->findAll();
	}

	/**
	 * Find all orders
	 * @return Nette\Database\Table\Selection
	 */
	public function findAllOrders()
	{
		return $this->ordersRepository->findAll();
	}

	/**
	 * Delete rows by ID
	 * @return number of deleted rows
	 */
	public function deleteById($id)
	{
		return $this->shopRepository->findBy(array('id' => (int)$id))->delete();
	}

	/**
	 * Delete order by ID
	 * @return number of deleted rows
	 */
	public function deleteOrderById($id)
	{
		return $this->ordersRepository->findBy(array('id' => (int)$id))->delete();
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
			$result = $this->shopRepository->findBy(array('id' => (int)$id))->update($values);
			$return = $result > 0 ? 'updated' : FALSE;
		} else {
			$result = $this->shopRepository->insert($values);
			$return = $result ? 'inserted' : FALSE;
		}
		return $return;
	}

	/**
	 * Save order
	 * @return bool
	 */
	public function saveOrder($values)
	{
		$return = FALSE;
		$objednavka = ""; $objednano = ""; $cena = 0; $potvrzeni = ""; $return = FALSE;
		$zbozi = $this->shopRepository->findAll()->order('title');
		foreach ($zbozi as $id => $row) {
			if ($values['item'.$row->id]>0) {
				$objednano.= $row->title . "; počet kusů: " . $values['item'.$row->id] . "\n";
				$cena+= $row->price*$values['item'.$row->id];
			}
		}
		$prevod = (int)$this->config->postage['transfer']+(int)$this->config->postage['packing'];
		$dobirka = (int)$this->config->postage['cash_on_delivery']+(int)$this->config->postage['packing'];
		$options = array(
			'prevod' => "Převod (poštovné a balné {$prevod} Kč)",
			'dobirka' => "Dobírka (poštovné a balné {$dobirka} Kč)",
			'osobne'  => 'Osobně',
		);
		$order['sent'] = date('Y-m-d H:i:s');
		$order['name'] = $values['jmeno'] . " " . $values['prijmeni'];
		$order['mail'] = $values['email'];
		$order['address'] = $values['adresa'] . ", " . $values['psc'] . " " . $values['obec'];
		$order['contact'] = $values['telefon'];
		$order['items'] = $objednano;
		$order['price'] = $cena;
		$order['delivery'] = $options[$values['doruceni']];
		$order['state'] = 'wait';
		$order['note'] = $values['poznamka'];
		$this->ordersRepository->insert($order);

		# send e-mails
		$latte = new Latte\Engine;
		$params = array(
			'objednano' => $objednano,
			'cena' => $cena,
			'doruceni' => $options[$values['doruceni']],
			'values' => $values
		);
		# order
		$message = new Message;
		$message->setFrom($values['email'], "{$values['jmeno']} {$values['prijmeni']}")
			->addTo($this->config->shop['from_mail'],$this->config->shop['from'])
			->setHtmlBody($latte->renderToString('app/FrontModule/templates/Emails/order.latte', $params));
		try {
			$this->mailer->send($message);
			$return = TRUE;
		} catch(Mail\SendException $e) {
			Tracy\Debugger::log('Chyba pri odeslani emailu s objednavkou Bukovince');
		}
		# confirm
		$message = new Message;
		$message->setFrom($this->config->shop['from_mail'],$this->config->shop['from'])
			->addTo($values['email'], "{$values['jmeno']} {$values['prijmeni']}")
			->setHtmlBody($latte->renderToString('app/FrontModule/templates/Emails/confirm.latte', $params));
		try {
			$this->mailer->send($message);
			$return = TRUE;
		} catch(Mail\SendException $e) {
			Tracy\Debugger::log('Chyba pri odeslani emailu s potvrzenim uzivateli');
		}
		return $return;
	}

	/**
	 * Save values
	 * @return bool
	 */
	public function saveOrderState($values)
	{
		if ($this->ordersRepository->findBy(array('id'=>$values->id))->update((array)$values)) {
			return true;
		} else {
			return false;
		}
	}


}
