<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\PreCipeOdg;
use UserBundle\Entity\PreCipe;
use UserBundle\Entity\LastUpdates;



class PreCipeOdgController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;


	/**
     * @SWG\Tag(
     *   name="PreCipe OdG",
     *   description="Cancellazione dell'Ordine del Giorno di un PreCipe"
     * )
     */

    /**
     * @Route("/precipeodg", name="precipeodg")
     * @Method("GET")
     */
    public function precipeodgAction(Request $request) {
        //prendo i parametri get
        $limit  = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;
        $sortBy  = ($request->query->get('sort_by') != "") ? $request->query->get('sort_by') : 'id';
        $sortType = ($request->query->get('sort_order') != "") ? $request->query->get('sort_order') : "DESC";

        $repository = $this->getDoctrine()->getRepository('UserBundle:PreCipeOdg');
        $precipeodg = $repository->listaPrecipeOdg($limit, $offset, $sortBy, $sortType);
        //print_r($tags);

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($precipeodg),
            "limit" => $limit,
            "offset" => $offset,
            "data" => json_decode($this->serialize($precipeodg)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
    
    
    /**
     * @Route("/precipeodg/{id}", name="precipeodg_item")
     * @Method("GET")
     */
    public function precipeodgItemAction(Request $request, $id) {
            
        $repository = $this->getDoctrine()->getRepository('UserBundle:PreCipeOdg');
        $precipeodg = $repository->findOneById($id);

				
        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($precipeodg),
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($precipeodg)),
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
		
        
    /**
     * @Route("/precipeodg/{id}", name="precipeodg_item_save")
     * @Method("PUT")
     */
    public function precipeodgItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        
        $repository = $em->getRepository('UserBundle:PreCipeOdg');
        $precipeodg = $repository->findOneById($data->id);

        $precipeodg->setIdPrecipe($data->id_precipe);
        $precipeodg->setProgressivo($data->progressivo);
        $precipeodg->setIdTitolari($data->id_titolari);
        $precipeodg->setIdFascicoli($data->id_fascicoli);
        $precipeodg->setIdArgomenti($data->id_argomenti);
        $precipeodg->setIdUffici($data->id_uffici);
        $precipeodg->setNumeroOdg($data->numero_odg);
        $precipeodg->setDenominazione($data->denominazione);
        $precipeodg->setRisultanza($data->risultanza);
        $precipeodg->setAnnotazioni($data->annotazioni);
        if (isset($data->annotazioni)) { $precipeodg->setAnnotazioni($data->annotazioni);}
        $precipeodg->setStato($data->stato);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("precipeodg");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($precipeodg), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
		
        
        
   /**
     * @Route("/precipeodg", name="precipeodg_item_create")
     * @Method("POST")
     */
    public function precipeodgItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $precipeodg = new PreCipeOdg();

        $precipeodg->setIdPrecipe($data->id_precipe);
        $precipeodg->setProgressivo($data->progressivo);
        $precipeodg->setIdTitolari($data->id_titolari);
        $precipeodg->setIdFascicoli($data->id_fascicoli);
        $precipeodg->setIdArgomenti($data->id_argomenti);
        $precipeodg->setIdUffici($data->id_uffici);
        $precipeodg->setNumeroOdg($data->numero_odg);
        $precipeodg->setDenominazione($data->denominazione);
        $precipeodg->setRisultanza($data->risultanza);
        $precipeodg->setAnnotazioni($data->annotazioni);
        $precipeodg->setStato($data->stato);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("precipeodg");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($precipeodg);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($precipeodg), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



	/**
     * @SWG\Delete(
     *     path="/api/precipeodg/{id}",
     *     summary="Eliminazione OdG di un PreCipe",
     *     tags={"PreCipe OdG"},
     *     operationId="idPreCipeOdg",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id dell'OdG del Precipe",
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
     * @Route("/precipeodg/{id}", name="precipeodg_item_delete")
     * @Method("DELETE")
     */
    public function precipeodgItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        
        $repository = $em->getRepository('UserBundle:PreCipeOdg');
        $precipeodg = $repository->findOneById($id);


        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("precipeodg");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente


        $em->remove($precipeodg); //delete
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($precipeodg), Response::HTTP_OK);
        return $this->setBaseHeaders($response);

    }
		
        

        
        
        
        
        
        
        
        
        
        
    /**
     * @Route("/precipeodg", name="precipeodg_options")
     * @Method("OPTIONS")
     */
    public function precipeodgOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
    
    /**
     * @Route("/precipeodg/{id2}", name="precipeodg_item_options")
     * @Method("OPTIONS")
     */
    public function precipeodgItemOptions(Request $request, $id2) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
