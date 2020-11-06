<?php
namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Entity\Registry;
use App\Entity\RegistryCategory;
use App\Form\RegistryCategoriesType;
use App\Form\RegistryType;
use Doctrine\DBAL\DBALException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\AdminType;

class RegistryController extends AbstractController
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
    public function registriesPage(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Registry::class)->findBy([],[
            $request->query->get('sort','id')=>$request->query->get('direction','asc')
        ]);
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );


        return $this->render('back/registry.html.twig',array(
            'registries'=> $pagination,
        ));
    }

    /**
     * @param int $id
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(int $id, Request $request)    {
        $registry = $this->getDoctrine()
            ->getRepository(Registry::class)
            ->find($id);
        if($registry){
            $form = $this->createForm(RegistryType::class, $registry);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $registry = $form->getData();
                try{
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($registry);
                    $em->flush();
                }catch (DBALException $e) {
                    $message = $e->getMessage();
                    $mess = explode(':',$message);
                    $this->session->getFlashBag()->add('danger', 'Wpis nie został zedytowany'.$mess[1].' --- '. $message);

                    return $this->redirectToRoute('admin_registry_edit',['id' => $id]);
                }

                $this->session->getFlashBag()->add('success', 'Admin został zmieniony');

                return $this->render('back/registry_edit.html.twig',array(
                    'registry'=> $registry,
                    'form'=> $form->createView()
                ));
            }

            return $this->render('back/registry_edit.html.twig',array(
                'registry'=> $registry,
                'form'=> $form->createView()
            ));
        }else {
            $this->session->getFlashBag()->add('danger', 'Rejestr nie został znaleziony');

            return $this->redirectToRoute('admin_registry_list');
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request){

        $registry = new Registry();
        $form = $this->createForm(RegistryType::class, $registry);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registry = $form->getData();
            try{
                $em = $this->getDoctrine()->getManager();
                $em->persist($registry);
                $em->flush();
            }catch (DBALException $e) {
                $message = $e->getMessage();
                $mess = explode(':',$message);
                $this->session->getFlashBag()->add('danger', 'Wpis nie został utworzony'.$mess[1].' --- '. $message);

                return $this->redirectToRoute('admin_registry_new');
            }

            $this->session->getFlashBag()->add('success', 'Wpis została dodany');

            return $this->redirectToRoute('admin_registry_list',[]);
        }

        return $this->render('back/registry_new.html.twig',array(
            'form'=> $form->createView()
        ));
    }

    /**
     * @param int $id
     * @param int $status
     * @return RedirectResponse
     */
    public function disable(int $id, int $status)    {
        $registry = $this->getDoctrine()
            ->getRepository(Registry::class)
            ->find($id);
        $registry->setEnabled($status);
        try{
            $em = $this->getDoctrine()->getManager();
            $em->persist($registry);
            $em->flush();
        }catch (DBALException $e) {
            $message = $e->getMessage();
            $mess = explode(':',$message);
            $this->session->getFlashBag()->add('danger', 'Wpis nie została wyłączony '.$mess[1].' --- '. $message);

        return $this->redirectToRoute('admin_registry_list');
        }

        $this->session->getFlashBag()->add('success', 'Wpis został wyłączony');

        return $this->redirectToRoute('admin_registry_list');
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function delete(int $id)    {
        $registry = $this->getDoctrine()
            ->getRepository(Registry::class)
            ->find($id);
        try{
            $em = $this->getDoctrine()->getManager();
            $em->remove($registry);
            $em->flush();
        }catch (DBALException $e) {
            $message = $e->getMessage();
            $mess = explode(':',$message);
            $this->session->getFlashBag()->add('danger', 'Wpis nie został usunięty'.$mess[1].' --- '. $message);

        return $this->redirectToRoute('admin_registry_list');
        }

        $this->session->getFlashBag()->add('success', 'Wpis została usunięty');

        return $this->redirectToRoute('admin_registry_list');
    }
}
