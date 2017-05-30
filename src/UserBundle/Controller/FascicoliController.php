<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\Fascicoli;
use UserBundle\Entity\RelAmministrazioniFascicoli;
use UserBundle\Entity\RelTagsFascicoli;
use UserBundle\Entity\LastUpdates;
use UserBundle\Entity\Registri;



class FascicoliController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;

    /**
     * @Route("/fascicoli", name="fascicoli")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_FASCICOLI')")
     */
    public function fascicoliAction(Request $request) {
        
        //prendo i parametri get
        $limit  = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;
        $sortBy  = ($request->query->get('sort_by') != "") ? $request->query->get('sort_by') : 'numero_fascicolo';
        $sortType = ($request->query->get('sort_order') != "") ? $request->query->get('sort_order') : "ASC";

        $id_titolari = ($request->query->get('id_titolari') != "") ? $request->query->get('id_titolari') : '';
        $numero_fascicolo = ($request->query->get('numero_fascicolo') != "") ? $request->query->get('numero_fascicolo') : '';
        $argomento = ($request->query->get('argomento') != "") ? $request->query->get('argomento') : '';
        $id_amministrazione = ($request->query->get('id_amministrazione') != "") ? $request->query->get('id_amministrazione') : '';
			

        $repository = $this->getDoctrine()->getRepository('UserBundle:Fascicoli');
        $fascicoli = $repository->listaFascicoli($limit, $offset, $sortBy, $sortType, $id_titolari, $numero_fascicolo, $argomento, $id_amministrazione); //effettua un join e restituisce tanti oggetti quante sono le relazioni
        $totFascicoli = $repository->totaleFascicoli();

        
        //converte i risultati in json
        $serialize = $this->serialize($fascicoli);
        
        //funzione per raggruppare i risultati del join inserendo id_amministrazioni con la virgola
		$serialize = $this->mergeIdAmministrazioni($serialize);


		foreach ($serialize as $item => $value) {
            $tagArrayConvert = array_map('intval', explode(',', $serialize[$item]["id_tags"]));
            $serialize[$item]["id_tags"] = ($tagArrayConvert[0] == 0 ? array() : $tagArrayConvert);
        }
        //funzione per formattare le date del json
        //$serialize = $this->formatDateJsonCustom($serialize, array('data_cipe', 'data_cipe2', 'data_magazzino'));
        

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($totFascicoli),
            "filter_results" => count($fascicoli),
            "limit" => $limit,
            "offset" => $offset,
            "data" => $serialize
        );
        
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
		
    /**
     * @Route("/fascicoli/{id}", name="fascicoli_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_FASCICOLI')")
     */
    public function fascicoliItemAction(Request $request, $id) {
            
        $repository = $this->getDoctrine()->getRepository('UserBundle:Fascicoli');
        $fascicolo = $repository->schedaFascicolo($id); //effettua un join e restituisce tanti oggetti quante sono le relazioni
				
		//converte i risultati in json
        $serialize = $this->serialize($fascicolo);

        
        //funzione per raggruppare i risultati del join inserendo id_amministrazioni e anche id_tags con la virgola
        $serialize = $this->mergeIdAmministrazioni($serialize);

        $serialize = $this->formatDateJsonCustom($serialize, array('data_cipe', 'data_cipe2', 'data_magazzino'));

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => 1,
            "limit" => 1,
            "offset" => 0,
            "data" => $serialize,
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
    
    
    /**
     * @Route("/fascicoli/{id}", name="fascicoli_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_FASCICOLI')")
     */
    public function fascicoliItemSaveAction(Request $request, $id) {
        
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $repository = $em->getRepository('UserBundle:Fascicoli');
        $fascicolo = $repository->findOneById($data->id); //ricavo il fascicolo dall'id

        $repository_rel = $em->getRepository('UserBundle:RelAmministrazioniFascicoli');
        $relAmministrazioniFascicoli_delete = $repository_rel->findByIdFascicoli($data->id); //ricavo tutte le relazioni con l'id del fascicolo

        $repository_rel_tags = $em->getRepository('UserBundle:RelTagsFascicoli');
        $relTagsFascicoli_delete = $repository_rel_tags->findByIdFascicoli($data->id); //ricavo tutte le relazioni con l'id del fascicolo

        //ricavo gli tutte le amministrazioni passate dalla tendina
        $array_id_amministrazioni = explode(",", $data->id_amministrazioni);
        //ricavo gli tutte i tags passati dalla tendina
        $array_id_tags = explode(",", $data->id_tags);


		//setto i campi del fascicolo
        $fascicolo->setIdAmministrazione($data->id_amministrazione);//NON � quello giusto
        $fascicolo->setAnnotazioni($data->annotazioni);
        $fascicolo->setArchiviazioneRepertorio($data->archiviazione_repertorio);
        $fascicolo->setIdArchivioRepertorio($data->archivio_repertorio_id);
        $fascicolo->setArgomento($data->argomento);
        $fascicolo->setCodiceRepertorio($data->codice_repertorio);
        $fascicolo->setIdTitolari($data->id_titolari);
        //$fascicolo->setDataCipe($data->data_cipe);
        //$fascicolo->setDataCipe2($data->data_cipe2);
        $fascicolo->setDataMagazzino(new \DateTime($this->formatDateStringCustom($data->data_magazzino))); //22/09/2009
        $fascicolo->setIdEsitiCipe($data->esiti_cipe_id);
        $fascicolo->setIdNumeriDelibera($data->numeri_delibera_id);
        $fascicolo->setNumeroFascicolo($data->numero_fascicolo);

				
		// AMMINISTRAZIONI
        //rimuovo tutte le relazioni con l'id del fascicolo (per poi riaggiornale ovvero ricrearle)
        foreach ($relAmministrazioniFascicoli_delete as $relAmministrazioniFascicoli_delete) {
            $em->remove($relAmministrazioniFascicoli_delete);
        }
        //creo le relazioni da aggiornare nella tabella RelAmministrazioniFascicoli
        foreach ($array_id_amministrazioni as $item) {
            $relAmministrazioniFascicoli = new RelAmministrazioniFascicoli();
            $relAmministrazioniFascicoli->setIdFascicoli($data->id);
            $relAmministrazioniFascicoli->setIdAmministrazioni($item);
            //aggiorno (in realt� ricreo) le relazioni del fascicolo
            $em->persist($relAmministrazioniFascicoli); //create
        }

        // TAGS
        //rimuovo tutte le relazioni con l'id del fascicolo (per poi riaggiornale ovvero ricrearle)
        foreach ($relTagsFascicoli_delete as $relTagsFascicoli_delete) {
            $em->remove($relTagsFascicoli_delete);
        }
        //creo le relazioni da aggiornare nella tabella RelTagsFascicoli
        foreach ($array_id_tags as $item_tags) {
            $relTagsFascicoli = new RelTagsFascicoli();
            $relTagsFascicoli->setIdFascicoli($data->id);
            $relTagsFascicoli->setIdTags($item_tags);
            //aggiorno (in realt� ricreo) le relazioni del fascicolo
            $em->persist($relTagsFascicoli); //create
        }
                
        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("fascicoli");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->flush(); //esegue 

        $response = new Response($this->serialize($relTagsFascicoli), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
		
        
   /**
     * @Route("/fascicoli", name="fascicoli_item_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREATE_FASCICOLI')")
     */
    public function fascicoliItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());


        //restituisce tutti i fascicoli dell'id titolario ordinati per numero fascicolo discendente
        //in modo tale che sommando 1 al primo otteniamo il nuovo id_titolari per il fascicolo da creare
        $repository = $em->getRepository('UserBundle:Fascicoli');
        $fascicoli_titolario = $repository->findOneBy(array('idTitolari' => $data->id_titolari),array('numeroFascicolo' => 'DESC'));
        if ($fascicoli_titolario) {
            $id_prossimo_fascicolo = $fascicoli_titolario->getNumeroFascicolo() + 1;
        } else {
            $id_prossimo_fascicolo = 1;
        }


        $fascicolo = new Fascicoli();

        $fascicolo->setAnnotazioni($data->annotazioni);
        $fascicolo->setArchiviazioneRepertorio($data->archiviazione_repertorio);
        $fascicolo->setArgomento($data->argomento);
        $fascicolo->setIdTitolari($data->id_titolari);
        $fascicolo->setDataMagazzino(new \DateTime($this->formatDateStringCustom($data->data_magazzino)));
        //$fascicolo->setNumeroFascicolo($data->numero_fascicolo);
        $fascicolo->setNumeroFascicolo($id_prossimo_fascicolo);


        //ricavo gli id di tutte le amministrazioni passate dalla tendina
        $array_id_amministrazioni = explode(",", $data->id_amministrazione);
        //ricavo gli id di tutte i tags passati dalla tendina
        $array_id_tags = explode(",", $data->id_tags);


        $em->persist($fascicolo);
        $em->flush(); //esegue query



        $id_fascicolo_creato = $fascicolo->getId();
        // AMMINISTRAZIONI
        //creo le relazioni da creare nella tabella RelAmministrazioniFascicoli
        foreach ($array_id_amministrazioni as $item) {
            $relAmministrazioniFascicoli = new relAmministrazioniFascicoli();
            $relAmministrazioniFascicoli->setIdFascicoli($id_fascicolo_creato);
            $relAmministrazioniFascicoli->setIdAmministrazioni($item);
            //aggiorno (in realt� ricreo) le relazioni del fascicolo
            $em->persist($relAmministrazioniFascicoli); //create
        }
        // TAGS
        //creo le relazioni da creare nella tabella RelTagsFascicoli
        foreach ($array_id_tags as $item) {
            $relTagsFascicoli = new RelTagsFascicoli();
            $relTagsFascicoli->setIdFascicoli($id_fascicolo_creato);
            $relTagsFascicoli->setIdTags($item);
            //aggiorno (in realt� ricreo) le relazioni del fascicolo
            $em->persist($relTagsFascicoli); //create
        }
        
        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("fascicoli");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente
        
        $em->flush(); //esegue query


        $response = new Response($this->serialize($fascicolo), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }
        
        
        
    /**
     * @Route("/fascicoli/{id}", name="fascicoli_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_FASCICOLI')")
     */
    public function fascicoliItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        
        $repository = $em->getRepository('UserBundle:Fascicoli');
        $fascicolo = $repository->findOneById($id);
        
        
        $repositoryRegistri = $em->getRepository('UserBundle:Registri');
        $registri = $repositoryRegistri->findOneByIdFascicoli($id);
  
        
        if ($registri) {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il Fascicolo non e' vuoto, impossibile eliminarlo."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {    
            $repository_rel = $em->getRepository('UserBundle:RelAmministrazioniFascicoli');
            $relAmministrazioniFascicoli_delete = $repository_rel->findByIdFascicoli($id);//ricavo tutte le relazioni con l'id del fascicolo

            $repository_rel_tags = $em->getRepository('UserBundle:RelTagsFascicoli');
            $relTagsFascicoli_delete = $repository_rel_tags->findByIdFascicoli($id);//ricavo tutte le relazioni con l'id del fascicolo

            //rimuovo tutte le relazioni con l'id del fascicolo (per poi riaggiornale ovvero ricrearle)
            foreach ($relAmministrazioniFascicoli_delete as $relAmministrazioniFascicoli_delete) {
                $em->remove($relAmministrazioniFascicoli_delete);
            }
            //rimuovo tutte le relazioni con l'id del fascicolo (per poi riaggiornale ovvero ricrearle)
            foreach ($relTagsFascicoli_delete as $relTagsFascicoli_delete) {
                $em->remove($relTagsFascicoli_delete);
            }

            $em->remove($fascicolo); //delete
              
            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("fascicoli");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente
            
            $em->flush(); //esegue
    
            $response = new Response($this->serialize($fascicolo), Response::HTTP_OK);
    
            return $this->setBaseHeaders($response);
        }
    }
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
    /**
     * @Route("/fascicoli/{id2}", name="fascicoli_item_options")
     * @Method("OPTIONS")
     */
    public function fascicoliItemOptions(Request $request, $id2) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
    
    /**
     * @Route("/fascicoli", name="fascicoli_options")
     * @Method("OPTIONS")
     */
    public function fascicoliOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
