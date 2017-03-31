<?php

namespace App;

use Nette,
	Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\SimpleRouter;


/**
 * Router factory.
 */
class RouterFactory
{

	/**
	 * @return \Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList();
		$router[] = $adminRouter = new RouteList('Admin');
		$adminRouter[] = new Route('admin/<presenter>/<action>[/<id>]', array(
			'presenter' => 'Homepage',
			'action' => 'default',
		));

		$router[] = $frontRouter = new RouteList('Front');
		$frontRouter[] = new Route('/[strana-<vp-page>]', array(
			'presenter' => 'Articles',
			'action' => 'showHome',
		));
		$frontRouter[] = new Route('/clanek/[<id>.html]', array(
			'presenter' => 'Articles',
			'action' => 'showArticle',
		));
		$frontRouter[] = new Route('/rubrika/[<id>/][strana-<vp-page>]', array(
			'presenter' => 'Articles',
			'action' => 'showRubric',
		));
		$frontRouter[] = new Route('/<action galerie|video>/[strana-<vp-page>]', array(
			'presenter' => 'Galleries',
			'action' => array(
				Route::FILTER_TABLE => array(
					'galerie' => 'showGalleries',
					'video' => 'showVideos',
				),
			),
		));
		$frontRouter[] = new Route('/koncerty/[strana-<vp-page>]', array(
			'presenter' => 'Concerts',
			'action' => 'show',
		));
		$frontRouter[] = new Route('/<action kontakt|objednavka>/', array(
			'presenter' => 'Forms',
			'action' => array(
				Route::FILTER_TABLE => array(
					'kontakt' => 'showContact',
					'objednavka' => 'showShop',
				),
			),
		));
		$frontRouter[] = new Route('/<id>/', array(
			'presenter' => 'Page',
			'action' => 'showPage',
		));
		return $router;
	}

}
