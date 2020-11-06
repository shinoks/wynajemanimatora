<?php
namespace App\Controller\Front;

use App\Entity\Registry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    public function startPage(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Registry::class)->findBy(
            [
                'enabled'=>1,
            ],
            [
                $request->query->get('sort','id')=>$request->query->get('direction','desc'),
            ]
        );
        $registry = $paginator->paginate($query,$request->query->getInt('page', 1),5);

        return $this->render('front/index.html.twig',
            [
                'registry' => $registry,
            ]
        );
    }
}