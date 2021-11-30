<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\Tags;
use UserBundle\Entity\LastUpdates;



class TagsController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;


	/**
     * @SWG\Tag(
     *   name="Etichette",
     *   description="Tutte le Api delle etichette"
     * )
     */


    /**
     * @SWG\Get(
     *     path="/api/tags",
     *     summary="Lista etichette",
     *     tags={"Etichette"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":22,"limit":"99999","offset":0,"data":{{"id":2,"denominazione":"secondo tags"}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     */

    /**
     * @Route("/tags", name="tags")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_TAGS')")
     */
    public function tagsAction(Request $request) {
        //prendo i parametri get
        $limit  = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;

        $repository = $this->getDoctrine()->getRepository('UserBundle:Tags');
        $tags = $repository->findAll();
        
        //print_r($tags);

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($tags),
            "limit" => $limit,
            "offset" => $offset,
            "data" => json_decode($this->serialize($tags)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


	/**
     * @SWG\Get(
     *     path="/api/tags/{id}",
     *     summary="Singola etichetta",
     *     tags={"Etichette"},
     *     operationId="idTag",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id dell'etichetta",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json":{"response":200,"total_results":22,"limit":"99999","offset":0,"data":{{"id":2,"denominazione":"secondo tags"}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))

     * )
     */

    /**
     * @Route("/tags/{id}", name="tags_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_TAGS')")
     */
    public function tagsItemAction(Request $request, $id) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:Tags');
        $tag = $repository->findOneById($id);


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($tag),
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($tag)),
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }




	/**
     * @SWG\Post(
     *     path="/api/tags",
     *     summary="Creazione etichetta",
     *     tags={"Etichette"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */	

    /**
     * @Route("/tags", name="tags_item_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREATE_TAGS')")
     */
    public function tagsItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()),["denominazione"]);
        if ($check != "ok") {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il campo ".$check." e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $repository = $em->getRepository('UserBundle:Tags');
        $tag = $repository->findOneByDenominazione($data->denominazione);

        //controllo se già esiste il tag
        if ($tag) {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il tag ".$data->denominazione." è già utilizzato"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $tag_new = new Tags();
        //MODIFICA MOSIC 3.0 del 17/06/2020
        $tag_new->setDisattivo(0);
        if (isset($data->disattivo) && $data->disattivo == 1) {
            $tag_new->setDisattivo($data->disattivo);
        }
        $tag_new->setDenominazione($data->denominazione);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("tags");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($tag_new);
        $em->flush(); //esegue l'update


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => 1,
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($tag_new)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }




	/**
     * @SWG\Put(
     *     path="/api/tags/{id}",
     *     summary="Salvataggio etichetta",
     *     tags={"Etichette"},
     *     operationId="idEtichetta",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="query",
     *         description="id dell'etichetta",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
	 *     @SWG\Parameter(
     *         name="etichette",
     *         in="body",
     *         description="Richiesta",
 	 *         @SWG\Schema(
	 *				type="array",
     *              @SWG\Items(
     *                 type="object",
     *                 	@SWG\Property(property="id", type="integer"),
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
     * @Route("/tags/{id}", name="tags_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_TAGS')")
     */
    public function tagsItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()),["denominazione"]);
        if ($check != "ok") {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il campo ".$check." e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $repository = $em->getRepository('UserBundle:Tags');
        $tags = $repository->findOneById($data->id);

        $tags->setDenominazione($data->denominazione);
        //MODIFICA MOSIC 3.0 del 17/06/2020
        $tags->setDisattivo(0);
        if (isset($data->disattivo) && $data->disattivo == 1) {
            $tags->setDisattivo($data->disattivo);
        }

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("tags");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($tags), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



	/**
     * @SWG\Delete(
     *     path="/api/tags/{id}",
     *     summary="Eliminazione etichetta",
     *     tags={"Etichette"},
     *     operationId="idEtichetta",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id dell'etichetta",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"),
	 *     @SWG\Response(response=409, description="Etichetta è associata ad un registro o ad un fascicolo o ad una delibera, impossibile eliminarla.")
     * )
     */  


    /**
     * @Route("/tags/{id}", name="tags_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_TAGS')")
     */
    public function tagsItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:Tags');
        $tags = $repository->findOneById($id);

        $repository_rel_fascicoli = $em->getRepository('UserBundle:RelTagsFascicoli');
        $relTagsFascicoli_delete = $repository_rel_fascicoli->findByIdTags($id);//ricavo tutte le relazioni con l'id del registro

        $repository_rel_registri = $em->getRepository('UserBundle:RelTagsRegistri');
        $relTagsRegistri_delete = $repository_rel_registri->findByIdTags($id);//ricavo tutte le relazioni con l'id del registro

        $repository_rel_delibere = $em->getRepository('UserBundle:RelTagsDelibere');
        $relTagsDelibere_delete = $repository_rel_delibere->findByIdTags($id);//ricavo tutte le relazioni con l'id del registro


        if ($relTagsFascicoli_delete || $relTagsRegistri_delete || $relTagsDelibere_delete) {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il tags non e' vuoto, impossibile eliminarlo."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {

            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("tags");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente


            $em->remove($tags); //delete
            $em->flush(); //esegue l'update

            $response = new Response($this->serialize($tags), Response::HTTP_OK);
            return $this->setBaseHeaders($response);
        }
    }
    
    
    
    
    


		
    /**
     * @Route("/tags", name="tags_options")
     * @Method("OPTIONS")
     */
    public function tagsOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/tags/{id2}", name="tags_item_options")
     * @Method("OPTIONS")
     */
    public function tagsItemOptions(Request $request, $id2) {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
