<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Source;
use AppBundle\Form\SourceType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

/**
 * Class SourceController
 *
 * @Route("/source")
 * @Security("has_role('ROLE_USER')")
 *
 * @package AppBundle\Controller
 */
class SourceController extends Controller
{
    /**
     * Lists all private Source entities by user.
     *
     * @Route("/", name="source_private")
     * @Method("GET")
     * @Template(":source:index.html.twig")
     *
     * @return array
     */
    public function privateIndexAction()
    {
        $topAction = array(
            'start_html' => '<div class="row"><div class="col-sm-3">',
            'end_html' => '<hr></div></div>',
            'actions' => array(
                array(
                    'route' => $this->get('router')->generate('source_new'),
                    'label' => $this->get('translator')->trans('datatables.actions.new'),
                    'icon' => 'glyphicon glyphicon-plus',
                    'role' => 'ROLE_USER',
                    'attributes' => array(
                        'rel' => 'tooltip',
                        'title' => $this->get('translator')->trans('datatables.actions.new'),
                        'class' => 'btn btn-primary',
                        'role' => 'button'
                    ),
                )
            )
        );

        $datatable = $this->get('app.datatable.source');
        $datatable->getTopActions()->set($topAction);
        $datatable->buildDatatable();

        return array(
            'datatable' => $datatable,
            'title' => 'Meine Bezugsquellen'
        );
    }

    /**
     * Lists all public Source entities.
     *
     * @Route("/all", name="source_public")
     * @Method("GET")
     * @Template(":source:index.html.twig")
     *
     * @return array
     */
    public function publicIndexAction()
    {
        $datatable = $this->get('app.datatable.source');
        $datatable->buildDatatable();
        $datatable->getAjax()->set(array(
            'url' => $this->get('router')->generate('source_public_results'),
        ));

        return array(
            'datatable' => $datatable,
            'title' => 'Öffentliche Bezugsquellen'
        );
    }

    /**
     * Find all private Source entities by user.
     *
     * @Route("/results", name="source_private_results")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexPrivateResultsAction()
    {
        $datatable = $this->get('app.datatable.source');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        $user = $this->getUser();
        $function = function(QueryBuilder $qb) use ($user)
        {
            $qb->andWhere('source.user = :user');
            $qb->andWhere('source.public = false');
            $qb->setParameter('user', $user);
        };

        $query->addWhereAll($function);

        return $query->getResponse();
    }

    /**
     * Find all public Source entities.
     *
     * @Route("/all/results", name="source_public_results")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexPublicResultsAction()
    {
        $datatable = $this->get('app.datatable.source');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        $function = function(QueryBuilder $qb)
        {
            $qb->andWhere('source.public = true');
        };

        $query->addWhereAll($function);

        return $query->getResponse();
    }

    /**
     * Creates a new Source entity.
     *
     * @param Request $request
     *
     * @Route("/", name="source_create")
     * @Method("POST")
     * @Template(":source:new.html.twig")
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $entity = new Source();
        $entity->setPublic(false);
        $entity->setUser($this->getUser());

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('source_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Source entity.
     *
     * @param Source $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Source $entity)
    {
        $form = $this->createForm(SourceType::class, $entity, array(
            'action' => $this->generateUrl('source_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Speichern', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Source entity.
     *
     * @Route("/new", name="source_new")
     * @Method("GET")
     * @Template(":source:new.html.twig")
     *
     * @return array
     */
    public function newAction()
    {
        $entity = new Source();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Source entity.
     *
     * @param Source  $source
     *
     * @Route("/{id}", name="source_show", options={"expose"=true})
     * @Method("GET")
     * @Template(":source:show.html.twig")
     *
     * @return array
     */
    public function showAction(Source $source)
    {
        $options = array(
            'sourceId' => $source->getId()
        );

        $addressDatatable = $this->get('app.datatable.address');
        $addressDatatable->buildDatatable($options);

        return array(
            'entity' => $source,
            'address_datatable' => $addressDatatable
        );
    }

    /**
     * Displays a form to edit an existing Source entity.
     *
     * @param Source $source
     *
     * @Route("/{id}/edit", name="source_edit", options={"expose"=true})
     * @Method("GET")
     * @Template(":source:edit.html.twig")
     * @Security("has_role('ROLE_USER') and source.isOwner(user) and not source.isPublic()")
     *
     * @return array
     */
    public function editAction(Source $source)
    {
        $editForm = $this->createEditForm($source);
        $deleteForm = $this->createDeleteForm($source);

        return array(
            'entity' => $source,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Source entity.
    *
    * @param Source $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Source $entity)
    {
        $form = $this->createForm(SourceType::class, $entity, array(
            'action' => $this->generateUrl('source_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Speichern', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Edits an existing Source entity.
     *
     * @param Request $request
     * @param Source  $source
     *
     * @Route("/{id}", name="source_update")
     * @Method("PUT")
     * @Template(":source:edit.html.twig")
     * @Security("has_role('ROLE_USER') and source.isOwner(user) and not source.isPublic()")
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, Source $source)
    {
        $em = $this->getDoctrine()->getManager();

        $originalAddresses = new ArrayCollection();
        foreach ($source->getAddresses() as $address) {
            $originalAddresses->add($address);
        }

        $deleteForm = $this->createDeleteForm($source);
        $editForm = $this->createEditForm($source);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            foreach ($originalAddresses as $address) {
                if (false === $source->getAddresses()->contains($address)) {
                    $source->getAddresses()->removeElement($address);
                    $em->remove($address);
                }
            }

            $em->persist($source);
            $em->flush();

            return $this->redirect($this->generateUrl('source_show', array('id' => $source->getId())));
        }

        return array(
            'entity' => $source,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Source entity.
     *
     * @param Request $request
     * @param Source  $source
     *
     * @Route("/{id}", name="source_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER') and source.isOwner(user) and not source.isPublic()")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Source $source)
    {
        $form = $this->createDeleteForm($source);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($source);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('source'));
    }

    /**
     * Creates a form to delete a Source entity by id.
     *
     * @param Source $source
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(Source $source)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('source_delete', array('id' => $source->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Löschen', 'attr' => array('class' => 'btn btn-danger')))
            ->getForm()
        ;
    }
}
