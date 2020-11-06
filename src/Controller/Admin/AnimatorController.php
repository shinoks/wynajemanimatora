<?php
namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Entity\Animator;
use App\Entity\Registry;
use App\Entity\RegistryCategory;
use App\Form\AnimatorType;
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

class AnimatorController extends AbstractController
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
    public function animatorsPage(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Animator::class)->findBy([],[
            $request->query->get('sort','id')=>$request->query->get('direction','asc')
        ]);
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );

        return $this->render('back/animator.html.twig',array(
            'animators'=> $pagination,
        ));
    }

    /**
     * @param int $id
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(int $id, Request $request)    {
        $animator = $this->getDoctrine()
            ->getRepository(Animator::class)
            ->find($id);
        if($animator){
            $form = $this->createForm(AnimatorType::class, $animator);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $animator = $form->getData();
                try{
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($animator);
                    $em->flush();
                }catch (DBALException $e) {
                    $message = $e->getMessage();
                    $mess = explode(':',$message);
                    $this->session->getFlashBag()->add('danger', 'Animator nie został zedytowany'.$mess[1].' --- '. $message);

                    return $this->redirectToRoute('admin_animator_edit',['id' => $id]);
                }

                $this->session->getFlashBag()->add('success', 'Animator został zmieniony');

                return $this->render('back/admin_animator_edit.html.twig',array(
                    'animator'=> $animator,
                    'form'=> $form->createView()
                ));
            }

            return $this->render('back/admin_animator_edit.html.twig',array(
                'animator'=> $animator,
                'form'=> $form->createView()
            ));
        }else {
            $this->session->getFlashBag()->add('danger', 'Animator nie został znaleziony');

            return $this->redirectToRoute('admin_animator_list');
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request){
        $animator = new Registry();
        $form = $this->createForm(AnimatorType::class, $animator);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $animator = $form->getData();
            try{
                $em = $this->getDoctrine()->getManager();
                $em->persist($animator);
                $em->flush();
            }catch (DBALException $e) {
                $message = $e->getMessage();
                $mess = explode(':',$message);
                $this->session->getFlashBag()->add('danger', 'Animator nie został utworzony'.$mess[1].' --- '. $message);

                return $this->redirectToRoute('admin_animator_new');
            }

            $this->session->getFlashBag()->add('success', 'Animator została dodany');

            return $this->redirectToRoute('admin_animator_list',[]);
        }

        return $this->render('back/admin_animator.html.twig',array(
            'form'=> $form->createView()
        ));
    }

    /**
     * @param int $id
     * @param int $status
     * @return RedirectResponse
     */
    public function disable(int $id, int $status)    {
        $animator = $this->getDoctrine()
            ->getRepository(Animator::class)
            ->find($id);
        $animator->setEnabled($status);
        try{
            $em = $this->getDoctrine()->getManager();
            $em->persist($animator);
            $em->flush();
        }catch (DBALException $e) {
            $message = $e->getMessage();
            $mess = explode(':',$message);
            $this->session->getFlashBag()->add('danger', 'Animator nie został wyłączony '.$mess[1].' --- '. $message);

        return $this->redirectToRoute('admin_registry_list');
        }

        $this->session->getFlashBag()->add('success', 'Animator został wyłączony');

        return $this->redirectToRoute('admin_animator_list');
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function delete(int $id)    {
        $animator = $this->getDoctrine()
            ->getRepository(Animator::class)
            ->find($id);
        try{
            $em = $this->getDoctrine()->getManager();
            $em->remove($animator);
            $em->flush();
        }catch (DBALException $e) {
            $message = $e->getMessage();
            $mess = explode(':',$message);
            $this->session->getFlashBag()->add('danger', 'Animator nie został usunięty'.$mess[1].' --- '. $message);

        return $this->redirectToRoute('admin_animator_list');
        }

        $this->session->getFlashBag()->add('success', 'Animator został usunięty');

        return $this->redirectToRoute('admin_animator_list');
    }
}
