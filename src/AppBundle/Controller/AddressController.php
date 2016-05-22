<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class AddressController
 *
 * @Route("/address")
 * @Security("has_role('ROLE_USER')")
 *
 * @package AppBundle\Controller
 */
class AddressController extends Controller
{
    /**
     * Find all Address entities by Source.
     *
     * @param integer $sourceId
     *
     * @Route("/results/source/{sourceId}", name="address_source_results")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexSourceResultsAction($sourceId)
    {
        $options = array(
            'sourceId' => $sourceId
        );

        $datatable = $this->get('app.datatable.address');
        $datatable->buildDatatable($options);

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        $function = function($qb) use ($sourceId)
        {
            $qb->andWhere("source.id = :id");
            $qb->setParameter('id', $sourceId);
        };

        $query->addWhereAll($function);

        return $query->getResponse();
    }

    /**
     * Find all Address entities by User.
     *
     * @param integer $userId
     *
     * @Route("/results/user/{userId}", name="address_user_results")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexUserResultsAction($userId)
    {
        $options = array(
            'userId' => $userId
        );

        $datatable = $this->get('app.datatable.address');
        $datatable->buildDatatable($options);

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        $function = function($qb) use ($userId)
        {
            $qb->andWhere('user.id = :id');
            $qb->setParameter('id', $userId);
        };

        $query->addWhereAll($function);

        return $query->getResponse();
    }
}
