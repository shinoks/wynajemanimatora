<?php
namespace App\Utils;

use App\Entity\Config;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;

class MailManagerUtils
{
    protected $mailer;
    protected $twig;
    protected $em;
    protected $config;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function sendEmail(string $template, array $parameters, string $to, Swift_Mailer $mailer, ?array $files = null): int
    {
        $this->config = $this->em->getRepository(Config::class)->findOneBy(['id'=>'1']);
        $from = $this->config->getEmail();
        $fromName = $this->config->getTitle();
        $subject  = $parameters['subject'];

        try {
            $message = (new Swift_Message())
                ->setSubject($subject)
                ->setFrom($from, $fromName)
                ->setTo($to)
                ->setBody($template, 'text/html')
            ;
            if($files){
                foreach($files as $file){
                    $message->attach(Swift_Attachment::fromPath($file));
                }
            }
            $response = $mailer->send($message);

        } catch (Exception $ex) {
            return $ex->getMessage();
        }

        return $response;
    }
}
