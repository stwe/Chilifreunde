<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Location;
use AppBundle\Form\LocationType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\ORM\QueryBuilder;

/**
 * Location controller.
 *
 * @Route("/location")
 * @Security("has_role('ROLE_USER')")
 *
 * @package AppBundle\Controller
 */
class LocationController extends Controller
{
    /**
     * Lists all Location entities.
     *
     * @Route("/", name="location")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $datatable = $this->get('app.datatable.location');
        $datatable->buildDatatable();

        return $this->render('location/index.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * Get Locations for datatable.
     *
     * @Route("/results", name="location_results")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexResultsAction()
    {
        $datatable = $this->get('app.datatable.location');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        $user = $this->getUser();
        $function = function(QueryBuilder $qb) use ($user)
        {
            $qb->andWhere('location.user = :user');
            $qb->setParameter('user', $user);
        };

        $query->addWhereAll($function);

        return $query->getResponse();
    }

    /**
     * Creates a new Location entity.
     *
     * @param Request $request
     *
     * @Route("/new", name="location_new")
     * @Method({"GET", "POST"})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $location = new Location();
        $form = $this->createForm('AppBundle\Form\LocationType', $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($location);
            $em->flush();

            return $this->redirectToRoute('location_show', array('id' => $location->getId()));
        }

        return $this->render('location/new.html.twig', array(
            'location' => $location,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Location entity.
     *
     * @param Location $location
     *
     * @Route("/{id}", name="location_show", options={"expose"=true})
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Location $location)
    {
        return $this->render('location/show.html.twig', array(
            'location' => $location
        ));
    }

    /**
     * Displays a form to edit an existing Location entity.
     *
     * @param Request  $request
     * @param Location $location
     *
     * @Route("/{id}/edit", name="location_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER') and location.isOwner(user)")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Location $location)
    {
        $deleteForm = $this->createDeleteForm($location);
        $editForm = $this->createForm('AppBundle\Form\LocationType', $location);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($location);
            $em->flush();

            return $this->redirectToRoute('location_show', array('id' => $location->getId()));
        }

        return $this->render('location/edit.html.twig', array(
            'location' => $location,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Location entity.
     *
     * @param Request  $request
     * @param Location $location
     *
     * @Route("/{id}", name="location_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER') and location.isOwner(user)")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Location $location)
    {
        $form = $this->createDeleteForm($location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($location);
            $em->flush();
        }

        return $this->redirectToRoute('location');
    }

    /**
     * Creates a form to delete a Location entity.
     *
     * @param Location $location The Location entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Location $location)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('location_delete', array('id' => $location->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
