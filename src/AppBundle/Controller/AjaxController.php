<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Chili;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class AjaxController
 *
 * @Security("has_role('ROLE_USER')")
 *
 * @package AppBundle\Controller
 */
class AjaxController extends Controller
{
    /**
     * Get Chilis to create a Season.
     *
     * @param Request $request
     *
     * @Route("/season/get-chilis", name="get_chilis_to_create_season")
     *
     * @return JsonResponse|Response
     */
    public function getChilisToCreateSeasonAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $id = $request->request->get('species_id');
            $public = $this->strToBool($request->request->get('public'));

            if (is_bool($public)) {
                $em = $this->getDoctrine()->getManager();

                $species = null;
                if ('' != $id) {
                    $species = $em->getRepository('AppBundle:Species')->find($id);
                }

                $result = array();
                $chilis = $em->getRepository('AppBundle:Chili')->findAllChilisBySpeciesAndPublic($public, $species, $this->getUser());

                /** @var \AppBundle\Entity\Chili $chili */
                foreach ($chilis as $chili) {
                    $result[$chili->getName()] = $chili->getId();
                }

                return new JsonResponse($result);
            }
        }

        return new Response('Bad request.', 400);
    }

    /**
     * Get Chilis for editing Plant.
     *
     * @param Request $request
     *
     * @Route("/get_chilis", name="get_chilis")
     *
     * @return Response
     */
    public function getChilisAction(Request $request)
    {
        $value = $request->get('q');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Chili');
        $chilis = $repository->findChilis($value, $this->getUser());

        $json = array('data' => array());

        foreach ($chilis as $chili) {
            $species = 'unbekannte Art';
            if ($chili->getSpecies()) {
                $species = $chili->getSpecies()->getName();
            }

            $values = array(
                'id' => $chili->getId(),
                'name' => $chili->getName(),
                'species' => $species
            );

            array_push($json, $values);
        }

        $response = new Response();
        $response->setContent(json_encode($json));

        return $response;
    }

    /**
     * Get all Chilis to sync.
     *
     * @Route("/sync_chilis", name="sync_chilis")
     *
     * @return JsonResponse
     */
    public function getNotSyncedChilisAction()
    {

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Chili');
        $chilis = $repository->findAllChilis();

        return new JsonResponse($chilis);
    }

    /**
     * String to boolean.
     *
     * @param string $str
     *
     * @return bool
     * @throws \Exception
     */
    private function strToBool($str)
    {
        if ($str === 'true') {
            return true;
        } else if ($str === 'false') {
            return false;
        } else {
            throw new \Exception('strToBool(): Cannot convert string to boolean, expected string "true" or "false".');
        }
    }
}
