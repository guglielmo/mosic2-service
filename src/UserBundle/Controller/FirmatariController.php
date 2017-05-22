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
     * @Route("/firmatari", name="firmatari")
     * @Method("GET")
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
     * @Route("/firmatari/{id}", name="firmatari_item")
     * @Method("GET")
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
     * @Route("/firmatari/{id}", name="firmatari_item_save")
     * @Method("PUT")
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
     * @Route("/firmatari", name="firmatari_item_create")
     * @Method("POST")
     */
    public function firmatariItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        
        $firmatario = new Firmatari();

        $firmatario->setChiave($data->chiave);
        $firmatario->setDenominazione($data->denominazione);
        $firmatario->setDenominazioneEstesa($data->denominazione_estesa);
        $firmatario->setTipo($data->tipo);
        $firmatario->setDisattivato($data->disattivato);
        
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
     * @Route("/firmatari/{id}", name="firmatari_item_delete")
     * @Method("DELETE")
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
            $response_array = array("error" =>  ["code" => 409, "message" => "Il firmatario non e' vuoto, impossibile eliminarlo."]);
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
