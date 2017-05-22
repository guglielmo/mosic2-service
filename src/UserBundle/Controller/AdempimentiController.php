<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\Adempimenti;
use UserBundle\Entity\LastUpdates;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;


class AdempimentiController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;

    /**
     * @Route("/adempimenti", name="adempimenti")
     * @Method("GET")
     */
    public function adempimentiAction(Request $request) {
        //prendo i parametri get
        $limit  = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;
        $sortBy  = ($request->query->get('sort_by') != "") ? $request->query->get('sort_by') : 'codice';
        $sortType = ($request->query->get('sort_order') != "") ? $request->query->get('sort_order') : "ASC";

        $repository = $this->getDoctrine()->getRepository('UserBundle:Adempimenti');
        $adempimenti = $repository->listaAdempimenti($limit, $offset, $sortBy, $sortType);

        //converte i risultati in json
        $serialize = $this->serialize($adempimenti);
        //funzione per formattare le date del json
        $serialize = $this->formatDateJsonArrayCustom(json_decode($serialize), array('data_scadenza', 'data_modifica'));

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($adempimenti),
            "limit" => $limit,
            "offset" => $offset,
            "data" => $serialize,
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
    
    
    /**
     * @Route("/adempimenti/{id}", name="adempimenti_item")
     * @Method("GET")
     */
    public function adempimentiItemAction(Request $request, $id) {
            
        $repository = $this->getDoctrine()->getRepository('UserBundle:Adempimenti');
        $adempimento = $repository->findOneById($id);

				
        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($adempimento),
            "limit" => 1,
            "offset" => 0,
            "data" => json_decode($this->serialize($adempimento)),
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
		
        
    /**
     * @Route("/adempimenti/{id}", name="adempimenti_item_save")
     * @Method("PUT")
     */
    public function adempimentiItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        
        $repository = $em->getRepository('UserBundle:Adempimenti');
        $adempimento = $repository->findOneBy(["id" => $data->id]);

        $token = $this->get('security.token_storage')->getToken();
        $user = $token->getUser();


        $adempimento->setCodice($data->codice);
        $adempimento->setProgressivo($data->progressivo);
        $adempimento->setCodiceScheda($data->codice_scheda);
        $adempimento->setIdDelibere($data->id_delibere);
        $adempimento->setDescrizione($data->descrizione);
        $adempimento->setCodiceDescrizione($data->codice_descrizione);
        $adempimento->setCodiceFonte($data->codice_fonte);
        $adempimento->setCodiceEsito($data->codice_esito);
        $adempimento->setDataScadenza(new \DateTime($this->formatDateStringCustom($data->data_scadenza)));
        $adempimento->setGiorniScadenza($data->giorni_scadenza);
        $adempimento->setMesiScadenza($data->mesi_scadenza);
        $adempimento->setAnniScadenza($data->anni_scadenza);
        $adempimento->setVincolo($data->vincolo);
        $adempimento->setNote($data->note);
        $adempimento->setUtente($data->utente);
        $adempimento->getDataModifica(new \DateTime());

        
        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente
        
        
        $em->flush(); //esegue l'update


        $response = new Response($this->serialize($adempimento), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
		
        
        
   /**
     * @Route("/adempimenti", name="adempimenti_item_create")
     * @Method("POST")
     */
    public function adempimentiItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        
        $adempimento = new Adempimenti();

        $adempimento->setCodice($data->codice);
        $adempimento->setProgressivo($data->progressivo);
        $adempimento->setCodiceScheda($data->codice_scheda);
        $adempimento->setIdDelibere($data->id_delibere);
        $adempimento->setDescrizione($data->descrizione);
        $adempimento->setCodiceDescrizione($data->codice_descrizione);
        $adempimento->setCodiceFonte($data->codice_fonte);
        $adempimento->setCodiceEsito($data->codice_esito);
        $adempimento->setDataScadenza(new \DateTime($this->formatDateStringCustom($data->data_scadenza)));
        $adempimento->setGiorniScadenza($data->giorni_scadenza);
        $adempimento->setMesiScadenza($data->mesi_scadenza);
        $adempimento->setAnniScadenza($data->anni_scadenza);
        $adempimento->setVincolo($data->vincolo);
        $adempimento->setNote($data->note);
        $adempimento->setUtente($data->utente);

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente
        
        $em->persist($adempimento);
        $em->flush(); //esegue l'update 

        $response = new Response($this->serialize($adempimento), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
    
    
    /**
     * @Route("/adempimenti/{id}", name="adempimenti_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_Adempimenti')")
     */
    public function adempimentiItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        
        $repository = $em->getRepository('UserBundle:Adempimenti');
        $adempimento = $repository->findOneById($id);
        
        //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("adempimenti");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

           
            $em->remove($adempimento); //delete
    
            $em->flush(); //esegue l'update 
    
            $response = new Response($this->serialize($adempimento), Response::HTTP_OK);
    
            return $this->setBaseHeaders($response);
        
    }
		
        

        
        
        
        
        
        
        
        
        
        
    /**
     * @Route("/adempimenti", name="adempimenti_options")
     * @Method("OPTIONS")
     */
    public function adempimentiOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
    
    /**
     * @Route("/adempimenti/{id2}", name="adempimenti_item_options")
     * @Method("OPTIONS")
     */
    public function adempimentiItemOptions(Request $request, $id2) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
