<?php

namespace App\Controller\Front_BackController;

use App\Entity\User;
use App\Form\UserType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
#[Route('/')]
class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function index(Security $security): Response
    {
        $user = $security->getUser();
    
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
            'user' => $user,
        ]);
    }
    #[Route('/404', name: 'app_404')]
    public function error(): Response
    {
        return $this->render('back/404.html.twig', [
            'controller_name' => 'BackController',
        ]);
    }
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('Password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_front');
        }

        return $this->render('front/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('front/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route(path: '/showuser', name: 'app_user_showuser', methods: ['GET'])]
    public function showusersfront(UserRepository $userRepository,Security $security): Response
    {
        $user = $security->getUser();
        return $this->render('front/showuser.html.twig', [
            'users' => $userRepository->find($user),
            'user' => $user,
        ]);
    }

        #[Route('/edit', name: 'app_user_edituser', methods: ['GET', 'POST'])]
        public function edit(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, Security $security): Response
{
    // Get the currently logged-in user
    $loggedInUser = $security->getUser();

    // Retrieve the User entity using the UserRepository
    $user = $userRepository->find($loggedInUser->getId());

    // Compare the identities of the logged-in user and the user being modified
    if ($loggedInUser !== $user) {
        // The user being modified is not the same as the logged-in user
        return $this->redirectToRoute('app_404');
    }

    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Save the updated user data to the database
        $entityManager->flush();

        // Redirect to the user's profile page or any other appropriate page
        return $this->redirectToRoute('app_front', ['id' => $user->getId()]);
    }

    return $this->render('front/edituser.html.twig', [
        'form' => $form->createView(),
        'user' => $user,
    ]);
}

}
