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
     * @SWG\Tag(
     *   name="Ruoli",
     *   description="Tutte le Api dei Ruoli interni all'Ufficio"
     * )
     */


    /**
     * @SWG\Get(
     *     path="/api/ruoli_cipe",
     *     summary="Lista Ruoli interni all'Ufficio",
     *     tags={"Ruoli"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":8,"limit":"99999","offset":0,"data":{{"id":1,"codice":0,"denominazione":""}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     */

    /**
     * @Route("/ruoli_cipe", name="ruoli_cipe")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_RUOLI_CIPE')")
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
     * @SWG\Get(
     *     path="/api/ruoli_cipe/{id}",
     *     summary="Singolo Ruolo",
     *     tags={"Ruoli"},
     *     operationId="idRuoli",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id del ruolo",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":8,"limit":"99999","offset":0,"data":{{"id":1,"codice":0,"denominazione":""}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))

     * )
     */

    
    /**
     * @Route("/ruoli_cipe/{id}", name="ruoli_cipe_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_RUOLI_CIPE')")
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
     * @SWG\Put(
     *     path="/api/ruoli_cipe/{id}",
     *     summary="Salvataggio ruolo",
     *     tags={"Ruoli"},
     *     operationId="idRuoli",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="query",
     *         description="id del ruolo",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
	 *     @SWG\Parameter(
     *         name="ruoli",
     *         in="body",
     *         description="Richiesta",
     *         required=true,
	 *       	schema={}
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"id":1,"codice":0,"denominazione":"Documenti di seduta","descrizione":"Telex, Appunto generale, passi, etc...","id_uffici":2}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */		
        
    /**
     * @Route("/ruoli_cipe/{id}", name="ruoli_cipe_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_RUOLI_CIPE')")
     */
    public function ruoli_cipeItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()),["codice","denominazione"]);
        if ($check != "ok") {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il campo ".$check." e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

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
     * @SWG\Post(
     *     path="/api/ruoli_cipe",
     *     summary="Creazione ruolo",
     *     tags={"Ruoli"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */	

        
   /**
     * @Route("/ruoli_cipe", name="ruoli_cipe_item_create")
     * @Method("POST")
    * @Security("is_granted('ROLE_CREATE_RUOLI_CIPE')")
     */
    public function ruoli_cipeItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()),["codice","denominazione"]);
        if ($check != "ok") {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il campo ".$check." e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

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
     * @SWG\Delete(
     *     path="/api/ruoli_cipe/{id}",
     *     summary="Eliminazione ruolo",
     *     tags={"Ruoli"},
     *     operationId="idRuoli",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id del ruolo",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"),
	 *     @SWG\Response(response=409, description="Il ruolo è associato ad un utente, impossibile eliminarlo.")
     * )
     */  
    
    /**
     * @Route("/ruoli_cipe/{id}", name="ruoli_cipe_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_RUOLI_CIPE')")
     */
    public function ruoli_cipeItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        
        $repository = $em->getRepository('UserBundle:RuoliCipe');
        $ruoli_cipe = $repository->findOneById($id);

        $repositoryUtenti = $em->getRepository('UserBundle:User');
        $utenti = $repositoryUtenti->findOneByIdRuoliCipe($ruoli_cipe->getId());
        

        if ($utenti) {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il ruolo è associato ad un utente, impossibile eliminarlo."]);
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
