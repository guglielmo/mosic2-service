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
     * @Route("/registri", name="registri")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_REGISTRI')")
     */
    public function registriAction(Request $request)
    {

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
        $serialize = $this->serialize($registri);

        //funzione per formattare le date del json
        $serialize = $this->formatDateJsonArrayCustom(json_decode($serialize), array('data_arrivo', 'data_mittente'));

        //aggiungo i tags
        $repositoryTags = $this->getDoctrine()->getRepository('UserBundle:RelTagsRegistri');
        foreach ($serialize as $item => $value) {
            $tags = $repositoryTags->findBy(["idRegistri" => $value->id]);
            $tags = json_decode($this->serialize($tags));
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
     * @Route("/registri/{id}", name="registri_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_REGISTRI')")
     */
    public function registriItemSaveAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

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
        $registro->setDataMittente(new \DateTime($this->formatDateStringCustom($data->data_mittente)));
        $registro->setDataArrivo(new \DateTime($this->formatDateStringCustom($data->data_arrivo)));
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
     * @Route("/registri", name="registri_item_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREATE_REGISTRI')")
     */
    public function registriItemCreateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $registro = new Registri();

        $registro->setAnnotazioni($data->annotazioni);
        //$registro->setCodiceTitolario($data->codice_titolario);
        $registro->setDataMittente(new \DateTime($this->formatDateStringCustom($data->data_mittente)));
        $registro->setDataArrivo(new \DateTime($this->formatDateStringCustom($data->data_arrivo)));
        //$registro->setIdAmministrazione($data->id_amministrazione);
        //$registro->setIdFascicolo($data->id_fascicoli);
        $registro->setIdMittenti($data->id_mittenti);
        //$registro->setIdSottofascicoli($data->id_sottofascicoli);
        $registro->setIdTitolari($data->id_titolari);
        //$registro->setMittente($data->mittente);
        //$registro->setNumeroFascicolo($data->numero_fascicolo);
        $registro->setIdFascicoli($data->id_fascicoli);
        //$registro->setNumeroSottofascicolo($data->numero_sottofascicolo);
        $registro->setOggetto($data->oggetto);
        //$registro->setPropostaCipe($data->proposta_cipe);
        $registro->setProtocolloArrivo($data->protocollo_arrivo);
        $registro->setProtocolloMittente($data->protocollo_mittente);


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

        $response = new Response($this->serialize($registro), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


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

        //$response = new Response(json_encode($path_file), Response::HTTP_OK);
        //return $this->setBaseHeaders($response, "upload");


        $file = $request->files->get('file');

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
        if (!in_array($file->getMimeType(), array('image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/msword'))) {
            $response_array = array("error" => ["code" => 409, "message" => "Questo tipo di file non e' permesso."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }


        if (!is_numeric($check_file[0])) {
            try {
                $em->persist($allegatoRel);
                $em->flush(); //esegue query

                //copio fisicamente il file
                $file->move(Costanti::PATH_ASSOLUTO_ALLEGATI. "/" . $path_file, $nome_file);

            } catch (\Doctrine\ORM\EntityNotFoundException $ex) {
                echo "Exception Found - " . $ex->getMessage() . "<br/>";
            }
        }


        $response = new Response(json_encode($array), Response::HTTP_OK);
        return $this->setBaseHeaders($response, "upload");


    }


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
