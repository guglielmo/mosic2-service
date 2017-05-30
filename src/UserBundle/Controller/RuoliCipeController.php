<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\RuoliCipe;
use UserBundle\Entity\LastUpdates;



class RuoliCipeController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;

    /**
     * @Route("/ruoli_cipe", name="ruoli_cipe")
     * @Method("GET")
     */
    public function ruoli_cipeAction(Request $request) {
        //prendo i parametri get
        $limit  = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;

        $repository = $this->getDoctrine()->getRepository('UserBundle:RuoliCipe');
        $ruoli_cipe = $repository->findAll();

        //print_r($tags);

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($ruoli_cipe),
            "limit" => $limit,
            "offset" => $offset,
            "data" => json_decode($this->serialize($ruoli_cipe)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
    
    
    /**
     * @Route("/ruoli_cipe/{id}", name="ruoli_cipe_item")
     * @Method("GET")
     */
    public function ruoli_cipeItemAction(Request $request, $id) {
            
        $repository = $this->getDoctrine()->getRepository('UserBundle:RuoliCipe');
        $ruoli_cipe = $repository->findOneById($id);

				
        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($ruoli_cipe),
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($ruoli_cipe)),
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
		
        
    /**
     * @Route("/ruoli_cipe/{id}", name="ruoli_cipe_item_save")
     * @Method("PUT")
     */
    public function ruoli_cipeItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        
        $repository = $em->getRepository('UserBundle:RuoliCipe');
        $ruoli_cipe = $repository->findOneById($data->id);

        $ruoli_cipe->setCodice($data->codice);
        $ruoli_cipe->setDenominazione($data->denominazione);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("ruoli_cipe");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente
        
        
        $em->flush(); //esegue l'update


        $response = new Response($this->serialize($ruoli_cipe), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
		
        
        
   /**
     * @Route("/ruoli_cipe", name="ruoli_cipe_item_create")
     * @Method("POST")
     */
    public function ruoli_cipeItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $ruoli_cipe = new RuoliCipe();

        $ruoli_cipe->setCodice($data->codice);
        $ruoli_cipe->setDenominazione($data->denominazione);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("ruoli_cipe");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($ruoli_cipe);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($ruoli_cipe), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }

    
    /**
     * @Route("/ruoli_cipe/{id}", name="ruoli_cipe_item_delete")
     * @Method("DELETE")
     */
    public function ruoli_cipeItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        
        $repository = $em->getRepository('UserBundle:RuoliCipe');
        $ruoli_cipe = $repository->findOneById($id);

        $repositoryUtenti = $em->getRepository('UserBundle:User');
        $utenti = $repositoryUtenti->findOneByIdRuoliCipe($ruoli_cipe->getId());
        

        if ($utenti) {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il ruolo non e' vuoto, impossibile eliminarlo."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {
            
            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("ruoli_cipe");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

           
            $em->remove($ruoli_cipe); //delete
    
            $em->flush(); //esegue l'update 
    
            $response = new Response($this->serialize($ruoli_cipe), Response::HTTP_OK);
    
            return $this->setBaseHeaders($response);
        }
    }
		
        

        
        
        
        
        
        
        
        
        
        
    /**
     * @Route("/ruoli_cipe", name="ruoli_cipe_options")
     * @Method("OPTIONS")
     */
    public function ruoli_cipeOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
    
    /**
     * @Route("/ruoli_cipe/{id2}", name="ruoli_cipe_item_options")
     * @Method("OPTIONS")
     */
    public function ruoli_cipeItemOptions(Request $request, $id2) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
