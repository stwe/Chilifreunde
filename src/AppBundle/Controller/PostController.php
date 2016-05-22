<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Season;
use AppBundle\Entity\Post;
use AppBundle\Form\PostType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class PostController
 *
 * @Route("/post")
 * @Security("has_role('ROLE_USER')")
 *
 * @package AppBundle\Controller
 */
class PostController extends Controller
{
    /**
     * Creates a new Post entity.
     *
     * @param Request $request
     * @param Season  $season
     *
     * @Route("/{seasonId}/create", name="post_create")
     * @Method("POST")
     * @Template(":post:new.html.twig")
     * @ParamConverter("season", class="AppBundle:Season", options={"mapping": {"seasonId": "id"}})
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request, Season $season)
    {
        $entity = new Post();
        $entity->setSeason($season);

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('season_show', array('id' => $entity->getSeason()->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Post entity.
     *
     * @param Post $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Post $entity)
    {
        $form = $this->createForm(PostType::class, $entity, array(
            'action' => $this->generateUrl('post_create', array('seasonId' => $entity->getSeason()->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Speichern', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Post entity.
     *
     * @param Season $season
     *
     * @Route("/new/{seasonId}", name="post_new")
     * @Method("GET")
     * @Template(":post:new.html.twig")
     * @ParamConverter("season", class="AppBundle:Season", options={"mapping": {"seasonId": "id"}})
     *
     * @return array
     */
    public function newAction(Season $season)
    {
        $entity = new Post();
        $entity->setSeason($season);

        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Post entity.
     *
     * @param Post $post
     *
     * @Route("/show/{id}", name="post_show")
     * @Method("GET")
     * @Template(":post:show.html.twig")
     *
     * @return array
     */
    public function showAction(Post $post)
    {
        return array(
            'entity' => $post,
        );
    }

    /**
     * Displays a form to edit an existing Post entity.
     *
     * @param Post $post
     *
     * @Route("/{id}/edit", name="post_edit")
     * @Method("GET")
     * @Template(":post:edit.html.twig")
     * @Security("post.isOwner(user)")
     *
     * @return array
     */
    public function editAction(Post $post)
    {
        $editForm = $this->createEditForm($post);
        $deleteForm = $this->createDeleteForm($post);

        return array(
            'entity' => $post,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Post entity.
    *
    * @param Post $entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Post $entity)
    {
        $form = $this->createForm(PostType::class, $entity, array(
            'action' => $this->generateUrl('post_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Speichern', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Edits an existing Post entity.
     *
     * @param Request $request
     * @param Post    $post
     *
     * @Route("/{id}", name="post_update")
     * @Method("PUT")
     * @Template(":post:edit.html.twig")
     * @Security("post.isOwner(user)")
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, Post $post)
    {
        $em = $this->getDoctrine()->getManager();

        $originalImages = new ArrayCollection();
        foreach ($post->getImages() as $image) {
            $originalImages->add($image);
        }

        $deleteForm = $this->createDeleteForm($post);
        $editForm = $this->createEditForm($post);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            foreach ($originalImages as $image) {
                if (false === $post->getImages()->contains($image)) {
                    $post->getImages()->removeElement($image);
                    $cacheManager = $this->get('liip_imagine.cache.manager');
                    $cacheManager->resolve('images/'.$image->getFileName(), 'thumbnail_192_x_200');
                    $cacheManager->remove('images/'.$image->getFileName(), 'thumbnail_192_x_200');
                    $em->remove($image);
                }
            }

            $em->persist($post);
            $em->flush();

            return $this->redirect($this->generateUrl('post_show', array('id' => $post->getId())));
        }

        return array(
            'entity' => $post,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Post entity.
     *
     * @param Request $request
     * @param Post    $post
     *
     * @Route("/{id}", name="post_delete")
     * @Method("DELETE")
     * @Security("post.isOwner(user)")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Post $post)
    {
        $form = $this->createDeleteForm($post);
        $form->handleRequest($request);

        $seasonId = $post->getSeason()->getId();

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('season_show', array('id' => $seasonId)));
    }

    /**
     * Creates a form to delete a Post entity by id.
     *
     * @param Post $post
     *
     * @return \Symfony\Component\Form\Form The form
     * @internal param mixed $id The entity id
     *
     */
    private function createDeleteForm(Post $post)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_delete', array('id' => $post->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Löschen', 'attr' => array('class' => 'btn btn-danger')))
            ->getForm()
        ;
    }
}
