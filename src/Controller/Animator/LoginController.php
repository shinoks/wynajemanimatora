<?php
namespace App\Controller\Animator;


use App\Entity\Animator;
use App\Entity\Approval;
use App\Entity\Config;
use App\Entity\UserApproval;
use App\Form\AnimatorType;
use App\Service\Facebook;
use App\Service\GoogleAuthenticator;
use App\Utils\MailManagerUtils;
use App\Utils\RecaptchaUtils;
use Doctrine\ORM\EntityManagerInterface;
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

class LoginController extends AbstractController
{
    /**
     * @var Session
     */
    private Session $session;

    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Swift_Mailer $mailer
     * @param RecaptchaUtils $recaptchaUtils
     * @param EntityManagerInterface $emi
     * @param Facebook $fb
     * @param GoogleAuthenticator $google
     * @return RedirectResponse|Response
     */
    public function registerPage(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        Swift_Mailer $mailer,
        RecaptchaUtils $recaptchaUtils,
        EntityManagerInterface $emi,
        Facebook $fb,
        GoogleAuthenticator $google)
    {
        #TODO retrieve FB LOGIN LINK
        #$fbLink = $fb->fbLogin();
        $fbLink = '#';
        $googleLink = $google->getGoogleLoginLink();
        $form = $this->createForm(AnimatorType::class,[]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           # $captcha = $recaptchaUtils->check($request->get('g-recaptcha-response'),$request->getClientIp());
            $captcha = true;
            if($captcha ==false){
                $this->session->getFlashBag()->add('danger', 'Wypełnij formularz ponownie. Błąd captchy');


                return $this->render('front/register.html.twig',array(
                    'form' => $form->createView(),
                    'fbLink' => $fbLink,
                    'googleLink' => $googleLink
                ));
            }
            $animator = new Animator();
            $animator->setEmail($form->get('email')->getData());
            $animator->setPassword($form->get('password')->getData());
            $animator->setRoles(['ROLE_ANIMATOR']);
            //$animator->setIsActive(0);
            //$animator->setIsEnabledByAdmin(0);
            $password = $passwordEncoder->encodePassword($animator, $animator->getPassword());
            $animator->setPassword($password);
            $animator->setHash(uniqid("", true));
            $em = $this->getDoctrine()->getManager();
            $em->persist($animator);
            $em->flush();
            $approvalRegulation = $this->getDoctrine()
                ->getRepository(Approval::class)
                ->find(1);
            $userApproval = new UserApproval();
            $userApproval->setUser($animator);
            $userApproval->setApproval($approvalRegulation);
            $userApproval->setApprovalText($approvalRegulation->getText());
            $animator->addUserApproval($userApproval);
            $em->persist($userApproval);
            $approvalRegulation = $this->getDoctrine()
                ->getRepository(Approval::class)
                ->find(2);
            $userApproval = new UserApproval();
            $userApproval->setUser($animator);
            $userApproval->setApproval($approvalRegulation);
            $userApproval->setApprovalText($approvalRegulation->getText());
            $animator->addUserApproval($userApproval);
            $em->persist($userApproval);
            if($form->get('marketingRegulations')->getData()){
                $approvalRegulation = $this->getDoctrine()
                    ->getRepository(Approval::class)
                    ->find(3);
                $userApproval = new UserApproval();
                $userApproval->setUser($animator);
                $userApproval->setApproval($approvalRegulation);
                $userApproval->setApprovalText($approvalRegulation->getText());
                $animator->addUserApproval($userApproval);
                $em->persist($userApproval);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($animator);
            $em->flush();
            $this->session = new Session();
            $this->session->getFlashBag()->add('success', 'Zostałeś zarejestrowany. Potwierdź rejestrację klikając w link w przesłanej wiadomości email');

            $config = $this->getDoctrine()
                ->getRepository(Config::class)
                ->find(1);

            $message = (new Swift_Message('Formularz rejestracyjny z '.$config->getTitle()))
                ->setFrom($config->getEmail())
                ->setReplyTo($config->getEmail())
                ->setTo($animator->getEmail());
            $cid = $message->embed(Swift_Image::fromPath('uploads/files/logo.png'));
            $message
                ->setBody(
                    $this->renderView(
                        'front/emails/register_form.html.twig',
                        [
                            'user' => $animator,
                            'config' => $config,
                            'fbLink' => $fbLink,
                            'googleLink' => $googleLink,
                            'cid' => $cid
                        ]
                    ),
                    'text/html'
                )
            ;
            $mailManager = new MailManagerUtils($emi);
            $mailer->send($message);
            $cid = $message->embed(Swift_Image::fromPath('uploads/files/logo.png'));
            $mailBody = $this->renderView('front/emails/user_new_created.html.twig');
            $mailManager->sendEmail($mailBody,['subject' => 'Nowy użytkownik się zarejestrował - '.$config->getTitle()],'shinoks@o2.pl',$mailer);

            return $this->redirectToRoute('animator_login');
        }

        return $this->render('front/register.html.twig',array(
            'form' => $form->createView(),
            'fbLink' => $fbLink,
            'googleLink' => $googleLink
        ));
    }

    public function enable(string $h): RedirectResponse
    {
        $animator = $this->getDoctrine()
            ->getRepository(Animator::class)
            ->findOneBy(['hash' => $h]);
        if($animator){
            $animator->setIsActive(1);
            $animator->setHash(null);

            $em = $this->getDoctrine()->getManager();
            $em->persist($animator);
            $em->flush();

            $this->session->getFlashBag()->add('success', 'Konto zostało aktywowane. Możesz się zalogować');
        }

        return $this->redirectToRoute('login');
    }

    public function loginPage(AuthenticationUtils $authenticationUtils, Facebook $fb,GoogleAuthenticator $google): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $fbLink = $fb->fbLogin();
        $googleLink = $google->getGoogleLoginLink();

        return $this->render('front/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
            'session' => $this->session,
            'fbLink' => $fbLink,
            'googleLink' => $googleLink
        ]);
    }

    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}