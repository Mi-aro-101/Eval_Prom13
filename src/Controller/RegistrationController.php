<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\UtilisateurDetails;
use App\Form\RegistrationFormType;
use App\Security\UtilisateurAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $roleToken = $request->query->get('role_token');
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($this->isCsrfTokenValid('admin', $roleToken)){
                // Say role is admin
                $request->attributes->set('role', 0);
                $user->setRoles(['ROLE_ADMIN']);
            }
            else {
                $request->attributes->set('role', 5);
                $user->setRoles(['ROLE_CHAUFFEUR']);
            }
            $utilisateurDetails  = new UtilisateurDetails();
            try {
                $utilisateurDetails->processDatas($request, $entityManager);
            } catch (\Throwable $th) {
                dd($th->getMessage());
            }

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            //trim email
            $email = $user->getEmail();
            $user->setEmail(trim($email));
            $user->setUtilisateurDetails($utilisateurDetails);
            try {
                $entityManager->beginTransaction();
                    $entityManager->persist($utilisateurDetails);
                    $entityManager->persist($user);
                    $entityManager->flush();
                $entityManager->commit();
            } catch (\Throwable $ex) {
                $entityManager->rollback();
                dd($ex->getMessage());
            }

            // do anything else you need here, like send an email

            return $security->login($user, UtilisateurAuthenticator::class, 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
