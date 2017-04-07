<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\LastUpdates;



class LastUpdatesController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;

    /**
     * @Route("/lastupdates", name="lastupdates")
     * @Method("GET")
     */
    public function lastupdatesAction(Request $request) {
			
        $repository = $this->getDoctrine()->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repository->findAll();

        //converte i risultati in json
        $serialize = $this->serialize($lastUpdates);
        
        //funzione per formattare le date del json
        $serialize = $this->formatDateJsonArrayCustomLastUpdates(json_decode($serialize), array('last_update'));
        $array_updates = array();
        foreach ($serialize as $item) {
            $array_updates[$item->tabella] = $item->last_update;
        }
        $response_array = array(
            "data" => $array_updates,
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
    
    
        
        
    /**
     * @Route("/lastupdates", name="lastupdates_options")
     * @Method("OPTIONS")
     */
    public function lastupdatesOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
    
}
