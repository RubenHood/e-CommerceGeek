<?php

// src/AppBundle/Security/TokenAuthenticator.php
namespace eCommerceBundle\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Psr\Log\LoggerInterface;

class UserAuthenticator extends AbstractGuardAuthenticator
{
    private $logger = null;

    public function __construct(LoggerInterface $logger)
    {   
        $this->logger = $logger;
    }


    public function supports(Request $request)
    {
        $this->logger->info('Entro en supports');
        return 'login' === $request->attributes->get('_route') && $request->isMethod('POST');
    }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     */
    public function getCredentials(Request $request)
    {
        return null;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        
        return null;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // check credentials - e.g. make sure the password is valid
        // no credential check is needed in this case

        // return true to cause authentication success
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return null;
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return null;
    }

    public function supportsRememberMe()
    {
        return false;
    }
}