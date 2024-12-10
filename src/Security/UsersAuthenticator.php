<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UsersAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
   
    public function __construct(private UrlGeneratorInterface $urlGenerator, private UserProviderInterface $userProvider)
    {
        $this->urlGenerator = $urlGenerator;
        $this->userProvider = $userProvider;

    }

    public function authenticate(Request $request): Passport
    {
        $username = $request->getPayload()->getString('username');
        $user = $this->userProvider->loadUserByIdentifier($username);

        // Vérifier si l'utilisateur est une instance de l'entité Users
        if ($user instanceof \App\Entity\Users) {
            // Si l'utilisateur n'est pas vérifié, empêcher l'authentification
            if (!$user->isVerified()) {
                throw new AuthenticationException('Your email address is not verified. Please verify your email.');

            }
        }

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $username);
        
        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($request->getPayload()->getString('password')),
            [
                new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {

        // Récupérer l'utilisateur connecté
        $user = $token->getUser();
      
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            // Si l'utilisateur est admin, rediriger vers /admin
            return new RedirectResponse($this->urlGenerator->generate('admin'));
        } else {
            // Si l'utilisateur est un simple user, rediriger vers la page d'accueil
            return new RedirectResponse($this->urlGenerator->generate('app_home'));
        }
        

        // For example:
        // return new RedirectResponse($this->urlGenerator->generate('app_home'));
        //throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
