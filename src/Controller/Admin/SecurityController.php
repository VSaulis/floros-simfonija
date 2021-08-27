<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    private $translator;

    public function __construct(TranslatorInterface  $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/admin/login", name="admin login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('@EasyAdmin/page/login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'last_username' => $authenticationUtils->getLastUsername(),
            'translation_domain' => 'admin',
            'page_title' => $this->translator->trans('titles.login', [], 'admin'),
            'csrf_token_intention' => 'authenticate',
            'target_path' => $this->generateUrl('admin'),
            'username_label' => 'labels.email',
            'password_label' => 'labels.password',
            'sign_in_label' => 'buttons.login',
            'username_parameter' => 'email',
            'password_parameter' => 'password',
        ]);
    }
}