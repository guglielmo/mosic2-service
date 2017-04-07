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



class TitolariController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;

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

        
        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($titolari),
            "limit" => $limit,
            "offset" => $offset,
            "data" => json_decode($this->serialize($titolari)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
    
    
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
     * @Route("/titolari/{id}", name="titolari_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_TITOLARI')")
     */
    public function titolariItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        
        $repository = $em->getRepository('UserBundle:Titolari');
        $titolario = $repository->findOneById($data->id);
        
        $titolario->setCodice($data->codice);
        $titolario->setDenominazione($data->denominazione);
        $titolario->setDescrizione($data->descrizione);

        
        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("titolari");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente
        
        
        $em->flush(); //esegue l'update


        $response = new Response($this->serialize($titolario), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
		
        
        
   /**
     * @Route("/titolari", name="titolari_item_create")
     * @Method("POST")
    * @Security("is_granted('ROLE_CREATE_TITOLARI')")
     */
    public function titolariItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        
        $titolario = new Titolari();
        
        $titolario->setCodice($data->codice);
        $titolario->setDenominazione($data->denominazione);
        $titolario->setDescrizione($data->descrizione);
        
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
