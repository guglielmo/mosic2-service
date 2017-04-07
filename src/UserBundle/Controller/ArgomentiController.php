<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\Argomenti;
use UserBundle\Entity\PreCipeOdg;
use UserBundle\Entity\LastUpdates;



class ArgomentiController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;

    /**
     * @Route("/argomenti", name="argomenti")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_UFFICI')")
     */
    public function argomentiAction(Request $request) {
        //prendo i parametri get
        $limit  = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;

        $repository = $this->getDoctrine()->getRepository('UserBundle:Argomenti');
        $argomento = $repository->findAll();

        //print_r($tags);

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($argomento),
            "limit" => $limit,
            "offset" => $offset,
            "data" => json_decode($this->serialize($argomento)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
    
    
    /**
     * @Route("/argomenti/{id}", name="argomenti_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_UFFICI')")
     */
    public function argomentiItemAction(Request $request, $id) {
            
        $repository = $this->getDoctrine()->getRepository('UserBundle:Argomenti');
        $argomento = $repository->findOneById($id);

				
        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($argomento),
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($argomento)),
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
		
        
    /**
     * @Route("/argomenti/{id}", name="argomenti_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_UFFICI')")
     */
    public function argomentiItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        
        $repository = $em->getRepository('UserBundle:Argomenti');
        $argomento = $repository->findOneById($data->id);

        $argomento->setCodice($data->codice);
        $argomento->setDenominazione($data->denominazione);
        
        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("argomenti");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($argomento), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
		
        
        
   /**
     * @Route("/argomenti", name="argomenti_item_create")
     * @Method("POST")
    * @Security("is_granted('ROLE_CREATE_UFFICI')")
     */
    public function argomentiItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $argomento = new Argomenti();

        $argomento->setCodice($data->codice);
        $argomento->setDenominazione($data->denominazione);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("argomenti");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($argomento);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($argomento), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }

    
    /**
     * @Route("/argomenti/{id}", name="argomenti_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_UFFICI')")
     */
    public function argomentiItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        
        $repository = $em->getRepository('UserBundle:Argomenti');
        $argomento = $repository->findOneById($id);

        $repositoryPreCipeOdg = $em->getRepository('UserBundle:PreCipeOdg');
        $ordini = $repositoryPreCipeOdg->findOneByIdArgomenti($id);
        

        if ($ordini) {
            $response_array = array("error" =>  ["code" => 409, "message" => "L'argomento non e' vuoto, impossibile eliminarlo."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {
            
            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("argomenti");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

           
            $em->remove($argomento); //delete
            $em->flush(); //esegue l'update

            $response = new Response($this->serialize($argomento), Response::HTTP_OK);
            return $this->setBaseHeaders($response);
        }
    }
		
        

        
        
        
        
        
        
        
        
        
        
    /**
     * @Route("/argomenti", name="argomenti_options")
     * @Method("OPTIONS")
     */
    public function argomentiOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
    
    /**
     * @Route("/argomenti/{id2}", name="argomenti_item_options")
     * @Method("OPTIONS")
     */
    public function argomentiItemOptions(Request $request, $id2) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
