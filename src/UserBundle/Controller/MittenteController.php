<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\Mittente;
use UserBundle\Entity\LastUpdates;



class MittenteController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;


	/**
     * @SWG\Tag(
     *   name="Mittenti",
     *   description="Tutte le Api dei Mittenti"
     * )
     */


    /**
     * @SWG\Get(
     *     path="/api/mittenti",
     *     summary="Lista mittenti",
     *     tags={"Mittenti"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":147,"limit":"99999","offset":0,"data":{{"id":1,"denominazione":"Conferenza Stato Regioni"}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     */


    /**
     * @Route("/mittenti", name="mittenti")
     * @Method("GET")
     */
    public function mittenteAction(Request $request) {
        //prendo i parametri get
        $limit  = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;

        $repository = $this->getDoctrine()->getRepository('UserBundle:Mittente');
        $mittente = $repository->findAll();
        
        //print_r($mittente);

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => 147,
            "limit" => $limit,
            "offset" => $offset,
            "data" => json_decode($this->serialize($mittente)),
        );
				
				$response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



	/**
     * @SWG\Post(
     *     path="/api/mittenti",
     *     summary="Creazione mittente",
     *     tags={"Mittenti"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"),
     *     @SWG\Response(response=409, description="La denominazione del mittente è già stata utilizzata. Impossibile crearlo."))
     * )
     */	


    /**
     * @Route("/mittenti", name="mittenti_item_create")
     * @Method("POST")
     */
    public function mittentiItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $repository = $em->getRepository('UserBundle:Mittente');
        $mittente = $repository->findOneByDenominazione($data->denominazione);
        //controllo se già esiste il mittente
        if ($mittente) {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il mittente ".$data->denominazione." è già utilizzato. Impossibile crearlo."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $mittente_new = new Mittente();
        $mittente_new->setDenominazione($data->denominazione);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("mittenti");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($mittente_new);
        $em->flush(); //esegue l'update


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => 1,
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($mittente_new)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }



	/**
     * @SWG\Get(
     *     path="/api/mittenti/{id}",
     *     summary="Singolo mittente",
     *     tags={"Mittenti"},
     *     operationId="idMittenti",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id del mittente",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json":{"response":200,"total_results":147,"limit":"99999","offset":0,"data":{{"id":1,"denominazione":"Conferenza Stato Regioni"}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))

     * )
     */

    /**
     * @Route("/mittenti/{id}", name="mittenti_item")
     * @Method("GET")
     */
    public function mittentiItemAction(Request $request, $id) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:Mittente');
        $mittente = $repository->findOneById($id);


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($mittente),
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($mittente)),
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



	/**
     * @SWG\Put(
     *     path="/api/mittenti/{id}",
     *     summary="Salvataggio mittente",
     *     tags={"Mittenti"},
     *     operationId="idMittente",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id del mittente",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
	 *     @SWG\Parameter(
     *         name="mittenti",
     *         in="body",
     *         description="Richiesta",
     *         required=true,
	 *         @SWG\Schema(
	 *				type="array",
     *              @SWG\Items(
     *                 type="object",
     *                 	@SWG\Property(property="id", type="integer"),
	 *					@SWG\Property(property="denominazione", type="string")
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
     * @Route("/mittenti/{id}", name="mittenti_item_save")
     * @Method("PUT")
     */
    public function mittentiItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $repository = $em->getRepository('UserBundle:Mittente');
        $mittente = $repository->findOneById($data->id);

        $mittente->setDenominazione($data->denominazione);


        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("mittenti");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente


        $em->flush(); //esegue l'update

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => 1,
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($mittente)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


	
	/**
     * @SWG\Delete(
     *     path="/api/mittenti/{id}",
     *     summary="Eliminazione mittente",
     *     tags={"Mittenti"},
     *     operationId="idMittenti",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id del mittente",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"),
	 *     @SWG\Response(response=409, description="Il firmatario è associato ad un registro o ad un fascicolo, impossibile eliminarlo.")
     * )
     */  
	
    /**
     * @Route("/mittenti/{id}", name="mittenti_item_delete")
     * @Method("DELETE")
     */
    public function mittentiItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:Mittente');
        $mittente = $repository->findOneById($id);

        $repositoryRegistri = $em->getRepository('UserBundle:Registri');
        $registri = $repositoryRegistri->findOneByIdTitolari($id);

        $repositoryFascicoli = $em->getRepository('UserBundle:Fascicoli');
        $fascicoli = $repositoryFascicoli->findOneByIdTitolari($id);

        if ($registri || $fascicoli) {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il firmatario è associato ad un registro o ad un fascicolo, impossibile eliminarlo."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {

            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("titolari");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente


           // $em->remove($titolario); //delete

            //$em->flush(); //esegue l'update

            $response = new Response($this->serialize($titolario), Response::HTTP_OK);

            return $this->setBaseHeaders($response);
        }
    }







		
    /**
     * @Route("/mittenti", name="mittenti_options")
     * @Method("OPTIONS")
     */
    public function mittenteOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/mittenti/{id2}", name="mittenti_item_options")
     * @Method("OPTIONS")
     */
    public function mittentiItemOptions(Request $request, $id2) {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
