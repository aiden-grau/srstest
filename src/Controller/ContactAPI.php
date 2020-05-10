<?php
// src/Controller/ContactAPI.php
namespace App\Controller;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ContactAPIType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class ContactAPI extends AbstractFOSRestController
{
    /**
     * API add Contact
     * @Rest\Post("/api/addcontact")
     *
     * @return Response
     */
    public function APIaddContact(Request $request)
    {
      $contact = new Contact();
      $form = $this->createForm(ContactAPIType::class, $contact);
      $data = json_decode($request->getContent(), true);
      $form->submit($data);
      if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($contact);
        $entityManager->flush();
        return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
      }
      return $this->handleView($this->view($form->getErrors()));
    }

}
