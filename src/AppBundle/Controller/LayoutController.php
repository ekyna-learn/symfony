<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LayoutController
 * @package AppBundle\Controller
 */
class LayoutController extends Controller
{
    /**
     * Navbar action.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function navbarAction(Request $request)
    {
        $items = [
            [
                'caption' => 'Accueil',
                'route'   => 'app_home',
            ],
            [
                'caption' => 'Catalogue',
                'route'   => 'app_shop_index',
                'match'   => '~^app_shop_(index|category|product)~',
            ],
            [
                'caption' => 'Panier',
                'route'   => 'app_shop_cart',
            ],
            [
                'caption' => 'Contact',
                'route'   => 'app_contact',
            ],
        ];

        $activeRoute = $request->attributes->get('_route');
        foreach ($items as &$item) {
            $item['active'] = isset($item['match'])
                ? (bool)preg_match($item['match'], $activeRoute)
                : $activeRoute === $item['route'];
        }

        return $this->render('AppBundle:Layout:navbar.html.twig', [
            'home_route' => 'app_home',
            'items'      => $items,
        ]);
    }
}
