<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Plant;
use AppBundle\Entity\Season;
use AppBundle\Form\SinglePlantType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PlantController
 *
 * @Route("/plant")
 * @Security("has_role('ROLE_USER')")
 *
 * @package AppBundle\Controller
 */
class PlantController extends Controller
{
    /**
     * Find all Plant entities by Season.
     *
     * @param integer $seasonId
     *
     * @Route("/results/{seasonId}", name="plant_results")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexResultsAction($seasonId)
    {
        $options = array(
            'seasonId' => $seasonId
        );

        $datatable = $this->get('app.datatable.plant');
        $datatable->buildDatatable($options);

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        $function = function($qb) use ($seasonId)
        {
            $qb->andWhere('season.id = :id');
            $qb->setParameter('id', $seasonId);
        };

        $query->addWhereAll($function);

        return $query->getResponse();
    }

    /**
     * Displays a form to edit an existing Plant entity.
     *
     * @param Request $request
     * @param Season  $season
     * @param Plant   $plant
     *
     * @Route("/{seasonId}/{id}/edit", name="plant_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @ParamConverter("season", class="AppBundle:Season", options={"mapping": {"seasonId": "id"}})
     * @Security("has_role('ROLE_USER') and season.isOwner(user)")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Season $season, Plant $plant)
    {
        $editForm = $this->createForm(SinglePlantType::class, $plant);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($plant);
            $em->flush();

            return $this->redirectToRoute('season_show', array('id' => $season->getId()));
        }

        return $this->render(':plant:edit.html.twig', array(
            'season' => $season,
            'plant' => $plant,
            'edit_form' => $editForm->createView(),
        ));
    }
}
