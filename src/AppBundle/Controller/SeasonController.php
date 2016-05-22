<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Season;
use AppBundle\Form\SeasonType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class SeasonController
 *
 * @Route("/season")
 * @Security("has_role('ROLE_USER')")
 *
 * @package AppBundle\Controller
 */
class SeasonController extends Controller
{
    /**
     * Lists all private Season entities by user.
     *
     * @Route("/", name="season_private")
     * @Method("GET")
     * @Template(":season:index.html.twig")
     *
     * @return array
     */
    public function privateIndexAction()
    {
        $datatable = $this->get('app.datatable.season');
        $datatable->buildDatatable($options = array(
            'show_user' => false
        ));

        return array(
            'datatable' => $datatable,
            'title' => 'Meine Anbaulisten'
        );
    }

    /**
     * Lists all public Season entities.
     *
     * @Route("/all", name="season_public")
     * @Method("GET")
     * @Template(":season:index.html.twig")
     *
     * @return array
     */
    public function publicIndexAction()
    {
        $datatable = $this->get('app.datatable.season');
        $datatable->buildDatatable($options = array(
            'show_user' => true
        ));
        $datatable->getAjax()->set(array(
            'url' => $this->get('router')->generate('season_public_results'),
        ));

        return array(
            'datatable' => $datatable,
            'title' => 'Alle Anbaulisten'
        );
    }

    /**
     * Find all private Season entities by user.
     *
     * @Route("/results", name="season_private_results")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexPrivateResultsAction()
    {
        $datatable = $this->get('app.datatable.season');
        $datatable->buildDatatable($options = array(
            'show_user' => false
        ));

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        $user = $this->getUser();
        $function = function(QueryBuilder $qb) use ($user)
        {
            $qb->andWhere('season.user = :user');
            $qb->setParameter('user', $user);
        };

        $query->addWhereAll($function);

        return $query->getResponse();
    }

    /**
     * Find all public Season entities.
     *
     * @Route("/all/results", name="season_public_results")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexPublicResultsAction()
    {
        $datatable = $this->get('app.datatable.season');
        $datatable->buildDatatable($options = array(
            'show_user' => true
        ));

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        return $query->getResponse();
    }

    /**
     * Creates a new Season entity.
     *
     * @param Request $request
     *
     * @Route("/", name="season_create")
     * @Method("POST")
     * @Template(":season:new.html.twig")
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $entity = new Season();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('season_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Season entity.
     *
     * @param Season $entity
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(Season $entity)
    {
        $form = $this->createForm(SeasonType::class, $entity, array(
            'action' => $this->generateUrl('season_create'),
            'method' => 'POST'
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Speichern', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Season entity.
     *
     * @Route("/new", name="season_new")
     * @Method("GET")
     * @Template(":season:new.html.twig")
     *
     * @return array
     */
    public function newAction()
    {
        $entity = new Season();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Season entity.
     *
     * @param Request $request
     * @param Season  $season
     * @param integer $page
     *
     * @return array
     * @Route("/show/{id}/{page}", name="season_show", options={"expose"=true}, defaults={"page" = 1})
     * @Method("GET")
     * @Template(":season:show.html.twig")
     *
     */
    public function showAction(Request $request, Season $season, $page)
    {
        // get posts
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Post')->findPostsBySeason($season);

        // paginate posts
        $paginator  = $this->get('knp_paginator');
        $posts = $paginator->paginate(
            $query,
            $request->query->getInt('page', $page), // page number
            5                                       // limit per page
        );

        // plants
        $options = array(
            'seasonId' => $season->getId()
        );
        $plantsDatatable = $this->get('app.datatable.plant');
        $plantsDatatable->buildDatatable($options);

        return array(
            'season' => $season,
            'posts' => $posts,
            'plants_datatable' => $plantsDatatable,
        );
    }

    /**
     * Displays a form to edit an existing Season entity.
     *
     * @param Season $season
     *
     * @Route("/{id}/edit", name="season_edit", options={"expose"=true})
     * @Method("GET")
     * @Template(":season:edit.html.twig")
     * @Security("season.isOwner(user)")
     *
     * @return array
     */
    public function editAction(Season $season)
    {
        $editForm = $this->createEditForm($season);
        $deleteForm = $this->createDeleteForm($season);

        return array(
            'entity' => $season,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Season entity.
    *
    * @param Season $entity
    *
    * @return \Symfony\Component\Form\Form
    */
    private function createEditForm(Season $entity)
    {
        $form = $this->createForm(SeasonType::class, $entity, array(
            'action' => $this->generateUrl('season_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Speichern', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Edits an existing Season entity.
     *
     * @param Request $request
     * @param Season  $season
     *
     * @Route("/{id}", name="season_update")
     * @Method("PUT")
     * @Template(":season:edit.html.twig")
     * @Security("season.isOwner(user)")
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, Season $season)
    {
        $em = $this->getDoctrine()->getManager();

        $originalPlants = new ArrayCollection();
        foreach ($season->getPlants() as $plant) {
            $originalPlants->add($plant);
        }

        $deleteForm = $this->createDeleteForm($season);
        $editForm = $this->createEditForm($season);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            foreach ($originalPlants as $plant) {
                if (false === $season->getPlants()->contains($plant)) {
                    $season->getPlants()->removeElement($plant);
                    $em->remove($plant);
                }
            }

            $em->persist($season);
            $em->flush();

            return $this->redirect($this->generateUrl('season_show', array('id' => $season->getId())));
        }

        return array(
            'entity' => $season,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Season entity.
     *
     * @param Request $request
     * @param Season  $season
     *
     * @Route("/{id}", name="season_delete")
     * @Method("DELETE")
     * @Security("season.isOwner(user)")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Season $season)
    {
        $form = $this->createDeleteForm($season);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($season);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('season_private'));
    }

    /**
     * Creates a form to delete a Season entity by id.
     *
     * @param Season $season
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(Season $season)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('season_delete', array('id' => $season->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Löschen', 'attr' => array('class' => 'btn btn-danger')))
            ->getForm()
        ;
    }
}
