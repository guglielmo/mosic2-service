<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\Uffici;
use UserBundle\Entity\LastUpdates;



class UfficiController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;

	
    /**
     * @SWG\Tag(
     *   name="Uffici",
     *   description="Tutte le Api degli Uffici"
     * )
     */


    /**
     * @SWG\Get(
     *     path="/api/uffici",
     *     summary="Lista uffici",
     *     tags={"Uffici"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":43,"limit":"99999","offset":0,"data":{{"id":1,"codice":1,"codice_direzione":"","denominazione":"Ufficio I","ordine_ufficio":"10","disattivo_ufficio":"1","solo_delibere":"0"}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     */


    /**
     * @Route("/uffici", name="uffici")
     * @Method("GET")
     */
    public function ufficiAction(Request $request) {
        //prendo i parametri get
        $limit  = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;

        $repository = $this->getDoctrine()->getRepository('UserBundle:Uffici');
        $ufficio = $repository->findAll();

        //print_r($tags);

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($ufficio),
            "limit" => $limit,
            "offset" => $offset,
            "data" => json_decode($this->serialize($ufficio)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
    
    
	/**
     * @SWG\Get(
     *     path="/api/uffici/{id}",
     *     summary="Singolo ufficio",
     *     tags={"Uffici"},
     *     operationId="idUfficio",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id dell'ufficio",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":43,"limit":"99999","offset":0,"data":{{"id":1,"codice":1,"codice_direzione":"","denominazione":"Ufficio I","ordine_ufficio":"10","disattivo_ufficio":"1","solo_delibere":"0"}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))

     * )
     */
	
    /**
     * @Route("/uffici/{id}", name="uffici_item")
     * @Method("GET")
     */
    public function ufficiItemAction(Request $request, $id) {
            
        $repository = $this->getDoctrine()->getRepository('UserBundle:Uffici');
        $ufficio = $repository->findOneById($id);

				
        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($ufficio),
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($ufficio)),
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
	
	
	/**
     * @SWG\Put(
     *     path="/api/uffici/{id}",
     *     summary="Salvataggio ufficio",
     *     tags={"Uffici"},
     *     operationId="idUfficio",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="query",
     *         description="id dell'ufficio",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
	 *     @SWG\Parameter(
     *         name="uffici",
     *         in="body",
     *         description="Richiesta",
     *         required=true,
 	 *         @SWG\Schema(
	 *				type="array",
     *              @SWG\Items(
     *                 type="object",
     *                 	@SWG\Property(property="id", type="integer"),
	 *                 	@SWG\Property(property="codice", type="integer"),
	 *                 	@SWG\Property(property="codice_direzione", type="integer"),
	 *					@SWG\Property(property="denominazione", type="string"),
	 *					@SWG\Property(property="ordine_ufficio", type="integer"),
	 *					@SWG\Property(property="disattivo_ufficio", type="integer"),
	 *					@SWG\Property(property="solo_delibere", type="integer")
     *             )
	 *			),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */
        
    /**
     * @Route("/uffici/{id}", name="uffici_item_save")
     * @Method("PUT")
     */
    public function ufficiItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()),["denominazione"]);
        if ($check != "ok") {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il campo ".$check." e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $repository = $em->getRepository('UserBundle:Uffici');
        $ufficio = $repository->findOneById($data->id);

        $ufficio->setCodice($data->codice);
        $ufficio->setCodiceDirezione($data->codice_direzione);
        $ufficio->setDenominazione($data->denominazione);
        $ufficio->setOrdineUfficio($data->ordine_ufficio);
        $ufficio->setDisattivoUfficio($data->disattivo_ufficio);
        $ufficio->setSoloDelibere($data->solo_delibere);

        
        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("uffici");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente
        
        
        $em->flush(); //esegue l'update


        $response = new Response($this->serialize($ufficio), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
	
	
	/**
     * @SWG\Post(
     *     path="/api/uffici",
     *     summary="Creazione ufficio",
     *     tags={"Uffici"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */
        
        
   /**
     * @Route("/uffici", name="uffici_item_create")
     * @Method("POST")
     */
    public function ufficiItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()),["denominazione"]);
        if ($check != "ok") {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il campo ".$check." e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $ufficio = new Uffici();

        if (isset($data->codice)) {
            $ufficio->setCodice($data->codice);
        } else {
            $ufficio->setCodice(0);
        }
        if (isset($data->codice_direzione)) {
            $ufficio->setCodiceDirezione($data->codice_direzione);
        } else {
            $ufficio->setCodiceDirezione(0);
        }
        $ufficio->setDenominazione($data->denominazione);
        if (isset($data->ordine_ufficio)) {
            $ufficio->setOrdineUfficio($data->ordine_ufficio);
        } else {
            $ufficio->setOrdineUfficio(0);
        }
        if (isset($data->disattivo_ufficio)) {
            $ufficio->setDisattivoUfficio($data->disattivo_ufficio);
        } else {
            $ufficio->setDisattivoUfficio(0);
        }
        if (isset($data->solo_delibere)) {
            $ufficio->setSoloDelibere($data->solo_delibere);
        } else {
            $ufficio->setSoloDelibere(0);
        }


        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("uffici");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($ufficio);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($ufficio), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }

	
	
	/**
     * @SWG\Delete(
     *     path="/api/uffici/{id}",
     *     summary="Eliminazione ufficio",
     *     tags={"Uffici"},
     *     operationId="idTitolario",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id dell'ufficio",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"),
	 *     @SWG\Response(response=409, description="L'ufficio ha utenti associati, impossibile eliminarlo.")
     * )
     */

    
    /**
     * @Route("/uffici/{id}", name="uffici_item_delete")
     * @Method("DELETE")
     */
    public function ufficiItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        
        $repository = $em->getRepository('UserBundle:Uffici');
        $ufficio = $repository->findOneById($id);

        $repositoryUtenti = $em->getRepository('UserBundle:User');
        $utenti = $repositoryUtenti->findOneByIdUffici($ufficio->getId());

        $repositoryTitolari = $em->getRepository('UserBundle:Titolari');
        $titolari = $repositoryTitolari->findOneByIdUffici($ufficio->getId());


        if ($utenti) {
            $response_array = array("error" =>  ["code" => 409, "message" => "L'ufficio ha utenti associati, impossibile eliminarlo."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } elseif ($titolari) {
            $response_array = array("error" =>  ["code" => 409, "message" => "L'ufficio ha titolari associati, impossibile eliminarlo."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {

            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("uffici");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente
           
            $em->remove($ufficio); //delete
            $em->flush(); //esegue l'update 
    
            $response = new Response($this->serialize($ufficio), Response::HTTP_OK);
            return $this->setBaseHeaders($response);
        }
    }
		
        

        
        
        
        
        
        
        
        
        
        
    /**
     * @Route("/uffici", name="uffici_options")
     * @Method("OPTIONS")
     */
    public function ufficiOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
    
    /**
     * @Route("/uffici/{id2}", name="uffici_item_options")
     * @Method("OPTIONS")
     */
    public function ufficiItemOptions(Request $request, $id2) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
