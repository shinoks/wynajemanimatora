<?php
namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Entity\RegistryCategory;
use App\Form\RegistryCategoriesType;
use Doctrine\DBAL\DBALException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\AdminType;

class CategoriesController extends AbstractController
{
    /**
     * @var Session
     */
    private Session $session;

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function categoriesPage(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(RegistryCategory::class)->findBy([],[
            $request->query->get('sort','id')=>$request->query->get('direction','asc')
        ]);
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );


        return $this->render('back/category.html.twig',array(
            'categories'=> $pagination,
        ));
    }

    /**
     * @param int $id
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(int $id, Request $request)    {
        $category = $this->getDoctrine()
            ->getRepository(RegistryCategory::class)
            ->find($id);
        if($category){
            $form = $this->createForm(RegistryCategoriesType::class, $category);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $category = $form->getData();
                try{
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($category);
                    $em->flush();
                }catch (DBALException $e) {
                    $message = $e->getMessage();
                    $mess = explode(':',$message);
                    $this->session->getFlashBag()->add('danger', 'Kategoria nie została zedytowana'.$mess[1].' --- '. $message);

                    return $this->redirectToRoute('admin_category_edit',['id' => $id]);
                }

                $this->session->getFlashBag()->add('success', 'Admin został zmieniony');

                return $this->render('back/admin_category_edit.html.twig',array(
                    'category'=> $category,
                    'form'=> $form->createView()
                ));
            }

            return $this->render('back/admin_category_edit.html.twig',array(
                'category'=> $category,
                'form'=> $form->createView()
            ));
        }else {
            $this->session->getFlashBag()->add('danger', 'Kategoria nie została znaleziona');

            return $this->redirectToRoute('admin_category_list');
        }
    }

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse|Response
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder){

        $category = new RegistryCategory();
        $form = $this->createForm(RegistryCategoriesType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            try{
                $em = $this->getDoctrine()->getManager();
                $em->persist($category);
                $em->flush();
            }catch (DBALException $e) {
                $message = $e->getMessage();
                $mess = explode(':',$message);
                $this->session->getFlashBag()->add('danger', 'Kategoria nie została utworzona'.$mess[1].' --- '. $message);

                return $this->redirectToRoute('admin_category_new');
            }

            $this->session->getFlashBag()->add('success', 'Kategoria została dodana');

            return $this->redirectToRoute('admin_category_list',[]);
        }

        return $this->render('back/category_new.html.twig',[
            'form'=> $form->createView()
        ]);
    }

    /**
     * @param int $id
     * @param int $status
     * @return RedirectResponse
     */
    public function disable(int $id, int $status)    {
        $category = $this->getDoctrine()
            ->getRepository(RegistryCategory::class)
            ->find($id);
        $category->setEnabled($status);
        try{
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
        }catch (DBALException $e) {
            $message = $e->getMessage();
            $mess = explode(':',$message);
            $this->session->getFlashBag()->add('danger', 'Kategoria nie została wyłączona '.$mess[1].' --- '. $message);

        return $this->redirectToRoute('admin_category_list');
        }

        $this->session->getFlashBag()->add('success', 'Kategoria została wyłączona');

        return $this->redirectToRoute('admin_category_list');
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function delete(int $id)    {
        $category = $this->getDoctrine()
            ->getRepository(RegistryCategory::class)
            ->find($id);
        try{
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }catch (DBALException $e) {
            $message = $e->getMessage();
            $mess = explode(':',$message);
            $this->session->getFlashBag()->add('danger', 'Kategoria nie została usunięta'.$mess[1].' --- '. $message);

        return $this->redirectToRoute('admin_category_list');
        }

        $this->session->getFlashBag()->add('success', 'Kategoria została usunięta');

        return $this->redirectToRoute('admin_category_list');
    }
}
