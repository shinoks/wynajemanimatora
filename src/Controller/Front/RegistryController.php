<?php
namespace App\Controller\Front;


use App\Entity\Config;
use App\Entity\Registry;
use App\Form\RegistryType;
use App\Utils\MailManagerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Image;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class RegistryController extends AbstractController
{
    private Session $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function addRegistryPage(Request $request, Swift_Mailer $mailer)
    {
        $registry = new Registry();
        $form = $this->createForm(RegistryType::class, $registry);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($registry);
            $em->flush();

            $config = $this->getDoctrine()
                ->getRepository(Config::class)
                ->find(1);

            $message = (new Swift_Message('Dodałeś nowy wpis na '.$config->getTitle()))
                ->setFrom($config->getEmail())
                ->setReplyTo($config->getEmail())
                ->setTo($registry->getEmail());

            $cid = $message->embed(Swift_Image::fromPath('uploads/files/logo.png'));
            $message
                ->setBody(
                    $this->renderView(
                        'front/emails/registry_add.html.twig',
                        [
                            'registry' => $registry,
                            'config' => $config,
                            'cid' => $cid
                        ]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);
            $this->session->getFlashBag()->add('success', 'Wpis został dodany. Potwierdź adres email klikając w przesłanej wiadomości.');

            return $this->redirectToRoute('index');
        }

        return $this->render('front/add_registry.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}