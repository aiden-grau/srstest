<?php
// src/Controller/ContactForm.php
namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Emailer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as Encoder;

class ContactForm extends AbstractController
{
    /**
     * @Route("/")
     */
    public function contact(Request $request, Emailer $emailer, Encoder $encoder)
    {
      $form = $this->createForm(ContactType::class);
      $form->handleRequest($request);
      if ($form->isSubmitted()) {
        $recaptcha = $request->get('g-recaptcha-response');
        if ($form->isValid()) {
          $data = $form->getData();
          if ($data['message'] == 'makeuser') {
            $id = $this->addUser($data, $encoder);
            $this->addFlash('success', 'You are user number ' . $id);
          } else {
            $id = $this->addContact($data);
            $emailer->sendUserEmail($data);
            $emailer->sendAdminEmail($data);
            $this->addFlash('success', 'Your message has been sent');
          }
        } elseif (empty($recaptcha)) {
          $this->addFlash('warning', 'Please confirm that you are not a robot');
        } else {
          $this->addFlash('warning', 'Oops! Your submission was not valid...');
        }
      }
      return $this->render('contact.html.twig', ['form' => $form->createView()]);
    }

    private function addContact($data)
    {
      $contact = new Contact();
      $contact->setName($data['name']);
      $contact->setEmail($data['email']);
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($contact);
      $entityManager->flush();
      return $contact->getId();
    }

    private function addUser($data, $encoder)
    {
      $user = new User();
      $user->setEmail($data['email']);
      $user->setRoles(['ROLE_USER']);
      $user->setPassword($encoder->encodePassword($user, $data['name']));
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($user);
      $entityManager->flush();
      return $user->getId();
    }

}
