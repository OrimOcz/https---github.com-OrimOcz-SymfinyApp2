<?php

namespace App\Controller;

use App\Entity\CustomersContact;
use App\Form\CustomersType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MainController extends AbstractController
{
    /**
     * @Route("/main", name="main")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    /**
     * @Route("/loader", name="loader")
     */
    public function loader(): Response
    {
        header('Location: /');
        exit();
    }


    /**
     * @Route("/", name="create")
     */
    public function create(Request $request){

        $data = $this->getDoctrine()->getRepository(CustomersContact::class)->findAll();
        $removeTheField=true;


        $customers = new CustomersContact();
        $form = $this->createForm(CustomersType::class, $customers);
        $form->handleRequest($request);

        /* Control form */
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($customers);
            $em->flush();

            $this->addFlash('notice','Submitted Successfully!');

            return $this->redirectToRoute('loader');
        }


        return $this->render('main/create.html.twig',[
            'form' => $form->createView(),
            'contact' => $data
        ]);

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => false,
        ]);
    }
}