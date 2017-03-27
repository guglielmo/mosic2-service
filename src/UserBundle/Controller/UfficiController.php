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
     * @Route("/uffici", name="uffici")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_UFFICI')")
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
     * @Route("/uffici/{id}", name="uffici_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_UFFICI')")
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
     * @Route("/uffici/{id}", name="uffici_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_UFFICI')")
     */
    public function ufficiItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        
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
     * @Route("/uffici", name="uffici_item_create")
     * @Method("POST")
    * @Security("is_granted('ROLE_CREATE_UFFICI')")
     */
    public function ufficiItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $ufficio = new Uffici();

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

        $em->persist($ufficio);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($ufficio), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }

    
    /**
     * @Route("/uffici/{id}", name="uffici_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_UFFICI')")
     */
    public function ufficiItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        
        $repository = $em->getRepository('UserBundle:Uffici');
        $ufficio = $repository->findOneById($id);

        $repositoryUtenti = $em->getRepository('UserBundle:User');
        $utenti = $repositoryUtenti->findOneByIdUffici($ufficio->getId());
        

        if ($utenti) {
            $response_array = array("error" =>  ["code" => 409, "message" => "L'ufficio non e' vuoto, impossibile eliminarlo."]);
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
