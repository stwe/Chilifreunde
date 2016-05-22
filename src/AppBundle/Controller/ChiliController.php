<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Chili;
use AppBundle\Form\ChiliType;

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
 * Class ChiliController
 *
 * @Route("/chili")
 * @Security("has_role('ROLE_USER')")
 *
 * @package AppBundle\Controller
 */
class ChiliController extends Controller
{
    /**
     * Lists all private Chili entities by user.
     *
     * @Route("/", name="chili_private")
     * @Method("GET")
     * @Template(":chili:index.html.twig")
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
                    'route' => $this->get('router')->generate('chili_new'),
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

        $datatable = $this->get('app.datatable.chili');
        $datatable->getTopActions()->set($topAction);
        $datatable->buildDatatable();

        return array(
            'datatable' => $datatable,
            'title' => 'Meine Chili-Datenbank'
        );
    }

    /**
     * Lists all public Chili entities.
     *
     * @Route("/all", name="chili_public")
     * @Method("GET")
     * @Template(":chili:index.html.twig")
     *
     * @return array
     */
    public function publicIndexAction()
    {
        $datatable = $this->get('app.datatable.chili');
        $datatable->buildDatatable();
        $datatable->getAjax()->set(array(
            'url' => $this->get('router')->generate('chili_public_results'),
        ));

        return array(
            'datatable' => $datatable,
            'title' => 'Öffentliche Chili-Datenbank'
        );
    }

    /**
     * Find all private Chili entities by user.
     *
     * @Route("/results", name="chili_private_results")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexPrivateResultsAction()
    {
        $datatable = $this->get('app.datatable.chili');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        $user = $this->getUser();
        $function = function(QueryBuilder $qb) use ($user)
        {
            $qb->andWhere('chili.user = :user');
            $qb->andWhere('chili.public = false');
            $qb->setParameter('user', $user);
        };

        $query->addWhereAll($function);

        return $query->getResponse();
    }

    /**
     * Find all public Chili entities.
     *
     * @Route("/all/results", name="chili_public_results")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexPublicResultsAction()
    {
        $datatable = $this->get('app.datatable.chili');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        $function = function (QueryBuilder $qb)
        {
            $qb->andWhere('chili.public = true');
        };

        $query->addWhereAll($function);

        return $query->getResponse();
    }

    /**
     * Creates a new Chili entity.
     *
     * @param Request $request
     *
     * @Route("/", name="chili_create")
     * @Method("POST")
     * @Template(":chili:new.html.twig")
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $entity = new Chili();
        $entity->setPublic(false);
        // setUser() despite @Gedmo\Blameable(on="create") for validate
        $entity->setUser($this->getUser());

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('chili_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Chili entity.
     *
     * @param Chili $entity
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(Chili $entity)
    {
        $form = $this->createForm(ChiliType::class, $entity, array(
            'action' => $this->generateUrl('chili_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Speichern', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Chili entity.
     *
     * @Route("/new", name="chili_new")
     * @Method("GET")
     * @Template(":chili:new.html.twig")
     *
     * @return array
     */
    public function newAction()
    {
        $entity = new Chili();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Chili entity.
     *
     * @param Chili $chili
     *
     * @Route("/{id}", name="chili_show", options={"expose"=true})
     * @Method("GET")
     * @Template(":chili:show.html.twig")
     *
     * @return array
     */
    public function showAction(Chili $chili)
    {
        return array(
            'entity' => $chili
        );
    }

    /**
     * Displays a form to edit an existing Chili entity.
     *
     * @param Chili $chili
     *
     * @Route("/{id}/edit", name="chili_edit", options={"expose"=true})
     * @Method("GET")
     * @Template(":chili:edit.html.twig")
     * @Security("chili.isOwner(user) and not chili.isPublic()")
     *
     * @return array
     */
    public function editAction(Chili $chili)
    {
        $editForm = $this->createEditForm($chili);
        $deleteForm = $this->createDeleteForm($chili);

        return array(
            'entity' => $chili,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Chili entity.
    *
    * @param Chili $entity
    *
    * @return \Symfony\Component\Form\Form
    */
    private function createEditForm(Chili $entity)
    {
        $form = $this->createForm(ChiliType::class, $entity, array(
            'action' => $this->generateUrl('chili_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Speichern', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Edits an existing Chili entity.
     *
     * @param Request $request
     * @param Chili   $chili
     *
     * @Route("/{id}", name="chili_update")
     * @Method("PUT")
     * @Template(":chili:edit.html.twig")
     * @Security("chili.isOwner(user) and not chili.isPublic()")
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, Chili $chili)
    {
        $em = $this->getDoctrine()->getManager();

        $originalImages = new ArrayCollection();
        foreach ($chili->getImages() as $image) {
            $originalImages->add($image);
        }

        $deleteForm = $this->createDeleteForm($chili);
        $editForm = $this->createEditForm($chili);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            foreach ($originalImages as $image) {
                if (false === $chili->getImages()->contains($image)) {
                    $chili->getImages()->removeElement($image);
                    $cacheManager = $this->get('liip_imagine.cache.manager');
                    $cacheManager->resolve('images/'.$image->getFileName(), 'thumbnail_192_x_200');
                    $cacheManager->remove('images/'.$image->getFileName(), 'thumbnail_192_x_200');
                    $em->remove($image);
                }
            }

            $em->persist($chili);
            $em->flush();

            return $this->redirect($this->generateUrl('chili_show', array('id' => $chili->getId())));
        }

        return array(
            'entity' => $chili,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Chili entity.
     *
     * @param Request $request
     * @param Chili   $chili
     *
     * @Route("/{id}", name="chili_delete")
     * @Method("DELETE")
     * @Security("chili.isOwner(user) and not chili.isPublic()")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Chili $chili)
    {
        $form = $this->createDeleteForm($chili);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($chili);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('chili_private'));
    }

    /**
     * Creates a form to delete a Chili entity by id.
     *
     * @param Chili $chili
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Chili $chili)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('chili_delete', array('id' => $chili->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Löschen', 'attr' => array('class' => 'btn btn-danger')))
            ->getForm()
        ;
    }
}
