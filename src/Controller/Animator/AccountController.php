<?php
namespace App\Controller\Animator;


use App\Entity\Animator;
use App\Entity\Config;
use App\Form\AnimatorType;
use App\Service\Facebook;
use App\Service\GoogleAuthenticator;
use App\Utils\MailManagerUtils;
use App\Utils\RecaptchaUtils;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Swift_Image;
use Swift_Message;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    private Session $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function accountPage():Response
    {
        $animator = $this->getUser();

        return $this->render('front/account.html.twig',[
            'animator'=>$animator,
        ]);
    }
}