<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
            $qb->andWhere("season.id = :id");
            $qb->setParameter('id', $seasonId);
        };

        $query->addWhereAll($function);

        return $query->getResponse();
    }
}
