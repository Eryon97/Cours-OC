<?php

namespace MapsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use MapsBundle\Form\SearchType;
use MapsBundle\Form\VisiteType;
use MapsBundle\Entity\Visite;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MapsBundle:Default:index.html.twig');
    }

    public function addVisiteAction(Request $request)
    {
      $visite = new Visite;
      $form = $this->createForm(VisiteType::class, $visite);

      $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
          $em = $this->getDoctrine()->getManager();

          $em->persist($visite);
          $em->flush();

          $this->addFlash('success', 'Votre visite à été publiée.');
          return $this->redirectToRoute('maps_add');
        }

      return $this->render('MapsBundle:Default:add.html.twig', array(
        'form' => $form->createView(),
      ));
    }

    public function searchAction(Request $request)
    {
      $form = $this->createForm(SearchType::class);

      $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
          $month = $form["month"]->getData();
          $year = $form["year"]->getData();

          $visites = $this->getDoctrine()->getManager()->getRepository('MapsBundle:Visite')->listeVisite($month, $year);

          return $this->render('MapsBundle:Default:results.html.twig', array(
            'visites' => $visites,
          ));
        }

      return $this->render('MapsBundle:Default:search.html.twig', array(
        'form' => $form->createView(),
      ));
    }
}
