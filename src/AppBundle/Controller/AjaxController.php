<?php

namespace AppBundle\Controller;

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
     * Get Chilis by Species.
     *
     * @param Request $request
     *
     * @Route("/get-chilis", name="get_chilis")
     *
     * @return JsonResponse|Response
     */
    public function ajaxAction(Request $request)
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
