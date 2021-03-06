<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use UserBundle\Entity\Costanti;
use UserBundle\Entity\Registri;
use UserBundle\Entity\RelAmministrazioniRegistri;
use UserBundle\Entity\RelTagsRegistri;
use UserBundle\Entity\RelAllegatiRegistri;
use UserBundle\Entity\Allegati;
use UserBundle\Entity\LastUpdates;
use UserBundle\Entity\Titolari;
use UserBundle\Entity\Fascicoli;
use Doctrine\ORM\EntityNotFoundException;


class RegistriController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;


	/**
     * @SWG\Tag(
     *   name="Registri",
     *   description="Tutte le Api dei Registri"
     * )
     */


    /**
     * @SWG\Get(
     *     path="/api/registri",
     *     summary="Lista registri",
     *     tags={"Registri"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":6939,"filter_results":6939,"limit":"99999","offset":0,"data":{{"id":7179,"data_arrivo":1494972000000,"protocollo_arrivo":"test testtest test","data_mittente":1494194400000,"protocollo_mittente":"9999","oggetto":"test test2","id_amministrazione":0,"mittente":"","codice_titolario":0,"numero_fascicolo":0,"numero_sottofascicolo":0,"denominazione_sottofascicolo":"","proposta_cipe":false,"annotazioni":"test test","id_sottofascicoli":0,"id_mittenti":2,"id_titolari":3,"id_fascicoli":896,"id_tags":{12,14,28,29,30}}}}

     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     */


    /**
     * @Route("/registri", name="registri")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_REGISTRI')")
     */
    public function registriAction(Request $request)
    {
        //* @Security("is_granted('ROLE_READ_REGISTRI')")

        //prendo i parametri get
        $limit = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;
        $sortBy = ($request->query->get('sort_by') != "") ? $request->query->get('sort_by') : 'id';
        $sortType = ($request->query->get('sort_order') != "") ? $request->query->get('sort_order') : "DESC";

        $id = ($request->query->get('id') != "") ? $request->query->get('id') : '';
        $id_titolari = ($request->query->get('id_titolari') != "") ? $request->query->get('id_titolari') : '';
        $numero_fascicolo = ($request->query->get('numero_fascicolo') != "") ? $request->query->get('numero_fascicolo') : '';
        $id_mittenti = ($request->query->get('id_mittenti') != "") ? $request->query->get('id_mittenti') : '';
        $data_arrivo_from = ($request->query->get('data_arrivo_from') != "") ? $request->query->get('data_arrivo_from') : '';
        $data_arrivo_to = ($request->query->get('data_arrivo_to') != "") ? $request->query->get('data_arrivo_to') : '';
        $protocollo_arrivo = ($request->query->get('protocollo_arrivo') != "") ? $request->query->get('protocollo_arrivo') : '';
        $protocollo_mittente = ($request->query->get('protocollo_mittente') != "") ? $request->query->get('protocollo_mittente') : '';
        $oggetto = ($request->query->get('oggetto') != "") ? $request->query->get('oggetto') : '';


        $repository = $this->getDoctrine()->getRepository('UserBundle:Registri');
        $registri = $repository->listaRegistri($limit, $offset, $sortBy, $sortType, $id, $id_titolari, $numero_fascicolo, $id_mittenti, $data_arrivo_from, $data_arrivo_to, $protocollo_arrivo, $protocollo_mittente, $oggetto);
        $totRegistri = $repository->totaleRegistri();

        //converte i risultati in json
        //$serialize = $this->serialize($registri);
        $serialize = json_encode($registri, JSON_NUMERIC_CHECK);
        $serialize = json_decode($serialize);

        //funzione per formattare le date del json
        //$serialize = $this->formatDateJsonArrayCustom(json_decode($serialize), array('data_arrivo', 'data_mittente'));

        //aggiungo i tags
        $repositoryTags = $this->getDoctrine()->getRepository('UserBundle:RelTagsRegistri');
        foreach ($serialize as $item => $value) {
            $tags = $repositoryTags->findBy(["idRegistri" => $value->id]);
            $tags = json_decode($this->serialize($tags));
            //print_r($tags[0]);
            $value->pippo = "aaa";
            foreach ($tags as $i => $v) {
                $value->id_tags[] = $v->id_tags;
            }
            if (count($tags) == 0) {
                $value->id_tags = [];
            }
        }

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($totRegistri),
            "filter_results" => count($registri),
            "limit" => $limit,
            "offset" => $offset,
            "data" => $serialize
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


	/**
     * @SWG\Get(
     *     path="/api/registri/{id}",
     *     summary="Singolo registro",
     *     tags={"Registri"},
     *     operationId="idRegistro",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id del registro",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":6939,"filter_results":6939,"limit":"99999","offset":0,"data":{"id":7179,"data_arrivo":1494972000000,"protocollo_arrivo":"test testtest test","data_mittente":1494194400000,"protocollo_mittente":"9999","oggetto":"test test2","id_amministrazione":0,"mittente":"","codice_titolario":0,"numero_fascicolo":0,"numero_sottofascicolo":0,"denominazione_sottofascicolo":"","proposta_cipe":false,"annotazioni":"test test","id_sottofascicoli":0,"id_mittenti":2,"id_titolari":3,"id_fascicoli":896,"id_tags":{12,14,28,29,30}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))

     * )
     */


    /**
     * @Route("/registri/{id}", name="registri_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_REGISTRI')")
     */
    public function registriItemAction(Request $request, $id)
    {

        $repository = $this->getDoctrine()->getRepository('UserBundle:Registri');
        $registro = $repository->schedaRegistro($id);

        //converte i risultati in json
        $serialize = $this->serialize($registro);

        //funzione per raggruppare i risultati del join inserendo id_amministrazioni con la virgola
        $serialize = $this->mergeIdAmministrazioni($serialize);


        $getDataPath = $repository->getDataPath($id);
        $getDataPath = json_decode($this->serialize($getDataPath));

        $allegati = $repository->getAllegatiByIdRegistro($id);

        //$serialize = $this->mergeAllegati($serialize, $allegati);

        $serialize["allegati"] = $allegati;

        $serialize = $this->formatDateJsonCustom($serialize, array('data_arrivo', 'data_mittente'));


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => 1,
            "limit" => 1,
            "offset" => 0,
            "data" => $serialize,
            //"allegati" => $allegati
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


	/**
     * @SWG\Put(
     *     path="/api/registri/{id}",
     *     summary="Salvataggio registro",
     *     tags={"Registri"},
     *     operationId="idRegistro",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="query",
     *         description="id del registro",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
	 *     @SWG\Parameter(
     *         name="registri",
     *         in="body",
     *         description="Richiesta",
     *         required=true,
 	 *         @SWG\Schema(
	 *				type="array",
     *              @SWG\Items(
     *                 type="object",
     *                 	@SWG\Property(property="id", type="integer"),
	 *                 	@SWG\Property(property="data_arrivo", type="string"),
	 *					@SWG\Property(property="protocollo_arrivo", type="integer"),
	 *					@SWG\Property(property="data_mittente", type="string"),
	 *					@SWG\Property(property="protocollo_mittente", type="integer"),
	 *					@SWG\Property(property="oggetto", type="string"),
	 *					@SWG\Property(property="mittente", type="string"),
	 *					@SWG\Property(property="codice_titolario", type="integer"),
	 *					@SWG\Property(property="numero_fascicolo", type="integer"),
	 *					@SWG\Property(property="numero_sottofascicolo", type="string"),
	 *					@SWG\Property(property="denominazione_sottofascicolo", type="string"),
	 *					@SWG\Property(property="proposta_cipe", type="integer"),
	 *					@SWG\Property(property="annotazioni", type="string"),
	 *					@SWG\Property(property="id_sottofascicoli", type="integer"),
	 *					@SWG\Property(property="id_mittenti", type="array"),
	 *					@SWG\Property(property="id_titolari", type="array"),
	 *					@SWG\Property(property="id_fascicoli", type="array"),
	 *					@SWG\Property(property="id_amministrazioni", type="array"),
	 *					@SWG\Property(property="id_tags", type="array"),
	 *					@SWG\Property(property="allegati_DEL",
	 *						type="array",
	 *						@SWG\Items(
	 *							@SWG\Property(property="id", type="integer"),
	 *							@SWG\Property(property="data", type="string"),
	 *							@SWG\Property(property="nome", type="string"),
	 *							@SWG\Property(property="tipo", type="string"),
	 *							@SWG\Property(property="relURI", type="string"),
	 *							@SWG\Property(property="dimensione", type="string")
	 *						)	 
	 *					),
     *             )
	 *			),
     *     ),
     *     @SWG\Response(
     
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */


    /**
     * @Route("/registri/{id}", name="registri_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_REGISTRI')")
     */
    public function registriItemSaveAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()),["oggetto","protocollo_arrivo","data_arrivo","id_titolari","id_fascicoli"]);
        if ($check != "ok") {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il campo ".$check." e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $repository = $em->getRepository('UserBundle:Registri');
        $registro = $repository->findOneById($data->id);
        //$registro = $repository->findOneBy(["id" => $data->id]);

        $repository_rel = $em->getRepository('UserBundle:RelAmministrazioniRegistri');
        $relAmministrazioniRegistri_delete = $repository_rel->findByIdRegistri($data->id); //ricavo tutte le relazioni con l'id dei registri

        $repository_rel_tags = $em->getRepository('UserBundle:RelTagsRegistri');
        $relTagsRegistri_delete = $repository_rel_tags->findByIdRegistri($data->id); //ricavo tutte le relazioni con l'id del registro

        //ricavo gli tutte le amministrazioni passate dalla tendina
        $array_id_amministrazioni = explode(",", $data->id_amministrazioni);
        //ricavo gli tutte i tags passati dalla tendina
        $array_id_tags = explode(",", $data->id_tags);

        $registro->setAnnotazioni($data->annotazioni);
        //$registro->setCodiceTitolario($data->codice_titolario);

        if (isset($data->data_mittente)) {
            $registro->setDataMittente(new \DateTime($this->zulu_to_rome($data->data_mittente)));
        } else {
            $registro->setDataMittente(null);
        }
        $registro->setDataArrivo(new \DateTime($this->zulu_to_rome($data->data_arrivo)));
        //$registro->setIdAmministrazione($data->id_amministrazione);
        $registro->setIdFascicoli($data->id_fascicoli);
        $registro->setIdMittenti($data->id_mittenti);
        $registro->setIdSottofascicoli($data->id_sottofascicoli);
        $registro->setIdTitolari($data->id_titolari);
        $registro->setMittente($data->mittente);
        $registro->setNumeroFascicolo($data->numero_fascicolo);
        $registro->setNumeroSottofascicolo($data->numero_sottofascicolo);
        $registro->setOggetto($data->oggetto);
        $registro->setPropostaCipe($data->proposta_cipe);
        $registro->setProtocolloArrivo($data->protocollo_arrivo);
        $registro->setProtocolloMittente($data->protocollo_mittente);

        // AMMINISTRAZIONI
        //rimuovo tutte le relazioni con l'id del registro (per poi riaggiornale ovvero ricrearle)
        foreach ($relAmministrazioniRegistri_delete as $relAmministrazioniRegistri_delete) {
            $em->remove($relAmministrazioniRegistri_delete);
        }
        //creo le relazioni da aggiornare nella tabella RelAmministrazioniRegistri
        foreach ($array_id_amministrazioni as $item) {
            $relAmministrazioniRegistri = new relAmministrazioniRegistri();
            $relAmministrazioniRegistri->setIdRegistri($data->id);
            $relAmministrazioniRegistri->setIdAmministrazioni($item);
            //aggiorno (in realt� ricreo) le relazioni del registro
            $em->persist($relAmministrazioniRegistri); //create
        }

        // TAGS
        //rimuovo tutte le relazioni con l'id del fascicolo (per poi riaggiornale ovvero ricrearle)
        foreach ($relTagsRegistri_delete as $relTagsRegistri_delete) {
            $em->remove($relTagsRegistri_delete);
        }
        //creo le relazioni da aggiornare nella tabella RelTagsRegistri
        foreach ($array_id_tags as $item_tags) {
            $relTagsRegistri = new RelTagsRegistri();
            $relTagsRegistri->setIdRegistri($data->id);
            $relTagsRegistri->setIdTags($item_tags);
            //aggiorno (in realt� ricreo) le relazioni del fascicolo
            $em->persist($relTagsRegistri); //create
        }

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("registri");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->flush(); //esegue 

        $response = new Response($this->serialize($registro), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



	/**
     * @SWG\Post(
     *     path="/api/registri",
     *     summary="Creazione registro",
     *     tags={"Registri"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */

    /**
     * @Route("/registri", name="registri_item_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREATE_REGISTRI')")
     */
    public function registriItemCreateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()),["oggetto","protocollo_arrivo","data_arrivo","id_titolari","id_fascicoli"]);
        if ($check != "ok") {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il campo ".$check." e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $registro = new Registri();

        if (isset($data->annotazioni)) { $registro->setAnnotazioni($data->annotazioni);} else {$registro->setAnnotazioni(""); }

        if (isset($data->data_mittente)) {
            $registro->setDataMittente(new \DateTime($this->zulu_to_rome($data->data_mittente)));
        } else {
            $registro->setDataMittente(null);
        }

        $registro->setDataArrivo(new \DateTime($this->zulu_to_rome($data->data_arrivo)));
        //$registro->setIdAmministrazione($data->id_amministrazione);
        //$registro->setIdFascicolo($data->id_fascicoli);
        if (isset($data->id_mittenti) && $data->id_mittenti != "") {
            $registro->setIdMittenti($data->id_mittenti);
        } else {
            $registro->setIdMittenti(null);
        }


        //$registro->setIdSottofascicoli($data->id_sottofascicoli);
        $registro->setIdTitolari($data->id_titolari);
        //$registro->setMittente($data->mittente);
        //$registro->setNumeroFascicolo($data->numero_fascicolo);
        $registro->setIdFascicoli($data->id_fascicoli);
        //$registro->setNumeroSottofascicolo($data->numero_sottofascicolo);
        $registro->setOggetto($data->oggetto);
        //$registro->setPropostaCipe($data->proposta_cipe);
        $registro->setProtocolloArrivo($data->protocollo_arrivo);

        if (isset($data->protocollo_mittente)) {
            $registro->setProtocolloMittente($data->protocollo_mittente);
        } else {
            $registro->setProtocolloMittente("");
        }


        //ricavo gli tutte le amministrazioni passate dalla tendina
        $array_id_amministrazioni = explode(",", $data->id_amministrazioni);
        //ricavo gli id di tutte i tags passati dalla tendina

        $array_id_tags = explode(",", $data->id_tags);

        $em->persist($registro);
        $em->flush(); //esegue query

        $id_registro_creato = $registro->getId();
        //AMMINISTRAZIONI
        //creo le relazioni da creare nella tabella RelAmministrazioniRegistri
        foreach ($array_id_amministrazioni as $item) {
            $relAmministrazioniRegistri = new relAmministrazioniRegistri();
            $relAmministrazioniRegistri->setIdRegistri($id_registro_creato);
            $relAmministrazioniRegistri->setIdAmministrazioni($item);
            //aggiorno (in realt� ricreo) le relazioni del registro
            $em->persist($relAmministrazioniRegistri); //create
        }

       // TAGS
       //creo le relazioni da creare nella tabella RelTagsRegistri
       foreach ($array_id_tags as $item) {
           $relTagsRegistri = new RelTagsRegistri();
           $relTagsRegistri->setIdRegistri($id_registro_creato);
           $relTagsRegistri->setIdTags($item);
           //aggiorno (in realt� ricreo) le relazioni del fascicolo
           $em->persist($relTagsRegistri); //create
       }

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("registri");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->flush(); //esegue query


        $registro = $this->serialize($registro);
        $registro = json_decode($registro, true); //trasformo in array
        $registro['data_arrivo'] = strtotime($this->zulu_to_rome($registro['data_arrivo'])) * 1000;

        $response = new Response(json_encode($registro), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


	/**
     * @SWG\Delete(
     *     path="/api/registri/{id}",
     *     summary="Eliminazione registro",
     *     tags={"Registri"},
     *     operationId="idRegistro",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id dell'ufficio",
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
     * @Route("/registri/{id}", name="registri_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_REGISTRI')")
     */
    public function registriItemDeleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:Registri');
        $registro = $repository->findOneById($id);

        $repository_rel = $em->getRepository('UserBundle:RelAmministrazioniRegistri');
        $relAmministrazioniRegistri_delete = $repository_rel->findByIdRegistri($id);//ricavo tutte le relazioni con l'id del registro

        $repository_rel_tags = $em->getRepository('UserBundle:RelTagsRegistri');
        $relTagsRegistri_delete = $repository_rel_tags->findByIdRegistri($id);//ricavo tutte le relazioni con l'id del registro

        //rimuovo tutte le relazioni con l'id del registro
        foreach ($relAmministrazioniRegistri_delete as $relAmministrazioniRegistri_delete) {
            $em->remove($relAmministrazioniRegistri_delete);
        }

        //rimuovo tutte le relazioni con l'id del registro
        foreach ($relTagsRegistri_delete as $relTagsRegistri_delete) {
            $em->remove($relTagsRegistri_delete);
        }
        $em->remove($registro); //delete

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("registri");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->flush(); //esegue query 

        $response = new Response($this->serialize($registro), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


	/**
     * @SWG\Post(
     *     path="/api/registri/{id}/upload",
     *     summary="Upload files di un registro",
     *     tags={"Registri"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"id":1,"codice":0,"denominazione":"Documenti di seduta","descrizione":"Telex, Appunto generale, passi, etc...","id_uffici":2}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"),
	 *     @SWG\Response(response=409, description="Il file e' troppo grande. (max 25 MB)"),
	 *     @SWG\Response(response=409, description="Tipo di file non permesso (solo PDF)")
     * )
     */


    /**
     * @Route("/registri/{id}/upload", name="uploadRegistri")
     * @Method("POST")
     */
    public function uploadRegistriAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:Registri');
        $registro = $repository->findOneById($id);
        $repository_titolario = $em->getRepository('UserBundle:Titolari');
        $titolario = $repository_titolario->findOneById($registro->getIdTitolari());
        $repository_fascicolo = $em->getRepository('UserBundle:Fascicoli');
        $fascicolo = $repository_fascicolo->findOneById($registro->getIdFascicoli());

        $codice_titolario = $titolario->getCodice();
        $denominazione_titolario = $titolario->getDenominazione();
        $denominazione_titolario = $this->sostituisciAccenti($denominazione_titolario);
        $denominazione_titolario = strtoupper($denominazione_titolario);
        $codice_fascicolo = $fascicolo->getNumeroFascicolo();

        $path_file = Costanti::URL_ALLEGATI_REGISTRI . "/" . $codice_titolario . " - " . $denominazione_titolario . "/" . $codice_fascicolo . "/";



        $file = $request->files->get('file');


        //$response = new Response(json_encode($path_file), Response::HTTP_OK);
        //return $this->setBaseHeaders($response, "upload");

        //controllo se è un file che è stato già caricato
        $check_file = explode("-", $file->getClientOriginalName());
        if (is_numeric($check_file[0])) {
            $nome_file = $file->getClientOriginalName();

            $repository_allegato = $em->getRepository('UserBundle:Allegati');
            $allegato = $repository_allegato->findOneByFile($path_file . $nome_file);

            $id_allegato_creato = $allegato->getId();

        } else {
            $nome_file = $id . "-" . $file->getClientOriginalName();

            $nome_file = $this->sostituisciAccenti($nome_file);

            //memorizzo il file nel database
            $allegato = new Allegati();
            $allegato->setData(new \DateTime());
            $allegato->setFile($path_file . $nome_file);

            $em->persist($allegato);
            $em->flush(); //esegue query

            $id_allegato_creato = $allegato->getId();

            $allegatoRel = new RelAllegatiRegistri();
            $allegatoRel->setIdAllegati($id_allegato_creato);
            $allegatoRel->setIdRegistri($id);
        }


        $array = array(
            'id' => $id_allegato_creato,
            'id_registri' => $id,
            'data' => filemtime($file) * 1000,
            'dimensione' => $file->getClientSize(),
            'nome' => $nome_file,
            'relURI' => $path_file . $nome_file,
            'tipo' => $this->getExtension($file->getMimeType()),
            'mime_tipe' => $file->getMimeType(),

            //'titolario' => $codice_titolario,
            //'fascicolo' => $codice_fascicolo,
        );

        //se il file è maggiore di 25 MB
        if ($file->getClientSize() > 26214400) {
            $response_array = array("error" => ["code" => 409, "message" => "Il file e' troppo grande. (max 25 MB)"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }
        //controllo su i tipi di file ammessi
        if (!in_array($file->getMimeType(), array(
            'image/jpeg',
            'image/png',
            'image/gif',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            '"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            '"image/tiff'))) {
            $response_array = array("error" => ["code" => 409, "message" => "Questo tipo di file non e' permesso."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }





        if (!is_numeric($check_file[0])) {
            try {
                $em->persist($allegatoRel);
                $em->flush(); //esegue query

                //$response = new Response(json_encode(Costanti::PATH_ASSOLUTO_ALLEGATI. $path_file), Response::HTTP_OK);
                //return $this->setBaseHeaders($response, "upload");


                //copio fisicamente il file
                $file->move($_SERVER['DOCUMENT_ROOT'] . "/" . Costanti::PATH_IN_SERVER . $path_file, $nome_file);

            } catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                echo "Exception Found - " . $ex->getMessage() . "<br/>";
            }
        }


        $response = new Response(json_encode($array), Response::HTTP_OK);
        return $this->setBaseHeaders($response, "upload");


    }

	/**
     * @SWG\Delete(
     *     path="/registri/{id}/upload/{idallegato}",
     *     summary="Eliminazione file di un registro",
     *     tags={"Registri"},
     *     operationId="idAllegato",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="query",
     *         description="id del file allegato",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"),
	 *     @SWG\Response(response=409, description="Il file non esiste")
     * )
     */


    /**
     * @Route("/registri/{id}/upload/{idallegato}", name="uploadDeleteRegistri")
     * @Method("DELETE")
     */
    public function allegatiItemDeleteAction(Request $request, $id, $idallegato)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:RelAllegatiRegistri');
        $relazione_allegato = $repository->findBy(array('idAllegati' => $idallegato, 'idRegistri' => $id));
        //$relazione_allegato = $repository->findOneById($idallegato);

        $repository_file = $em->getRepository('UserBundle:Allegati');
        $file = $repository_file->findOneById($idallegato);

        //$idRelAllegatiRegistri = $relazione_allegato[0]->getId();

        if (!$relazione_allegato[0]) {
            $response_array = array("error" => ["code" => 409, "message" => "Questo file non e' allegato a questo registro."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {
            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("registri");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

            $response = new Response($this->serialize($relazione_allegato[0]), Response::HTTP_OK);

            try {
                $em->remove($relazione_allegato[0]); //delete
                $em->flush(); //esegue l'update

                //elimino fisicamente il file
                unlink($file->getFile()); //il path
            } catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                echo "Exception Found - " . $ex->getMessage() . "<br/>";
            }


            return $this->setBaseHeaders($response);
        }
    }




    /**
     * @Route("/registri/{id}/zip", name="allegatiZipRegistri")
     * @Method("GET")
     */
    public function allegatiZipAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:RelAllegatiRegistri');
        $relAllegatiRegistri = $repository->findByIdRegistri($id);

        $zip = new \ZipArchive();
        $zip_name = date("Ymd-His")."-AllegatiRegistro". $id .".zip"; // Zip name
        if ($zip->open($zip_name, \ZipArchive::CREATE)!==TRUE) {
            exit("cannot open <$zip_name>\n");
        }



        if (count($relAllegatiRegistri) > 0) {
            foreach ($relAllegatiRegistri as $relAllegatiRegistri) {

                $repository2 = $em->getRepository('UserBundle:Allegati');
                $AllegatiRegistri = $repository2->findOneById($relAllegatiRegistri->getIdAllegati());

                $pathFile = $AllegatiRegistri->getFile();

                if (file_exists($pathFile)) {
                    $zip->addFile($pathFile, $pathFile);
                } else {
                    echo "file does not exist";
                }
            }
        } else {

            //$response = new Response("nessun file allegato", Response::HTTP_OK);
            //return $this->setBaseHeaders($response);


            $response = new Response(
                '<html><body>nessun file allegato</body></html>',
                Response::HTTP_OK
            );

            $response->headers->set('Content-Type', 'text/html');

            return $response;
        }

        $zip->close();

        header('Content-type: application/zip');
        header('Content-Disposition: attachment; filename="'.basename($zip_name).'"');
        header("Content-length: " . filesize($zip_name));
        header("Pragma: no-cache");
        header("Expires: 0");

        ob_clean();
        flush();

        readfile($zip_name);

        unlink($zip_name);

        $response = new Response(json_encode("FINE"), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }




    /**
     * @Route("/registri", name="registri_options")
     * @Method("OPTIONS")
     */
    public function registriOptions(Request $request)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/registri/{id}", name="registri_item_options")
     * @Method("OPTIONS")
     */
    public function registriItemOptions(Request $request, $id)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/registri/{id}/upload", name="registriUpload_item_options")
     * @Method("OPTIONS")
     */
    public function registriUploadItemOptions(Request $request, $id)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/registri/{id}/upload/{idallegato}", name="registriUploadDelete_item_options")
     * @Method("OPTIONS")
     */
    public function registriUploadDeleteItemOptions(Request $request, $id, $idallegato)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


}
