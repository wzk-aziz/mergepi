<?php

namespace App\Controller\Front_BackController;

use App\Entity\User;
use App\Form\UserType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;



#[Route('/back')]
class BackController extends AbstractController
{

    #[Route('/', name: 'app_back')]
    public function index(Security $security): Response
    {
        $user = $security->getUser();
        if (!$user || !$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_404');
        }
        return $this->render('back/index.html.twig', [
            'controller_name' => 'BackController',
        ]);
    }

    #[Route('/404', name: 'app_404')]
    public function error(): Response
    {
        return $this->render('back/404.html.twig', [
            'controller_name' => 'BackController',
        ]);
    }

    #[Route('/register', name: 'app_registerback')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user || !$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_404');
        }
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_back');
        }

        return $this->render('back/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    #[Route(path: '/login', name: 'app_loginback')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('back/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logoutback')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route(path: '/showusers', name: 'app_user_showall', methods: ['GET'])]
    public function showusers(UserRepository $userRepository): Response
    {
        return $this->render('back/showallusers.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    #[Route(path: '/{id}', name: 'app_user_showback', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('back/showuser.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_user_editback', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_showall', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/edituser.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_user_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $entityManager->remove($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_showall', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/deleteuser.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/{id}/tool', name: 'app_user_deletetool', methods: ['GET', 'POST'])]
    public function deletetool(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->find($user);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_showall', [], Response::HTTP_SEE_OTHER);
    }
}
