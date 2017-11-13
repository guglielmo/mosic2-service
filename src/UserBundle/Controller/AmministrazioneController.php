<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\Amministrazione;
use UserBundle\Entity\LastUpdates;
use UserBundle\Entity\RelAmministrazioniRegistri;
use UserBundle\Entity\RelAmministrazioniFascicoli;



class AmministrazioneController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;

	
    /**
     * @SWG\Tag(
     *   name="Amministrazioni",
     *   description="Tutte le Api delle amministrazioni"
     * )
     */


    /**
     * @SWG\Get(
     *     path="/api/amministrazioni",
     *     summary="Lista amministrazioni",
     *     tags={"Amministrazioni"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":185,"limit":"99999","offset":0,"data":{{"id":1,"codice":"1","denominazione"
:"Presidenza del Consiglio dei Ministri"}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     */

    /**
     * @Route("/amministrazioni", name="amministrazioni")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_AMMINISTRAZIONI')")
     */
    public function amministrazioneAction(Request $request) {
        //prendo i parametri get
        $limit  = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;

        $repository = $this->getDoctrine()->getRepository('UserBundle:Amministrazione');
        $amministrazione = $repository->findAll();
        
        
        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($amministrazione),
            "limit" => $limit,
            "offset" => $offset,
            "data" => json_decode($this->serialize($amministrazione)),
        );

        //$response = new Response($this->serialize('example amministrazionei.'), Response::HTTP_OK);
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



	
	/**
     * @SWG\Get(
     *     path="/api/amministrazioni/{id}",
     *     summary="Singola amministrazione",
     *     tags={"Amministrazioni"},
     *     operationId="idAmministrazione",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id dell'amministrazione",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":185,"limit":"99999","offset":0,"data":{{"id":1,"codice":"1","denominazione"
:"Presidenza del Consiglio dei Ministri"}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))

     * )
     */
	

    /**
     * @Route("/amministrazioni/{id}", name="amministrazioni_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_AMMINISTRAZIONI')")
     */
    public function amministrazioniItemAction(Request $request, $id) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:Amministrazione');
        $amministrazione = $repository->findOneById($id);


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($amministrazione),
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($amministrazione)),
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


	/**
     * @SWG\Post(
     *     path="/api/amministrazioni",
     *     summary="Creazione amministrazione",
     *     tags={"Amministrazioni"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */


	/**
     * @Route("/amministrazioni", name="amministrazioni_item_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREATE_AMMINISTRAZIONI')")
     */
    public function amministrazioneItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()),["denominazione"]);
        if ($check != "ok") {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il campo ".$check." e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $repository = $em->getRepository('UserBundle:Amministrazione');
        $amministrazione = $repository->findOneByDenominazione($data->denominazione);
        //controllo se già esiste l'amministrazione
        if ($amministrazione) {
            $response_array = array("error" =>  ["code" => 409, "message" => "L amministrazione ".$data->denominazione." è già utilizzata"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }


        $amministrazione_new = new Amministrazione();
        $amministrazione_new->setDenominazione($data->denominazione);
        $amministrazione_new->setCodice("xxx");//lo aggiornerò dopo con l'id dell'elemento creato

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("amministrazioni");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($amministrazione_new);
        $em->flush(); //esegue l'update

        //aggiorno il codice come l'id
        $id_creato = $amministrazione_new->getId();
        $amministrazione_new->setCodice($id_creato + 3); //per far coincidere codice e id
        $em->persist($amministrazione_new);
        $em->flush(); //esegue l'update


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => 1,
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($amministrazione_new)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
	
	
	/**
     * @SWG\Put(
     *     path="/api/amministrazioni/{id}",
     *     summary="Salvataggio amministrazione",
     *     tags={"Amministrazioni"},
     *     operationId="idAmministrazione",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id dell'amministrazione",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
	 *     @SWG\Parameter(
     *         name="amministrazioni",
     *         in="body",
     *         description="Richiesta",
     *         required=true,
	 *         @SWG\Schema(
	 *				type="array",
     *              @SWG\Items(
     *                 type="object",
     *                 	@SWG\Property(property="id", type="integer"),
	 *                 	@SWG\Property(property="codice", type="integer"),
	 *					@SWG\Property(property="denominazione", type="string")
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
     * @Route("/amministrazioni/{id}", name="amministrazioni_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_AMMINISTRAZIONI')")
     */
    public function amministrazioniItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()),["denominazione"]);
        if ($check != "ok") {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il campo ".$check." e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }


        $repository = $em->getRepository('UserBundle:Amministrazione');
        $amministrazione = $repository->findOneById($data->id);

        $amministrazione->setCodice($data->codice);
        $amministrazione->setDenominazione($data->denominazione);


        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("amministrazioni");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente


        $em->flush(); //esegue l'update

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => 1,
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($amministrazione)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


	/**
     * @SWG\Delete(
     *     path="/api/amministrazioni/{id}",
     *     summary="Eliminazione amministrazione",
     *     tags={"Amministrazioni"},
     *     operationId="idAmministrazione",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id dell'amministrazione",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"),
	 *     @SWG\Response(response=409, description="L'Amministrazione è associata ad un fasciolo o ad un registro, impossibile eliminarla.")
     * )
     */


    /**
     * @Route("/amministrazioni/{id}", name="amministrazioni_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_AMMINISTRAZIONI')")
     */
    public function amministrazioniItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:Amministrazione');
        $amministrazione = $repository->findOneById($id);

        $repositoryRegistri = $em->getRepository('UserBundle:RelAmministrazioniRegistri');
        $registri = $repositoryRegistri->findOneByIdAmministrazioni($id);

        $repositoryFascicoli = $em->getRepository('UserBundle:RelAmministrazioniFascicoli');
        $fascicoli = $repositoryFascicoli->findOneByIdAmministrazioni($id);

        if ($registri || $fascicoli) {
            $response_array = array("error" =>  ["code" => 409, "message" => "L'Amministrazione è associata ad un fasciolo o ad un registro, impossibile eliminarla."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {

            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("amministrazioni");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente


            $em->remove($amministrazione); //delete

            $em->flush(); //esegue l'update

            $response = new Response($this->serialize($amministrazione), Response::HTTP_OK);

            return $this->setBaseHeaders($response);
        }
    }









    /**
     * @Route("/amministrazioni", name="amministrazione_options")
     * @Method("OPTIONS")
     */
    public function amministrazioneOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/amministrazioni/{id2}", name="amministrazioni_item_options")
     * @Method("OPTIONS")
     */
    public function amministrazioniItemOptions(Request $request, $id2) {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
