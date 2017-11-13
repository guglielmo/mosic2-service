<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\Titolari;
use UserBundle\Entity\Fascicoli;
use UserBundle\Entity\Registri;
use UserBundle\Entity\LastUpdates;
use UserBundle\Entity\RelTagsTitolari;
use Swagger\Annotations\Swagger;



class TitolariController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;



    /**
     * @SWG\Info(
     *     title="Mosic 2.0 API",
     *     version="1.0"
     * )
     */

    /**
     * @SWG\Tag(
     *   name="Titolari",
     *   description="Tutte le Api dei titolari"
     * )
     */


    /**
     * @SWG\Get(
     *     path="/api/titolari",
     *     summary="Lista titolari",
     *     tags={"Titolari"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"id":1,"codice":0,"denominazione":"Documenti di seduta","descrizione":"Telex, Appunto generale, passi, etc...","id_uffici":2}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     */

    /**
     * @Route("/titolari", name="titolari")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_TITOLARI')")
     */
    public function titolariAction(Request $request) {
        //prendo i parametri get
        $limit  = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;

        $sortBy  = ($request->query->get('sort_by') != "") ? $request->query->get('sort_by') : 'codice';
        $sortType = ($request->query->get('sort_order') != "") ? $request->query->get('sort_order') : "ASC";

			
        $repository = $this->getDoctrine()->getRepository('UserBundle:Titolari');
        $titolari = $repository->listaTitolari($limit, $offset, $sortBy, $sortType);

        $serialize = json_decode($this->serialize($titolari));

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($titolari),
            "limit" => $limit,
            "offset" => $offset,
            "data" => $serialize,
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


    /**
     * @SWG\Get(
     *     path="/api/titolari/{id}",
     *     summary="Singolo titolario",
     *     tags={"Titolari"},
     *     operationId="idTitolario",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id del titolario",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
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
     * @Route("/titolari/{id}", name="titolari_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_TITOLARI')")
     */
    public function titolariItemAction(Request $request, $id) {
            
        $repository = $this->getDoctrine()->getRepository('UserBundle:Titolari');
        $titolario = $repository->findOneById($id);

				
        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($titolario),
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($titolario)),
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


    /**
     * @SWG\Put(
     *     path="/api/titolari/{id}",
     *     summary="Salvataggio titolario",
     *     tags={"Titolari"},
     *     operationId="idTitolario",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="query",
     *         description="id del titolario",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
	 *     @SWG\Parameter(
     *         name="titolari",
     *         in="body",
     *         description="Richiesta",
     *         required=true,
  	 *         @SWG\Schema(
	 *				type="array",
     *              @SWG\Items(
     *                 type="object",
     *                 	@SWG\Property(property="id", type="integer"),
	 *                 	@SWG\Property(property="codice", type="integer"),
	 *					@SWG\Property(property="denominazione", type="string"),
	 *					@SWG\Property(property="descrizione", type="string"),
	 *					@SWG\Property(property="id_uffici", type="array")
     *             )
	 *			),
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
     * @Route("/titolari/{id}", name="titolari_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_TITOLARI')")
     */
    public function titolariItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()),["codice","denominazione","descrizione","id_uffici"]);
        if ($check != "ok") {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il campo ".$check." e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $repository = $em->getRepository('UserBundle:Titolari');
        $titolario = $repository->findOneById($data->id);
        
        $titolario->setCodice($data->codice);
        $titolario->setDenominazione($data->denominazione);
        $titolario->setDescrizione($data->descrizione);
		$titolario->setIdUffici($data->id_uffici);



        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("titolari");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente
        
        
        $em->flush(); //esegue l'update


        $response = new Response($this->serialize($titolario), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



    /**
     * @SWG\Post(
     *     path="/api/titolari",
     *     summary="Creazione titolario",
     *     tags={"Titolari"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */


   /**
     * @Route("/titolari", name="titolari_item_create")
     * @Method("POST")
    * @Security("is_granted('ROLE_CREATE_TITOLARI')")
     */
    public function titolariItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()),["codice","denominazione","descrizione","id_uffici"]);
        if ($check != "ok") {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il campo ".$check." e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $titolario = new Titolari();
        
        $titolario->setCodice($data->codice);
        $titolario->setDenominazione($data->denominazione);
        $titolario->setDescrizione($data->descrizione);
		$titolario->setIdUffici($data->id_uffici);


        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("titolari");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente
        
        $em->persist($titolario);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($titolario), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



    /**
     * @SWG\Delete(
     *     path="/api/titolari/{id}",
     *     summary="Eliminazione titolario",
     *     tags={"Titolari"},
     *     operationId="idTitolario",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id del titolario",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */

    /**
     * @Route("/titolari/{id}", name="titolari_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_TITOLARI')")
     */
    public function titolariItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        
        $repository = $em->getRepository('UserBundle:Titolari');
        $titolario = $repository->findOneById($id);
        
        $repositoryRegistri = $em->getRepository('UserBundle:Registri');
        $registri = $repositoryRegistri->findOneByIdTitolari($id);
        
        $repositoryFascicoli = $em->getRepository('UserBundle:Fascicoli');
        $fascicoli = $repositoryFascicoli->findOneByIdTitolari($id);
        
        if ($registri || $fascicoli) {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il titolario non e' vuoto, impossibile eliminarlo."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {
            
            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("titolari");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

           
            $em->remove($titolario); //delete
    
            $em->flush(); //esegue l'update 
    
            $response = new Response($this->serialize($titolario), Response::HTTP_OK);
    
            return $this->setBaseHeaders($response);
        }
    }
		
        

        
        
        
        
        
        
        
        
        
        
    /**
     * @Route("/titolari", name="titolari_options")
     * @Method("OPTIONS")
     */
    public function titolariOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
    
    /**
     * @Route("/titolari/{id2}", name="titolari_item_options")
     * @Method("OPTIONS")
     */
    public function titolariItemOptions(Request $request, $id2) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
