<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\Firmatari;
use UserBundle\Entity\Fascicoli;
use UserBundle\Entity\Registri;
use UserBundle\Entity\LastUpdates;



class FirmatariController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;


	/**
     * @SWG\Tag(
     *   name="Firmatari",
     *   description="Tutte le Api dei Firmatari"
     * )
     */


    /**
     * @SWG\Get(
     *     path="/api/firmatari",
     *     summary="Lista firmatari",
     *     tags={"Firmatari"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":107,"limit":"99999","offset":0,"data":{{"id":2,"chiave":0,"tipo":2,"denominazione"
:"Gobbo","denominazione_estesa":"Fabio Gobbo","disattivato":1}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     */

    /**
     * @Route("/firmatari", name="firmatari")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_FIRMATARI')")
     */
    public function firmatariAction(Request $request) {
        //prendo i parametri get
        $limit  = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;

        $sortBy  = ($request->query->get('sort_by') != "") ? $request->query->get('sort_by') : 'id';
        $sortType = ($request->query->get('sort_order') != "") ? $request->query->get('sort_order') : "ASC";

			
        $repository = $this->getDoctrine()->getRepository('UserBundle:Firmatari');
        $firmatari = $repository->listaFirmatari($limit, $offset, $sortBy, $sortType);

        
        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($firmatari),
            "limit" => $limit,
            "offset" => $offset,
            "data" => json_decode($this->serialize($firmatari)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
    


	/**
     * @SWG\Get(
     *     path="/api/firmatari/{id}",
     *     summary="Singolo firmatario",
     *     tags={"Firmatari"},
     *     operationId="idFirmatari",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id del firmatario",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":107,"limit":"99999","offset":0,"data":{{"id":2,"chiave":0,"tipo":2,"denominazione"
:"Gobbo","denominazione_estesa":"Fabio Gobbo","disattivato":1}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))

     * )
     */
    
    /**
     * @Route("/firmatari/{id}", name="firmatari_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_FIRMATARI')")
     */
    public function firmatariItemAction(Request $request, $id) {
            
        $repository = $this->getDoctrine()->getRepository('UserBundle:Firmatari');
        $firmatario = $repository->findOneById($id);

				
        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($firmatario),
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($firmatario)),
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



	/**
     * @SWG\Put(
     *     path="/api/firmatari/{id}",
     *     summary="Salvataggio firmatario",
     *     tags={"Firmatari"},
     *     operationId="idFirmatario",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="query",
     *         description="id del firmatario",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
	 *     @SWG\Parameter(
     *         name="firmatari",
     *         in="body",
     *         description="Richiesta",
     *         required=true,
 	 *         @SWG\Schema(
	 *				type="array",
     *              @SWG\Items(
     *                 type="object",
     *                 	@SWG\Property(property="id", type="integer"),
	 *                 	@SWG\Property(property="chiave", type="integer"),
	 *					@SWG\Property(property="tipo", type="integer"),
	 *					@SWG\Property(property="denominazione", type="string"),
	 *					@SWG\Property(property="denominazione_estesa", type="string"),
	 *					@SWG\Property(property="disattivato", type="integer")
     *     			)
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
     * @Route("/firmatari/{id}", name="firmatari_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_FIRMATARI')")
     */
    public function firmatariItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        
        $repository = $em->getRepository('UserBundle:Firmatari');
        $firmatario = $repository->findOneById($data->id);
        
        $firmatario->setChiave($data->chiave);
        $firmatario->setDenominazione($data->denominazione);
        $firmatario->setDenominazioneEstesa($data->denominazione_estesa);
        $firmatario->setTipo($data->tipo);
        $firmatario->setDisattivato($data->disattivato);

        
        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("firmatari");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente
        
        
        $em->flush(); //esegue l'update


        $response = new Response($this->serialize($firmatario), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
		
        


	/**
     * @SWG\Post(
     *     path="/api/firmatari",
     *     summary="Creazione firmatario",
     *     tags={"Firmatari"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */	
        
   /**
     * @Route("/firmatari", name="firmatari_item_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREATE_FIRMATARI')")
     */
    public function firmatariItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()),["denominazione","denominazione_estesa","tipo"]);
        if ($check != "ok") {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il campo ".$check." e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $firmatario = new Firmatari();

        if (isset($data->chiave)) {
            $firmatario->setChiave($data->chiave);
        } else {
            $firmatario->setChiave(0);
        }
        $firmatario->setDenominazione($data->denominazione);
        $firmatario->setDenominazioneEstesa($data->denominazione_estesa);
        $firmatario->setTipo($data->tipo);
        if (isset($data->disattivato)) {
            $firmatario->setDisattivato($data->disattivato);
        } else {
            $firmatario->setDisattivato(0);
        }

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("firmatari");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente
        
        $em->persist($firmatario);
        $em->flush(); //esegue l'update 

        $response = new Response($this->serialize($firmatario), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
    



	
 	/**
     * @SWG\Delete(
     *     path="/api/firmatari/{id}",
     *     summary="Eliminazione firmatario",
     *     tags={"Firmatari"},
     *     operationId="idFirmatario",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id del firmatario",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"),
	 *     @SWG\Response(response=409, description="Il firmatario è associato ad una Delibero a ad un Cipe, impossibile eliminarlo.")
     * )
     */  
    
    /**
     * @Route("/firmatari/{id}", name="firmatari_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_FIRMATARI')")
     */
    public function firmatariItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        
        $repository = $em->getRepository('UserBundle:Firmatari');
        $firmatario = $repository->findOneById($id);
        
        $repositoryCipe = $em->getRepository('UserBundle:Cipe');
        $cipeP = $repositoryCipe->findOneByIdPresidente($id);
        $cipeS = $repositoryCipe->findOneByIdSegretario($id);
        $cipeD = $repositoryCipe->findOneByIdDirettore($id);

        $repositoryFirmatariDelibere = $em->getRepository('UserBundle:RelFirmatariDelibere');
        $FirmatariDelibere = $repositoryFirmatariDelibere->findOneByIdFirmatari($id);

        if ($cipeP || $cipeS || $cipeD || $FirmatariDelibere) {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il firmatario è associato ad una Delibero a ad un Cipe, impossibile eliminarlo."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {
            
            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("firmatari");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

           
            $em->remove($firmatario); //delete
    
            $em->flush(); //esegue l'update 
    
            $response = new Response($this->serialize($firmatario), Response::HTTP_OK);
    
            return $this->setBaseHeaders($response);
        }
    }
		
        

        
        
        
        
        
        
        
        
        
        
    /**
     * @Route("/firmatari", name="firmatari_options")
     * @Method("OPTIONS")
     */
    public function firmatariOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
    
    /**
     * @Route("/firmatari/{id2}", name="firmatari_item_options")
     * @Method("OPTIONS")
     */
    public function firmatariItemOptions(Request $request, $id2) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
