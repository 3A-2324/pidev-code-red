<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CustomAuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $roles = $token->getUser()->getRoles();
        if (in_array('ROLE_ADMIN', $roles, true)) {
            return new RedirectResponse('/user');
        } elseif (in_array('ROLE_USER', $roles, true)) {
            return new RedirectResponse('/home');
        }
    }
}
