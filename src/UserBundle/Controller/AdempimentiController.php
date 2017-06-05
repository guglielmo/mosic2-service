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
     * @SWG\Tag(
     *   name="Adempimenti",
     *   description="Tutte le Api degli adempimenti"
     * )
     */


    /**
     * @SWG\Get(
     *     path="/api/adempimenti",
     *     summary="Lista adempimenti",
     *     tags={"Adempimenti"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":280,"limit":"99999","offset":0,"data":{{"id":281,"codice":null,"progressivo":null,"codice_scheda":null,"id_delibere":418,"descrizione":"test date","codice_descrizione":null,"codice_fonte":null,"codice_esito":null,"data_scadenza":1495749600000,"giorni_scadenza":null,"mesi_scadenza":null,"anni_scadenza":null,"vincolo":null,"note":"","utente":null,"data_modifica":1495922400000},{"id":258,"codice":1,"progressivo":1,"codice_scheda":616,"id_delibere":2346,"descrizione":" ","codice_descrizione":0,"codice_fonte":0,"codice_esito":0,"data_scadenza":1495317600000,"giorni_scadenza":1,"mesi_scadenza":2,"anni_scadenza":3,"vincolo":1,"note":"","utente":90138,"data_modifica":1412632800000}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     */

    /**
     * @Route("/adempimenti", name="adempimenti")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_ADEMPIMENTI')")
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

//        //ricavo data e numero delibera per ogni adempimento
//        $repositoryDelibere = $this->getDoctrine()->getRepository('UserBundle:Delibere');
//        foreach ($serialize as $item => $value) {
//            $delibere = $repositoryDelibere->findOneBy(["id" => $value->id]);
//            $numeroDelibera = $delibere->getNumero();
//            $dataDelibera = $delibere->getData();
//            $serialize[$item]->data_delibera = strtotime($dataDelibera->format('Y-m-d')) * 1000;
//            $serialize[$item]->numero_delibera = $numeroDelibera;
//        }

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
     * @SWG\Get(
     *     path="/api/adempimenti/{id}",
     *     summary="Singolo adempimento",
     *     tags={"Adempimenti"},
     *     operationId="idAdempimento",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id dell'adempimento",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":280,"limit":"99999","offset":0,"data":{{"id":281,"codice":null,"progressivo":null,"codice_scheda":null,"id_delibere":418,"descrizione":"test date","codice_descrizione":null,"codice_fonte":null,"codice_esito":null,"data_scadenza":1495749600000,"giorni_scadenza":null,"mesi_scadenza":null,"anni_scadenza":null,"vincolo":null,"note":"","utente":null,"data_modifica":1495922400000},{"id":258,"codice":1,"progressivo":1,"codice_scheda":616,"id_delibere":2346,"descrizione":" ","codice_descrizione":0,"codice_fonte":0,"codice_esito":0,"data_scadenza":1495317600000,"giorni_scadenza":1,"mesi_scadenza":2,"anni_scadenza":3,"vincolo":1,"note":"","utente":90138,"data_modifica":1412632800000}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))

     * )
     */
	
    /**
     * @Route("/adempimenti/{id}", name="adempimenti_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_ADEMPIMENTI')")
     */
    public function adempimentiItemAction(Request $request, $id) {
            
        $repository = $this->getDoctrine()->getRepository('UserBundle:Adempimenti');
        $adempimento = $repository->findOneById($id);

        //converte i risultati in json
        $serialize = json_decode($this->serialize($adempimento));
        //funzione per formattare le date del json
        $serialize = $this->formatDateJsonArrayCustom([$serialize], array('data_scadenza', 'data_modifica'));

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($adempimento),
            "limit" => 1,
            "offset" => 0,
            "data" => $serialize[0],
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
	
	
	/**
     * @SWG\Put(
     *     path="/api/adempimenti/{id}",
     *     summary="Salvataggio adempimento",
     *     tags={"Adempimenti"},
     *     operationId="idAdempimento",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="query",
     *         description="id dell'adempimento",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
	 *     @SWG\Parameter(
     *         name="adempimenti",
     *         in="body",
     *         description="Richiesta",
     *         required=true,
	 *         @SWG\Schema(
	 *				type="array",
     *              @SWG\Items(
     *                 type="object",
     *                 	@SWG\Property(property="id", type="integer"),
	 *                 	@SWG\Property(property="id_delibere", type="integer"),
	 *					@SWG\Property(property="descrizione", type="string"),
	 *					@SWG\Property(property="codice_descrizione", type="integer"),
	 *					@SWG\Property(property="codice_fonte", type="integer"),
	 *					@SWG\Property(property="codice_esito", type="integer"),
	 *					@SWG\Property(property="fonti", type="string"),
	 *					@SWG\Property(property="data_scadenza", type="string"),
	 *					@SWG\Property(property="vincolo", type="integer"),
	 *					@SWG\Property(property="note", type="string"),
	 *					@SWG\Property(property="codice", type="integer"),
	 *					@SWG\Property(property="progressivo", type="integer"),
	 *					@SWG\Property(property="codice_scheda", type="integer"),
	 *					@SWG\Property(property="giorni_scadenza", type="integer"),
	 *					@SWG\Property(property="mesi_scadenza", type="integer"),
	 *					@SWG\Property(property="anni_scadenza", type="integer"),
	 *					@SWG\Property(property="utente", type="integer"),
	 *					@SWG\Property(property="data_modifica", type="string"),
	 *					@SWG\Property(property="id_cipe", type="integer")
     *             )
	 *			),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */
        
    /**
     * @Route("/adempimenti/{id}", name="adempimenti_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_ADEMPIMENTI')")
     */
    public function adempimentiItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        
        $repository = $em->getRepository('UserBundle:Adempimenti');
        $adempimento = $repository->findOneBy(["id" => $data->id]);

        //$token = $this->get('security.token_storage')->getToken();
        //$user = $token->getUser();


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
     * @SWG\Post(
     *     path="/api/adempimenti",
     *     summary="Creazione adempimento",
     *     tags={"Adempimenti"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */
   
   
        
   /**
     * @Route("/adempimenti", name="adempimenti_item_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREATE_ADEMPIMENTI')")
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
     * @SWG\Delete(
     *     path="/api/adempimentu/{id}",
     *     summary="Eliminazione adempimento",
     *     tags={"Adempimenti"},
     *     operationId="idAdempimento",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id dell'adempimento",
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
     * @Route("/adempimenti/{id}", name="adempimenti_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_ADEMPIMENTI')")
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
