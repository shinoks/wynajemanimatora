<?php

namespace App\Service;

use Google_Client;
use Google_Service_Oauth2;
use Google_Service_Oauth2_Resource_Userinfo;

class GoogleAuthenticator
{
    private string $siteUrl;
    private string $googleClientId;
    private string $googleClientSecret;

    public function __construct(string $siteUrl, string $googleClientId, string $googleClientSecret)
    {
        $this->siteUrl = $siteUrl;
        $this->googleClientId = $googleClientId;
        $this->googleClientSecret = $googleClientSecret;
    }

    public function getGoogleLoginLink(): string
    {
        $google = new Google_Client();
        $google->setClientId($this->googleClientId);
        $google->setClientSecret($this->googleClientSecret);
        $google->setRedirectUri($this->siteUrl."google_login");
        $google->addScope("email");
        $google->addScope("profile");

        return $google->createAuthUrl();
    }

    public function googleLogin(): ?Google_Service_Oauth2_Resource_Userinfo
    {
        $google = new Google_Client();
        $google->setClientId($this->googleClientId);
        $google->setClientSecret($this->googleClientSecret);
        $google->setRedirectUri($this->siteUrl."google_login");
        $google->addScope("email");
        $google->addScope("profile");

        $token = $google->fetchAccessTokenWithAuthCode($_GET["code"]);
        $data = NULL;
        if(!isset($token['error']))
        {
            $google->setAccessToken($token['access_token']);
            $_SESSION['access_token'] = $token['access_token'];
            $google_service = new Google_Service_Oauth2($google);
            $data = $google_service->userinfo->get();
            if(!empty($data['given_name']))
            {
                $_SESSION['user_first_name'] = $data['given_name'];
            }

            if(!empty($data['family_name']))
            {
                $_SESSION['user_last_name'] = $data['family_name'];
            }

            if(!empty($data['email']))
            {
                $_SESSION['user_email_address'] = $data['email'];
            }

            if(!empty($data['gender']))
            {
                $_SESSION['user_gender'] = $data['gender'];
            }

            if(!empty($data['picture']))
            {
                $_SESSION['user_image'] = $data['picture'];
            }
        }

        return $data;
    }
}