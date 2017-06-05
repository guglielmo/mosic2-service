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
use UserBundle\Entity\Allegati;
use UserBundle\Entity\PreCipe;
use UserBundle\Entity\PreCipeOdg;
use UserBundle\Entity\LastUpdates;
use UserBundle\Entity\RelAllegatiPreCipe;
use UserBundle\Entity\Registri;
use UserBundle\Entity\Costanti;
use Doctrine\ORM\EntityNotFoundException;
use UserBundle\Entity\RelRegistriOdg;
use Sensio\Bundle\BuzzBundle;
use UserBundle\Entity\RelUfficiPreCipe;


class PreCipeController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;


	
	/**
     * @SWG\Tag(
     *   name="Pre-Cipe",
     *   description="Tutte le Api dei PreCipe"
     * )
     */


    /**
     * @SWG\Get(
     *     path="/api/precipe",
     *     summary="Lista Pre-Cipe",
     *     tags={"Pre-Cipe"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":144,"limit":"99999","offset":0,"data":{{"id":151,"data":1496008800000
,"ufficiale_riunione":"0","public_reserved_status":"","public_reserved_url":"","allegati_TLX":"","allegati_APG"
:"","allegati_OSS":""}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     */


    /**
     * @Route("/precipe", name="precipe")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_PRECIPE')")
     */
    public function precipeAction(Request $request)
    {
        //prendo i parametri get
        $limit = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;
        $sortBy = ($request->query->get('sort_by') != "") ? $request->query->get('sort_by') : 'id';
        $sortType = ($request->query->get('sort_order') != "") ? $request->query->get('sort_order') : "DESC";

        $repository = $this->getDoctrine()->getRepository('UserBundle:PreCipe');
        $precipe = $repository->listaPrecipe($limit, $offset, $sortBy, $sortType);


        $arrayPrecipe = array();

        foreach ($precipe as $item => $value) {
            $allegatiTLX = "";
            $allegatiAPG = "";
            $allegatiOSS = "";

            $allegati = $repository->getAllegatiByIdPreCipe($value->getId());
            $allegati = json_decode($this->serialize($allegati));
            foreach ($allegati as $i => $v) {
                switch ($v->tipologia) {
                    case "TLX":
                        $allegatiTLX[] = $v;
                        break;
                    case "APG":
                        $allegatiAPG[] = $v;
                        break;
                    case "OSS":
                        $allegatiOSS[] = $v;
                        break;
                }
                //print_r($v->tipologia);
            }

            $arrayTemp = json_decode($this->serialize($value));
            $arrayTemp->allegati_TLX = $allegatiTLX;
            $arrayTemp->allegati_APG = $allegatiAPG;
            $arrayTemp->allegati_OSS = $allegatiOSS;
            $arrayPrecipe[] = $arrayTemp;
        }

        //$serialize = json_decode($this->serialize($precipe));

        //print_r($tags);

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($precipe),
            "limit" => $limit,
            "offset" => $offset,
            "data" => $this->formatDateJsonArrayCustom($arrayPrecipe, array('data'))
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


	/**
     * @SWG\Get(
     *     path="/api/precipe/{id}",
     *     summary="Singolo Pre-Cipe",
     *     tags={"Pre-Cipe"},
     *     operationId="idPreCipe",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id del Pre-Cipe",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":144,"limit":"99999","offset":0,"data":{{"id":151,"data":1496008800000
,"ufficiale_riunione":"0","public_reserved_status":"","public_reserved_url":"","allegati_TLX":"","allegati_APG"
:"","allegati_OSS":""}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))

     * )
     */

    /**
     * @Route("/precipe/{id}", name="precipe_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_PRECIPE')")
     */
    public function precipeItemAction(Request $request, $id)
    {

        $repository = $this->getDoctrine()->getRepository('UserBundle:PreCipe');
        $precipe = $repository->findOneById($id);

        $repositoryPreCipeOdg = $this->getDoctrine()->getRepository('UserBundle:PreCipeOdg');
        $ordini = $repositoryPreCipeOdg->findByIdPreCipe($id);

        $repositoryRelRegistriOdg = $this->getDoctrine()->getRepository('UserBundle:RelRegistriOdg');
        $repositoryRelUfficiPreCipe = $this->getDoctrine()->getRepository('UserBundle:RelUfficiPreCipe');

        foreach ($ordini as $i => $v) {
            $registri_precipe = $repositoryRelRegistriOdg->findByIdOdg($v->getId());
            $registri_precipe = $this->mergeRegistriOdg($this->serialize($registri_precipe));
            $uffici_precipe = $repositoryRelUfficiPreCipe->findByIdOdgPreCipe($v->getId());
            $uffici_precipe = $this->mergeUfficiOdg($this->serialize($uffici_precipe));

            //$registri_precipe = json_decode($this->serialize($registri_precipe));
            $arrayTemp = json_decode($this->serialize($v));
            switch (count($registri_precipe)) {
                case 1:
                    $arrayTemp->id_registri = [$registri_precipe];
                    break;
                case 0:
                    $arrayTemp->id_registri = [];
                    break;
                default:
                    $arrayTemp->id_registri = $registri_precipe;
            }
            switch (count($uffici_precipe)) {
                case 1:
                    $arrayTemp->id_uffici = [$uffici_precipe];
                    break;
                case 0:
                    $arrayTemp->id_uffici = [];
                    break;
                default:
                    $arrayTemp->id_uffici = $uffici_precipe;
            }

            //ricavo gli allegati per ogni registro nella lista $arrayTemp->id_registri
            foreach ($arrayTemp->id_registri as $i => $v) {
                $repositoryRegistri = $this->getDoctrine()->getRepository('UserBundle:Registri');
                $allegatiR = $repositoryRegistri->getAllegatiByIdRegistro($v);
                $arrayTemp->allegati[$v] = $allegatiR;
                $arrayTemp->allegati_esclusi = [];
                $arrayTemp->allegati_esclusi_approvati = [];
            }

            $arrayOrdini[] = $arrayTemp;
        }


        $allegati = $repository->getAllegatiByIdPreCipe($id);
        $allegati = json_decode($this->serialize($allegati));

        $allegatiTLX = "";
        $allegatiAPG = "";
        $allegatiOSS = "";

        foreach ($allegati as $i => $v) {
            switch ($v->tipologia) {
                case "TLX":
                    $allegatiTLX[] = $v;
                    break;
                case "APG":
                    $allegatiAPG[] = $v;
                    break;
                case "OSS":
                    $allegatiOSS[] = $v;
                    break;
            }
        }

        $precipeTemp = json_decode($this->serialize($precipe));
        $precipeTemp->precipe_odg = $arrayOrdini;
        $precipeTemp->allegati_TLX = $allegatiTLX;
        $precipeTemp->allegati_APG = $allegatiAPG;
        $precipeTemp->allegati_OSS = $allegatiOSS;

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($precipe),
            "limit" => 1,
            "offset" => 0,
            "data" => $this->formatDateJsonArrayCustom([$precipeTemp], array('data'))[0]
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


	/**
     * @SWG\Put(
     *     path="/api/precipe/{id}",
     *     summary="Salvataggio Pre-Cipe e relativi OdG",
     *     tags={"Pre-Cipe"},
     *     operationId="idPreCipe",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="query",
     *         description="id del Pre-Cipe",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
	 *     @SWG\Parameter(
     *         name="precipe",
     *         in="body",
     *         description="Richiesta",
     *         required=true,
 	 *         @SWG\Schema(
	 *				type="array",
     *              @SWG\Items(
     *                 type="object",
     *                 	@SWG\Property(property="id", type="integer"),
	 *                 	@SWG\Property(property="data", type="string"),
	 *					@SWG\Property(property="ufficiale_riunione", type="integer"),
	 *					@SWG\Property(property="public_reserved_status", type="integer"),
	 *					@SWG\Property(property="precipe_odg",
	 *						type="array",
	 *						@SWG\Items(
	 *							@SWG\Property(property="id", type="integer"),
	 *							@SWG\Property(property="id_pre_cipe", type="integer"),
	 *							@SWG\Property(property="progressivo", type="integer"),
	 *							@SWG\Property(property="id_titolari", type="integer"),
	 *							@SWG\Property(property="id_fascicoli", type="integer"),
	 *							@SWG\Property(property="id_argomenti", type="integer"),
	 *							@SWG\Property(property="id_uffici", type="array"),
	 *							@SWG\Property(property="ordine", type="integer"),
	 *							@SWG\Property(property="denominazione", type="string"),
	 *							@SWG\Property(property="risultanza", type="integer"),
	 *							@SWG\Property(property="annotazioni", type="string"),
	 *							@SWG\Property(property="stato", type="integer"),
	 *							@SWG\Property(property="id_registri", type="array"),
	 *							@SWG\Property(property="allegati",
	 *								type="array",
	 *								@SWG\Items(
	 *									@SWG\Property(property="id", type="integer"),
	 *									@SWG\Property(property="data", type="string"),
	 *									@SWG\Property(property="nome", type="string"),
	 *									@SWG\Property(property="tipo", type="string"),
	 *									@SWG\Property(property="relURI", type="string"),
	 *									@SWG\Property(property="dimensione", type="string")
	 *								)	 
	 *						)
	 *					),
	 *					@SWG\Property(property="allegati_TLX", type="string"),
     *					@SWG\Property(property="allegati_APG", type="string"),
     *					@SWG\Property(property="allegati_OSS", type="string")
     *             )
	 *			),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */

    /**
     * @Route("/precipe/{id}", name="precipe_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_UFFICI')")
     */
    public function precipeItemSaveAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $repository = $em->getRepository('UserBundle:PreCipe');
        $precipe = $repository->findOneById($data->id);

        $precipe->setData(new \DateTime($this->formatDateStringCustom($data->data)));
        //$precipe->setData(new \DateTime('2016-07-18'));

        $repository_odg = $em->getRepository('UserBundle:PreCipeOdg');
        $repository_rel_registri_odg = $em->getRepository('UserBundle:RelRegistriOdg');
        $repository_rel_uffici_odg = $em->getRepository('UserBundle:RelUfficiPreCipe');

        //salvo ogni odg del precipe
        foreach ($data->precipe_odg as $item => $value) {

            if (isset($value->id)) {
                $precipeodg = $repository_odg->findOneById((int)$value->id);
            } else {
                $precipeodg = new PreCipeOdg();
            }

            $precipeodg->setIdPrecipe($id);
            //$precipeodg->setProgressivo($value->progressivo);
            $precipeodg->setIdTitolari($value->id_titolari);
            $precipeodg->setIdFascicoli($value->id_fascicoli);
            //$precipeodg->setIdArgomenti($value->id_argomenti);
            $precipeodg->setOrdine($value->ordine);
            $precipeodg->setDenominazione($value->denominazione);
            $precipeodg->setRisultanza($value->risultanza);
            $precipeodg->setAnnotazioni($value->annotazioni);
            //$precipeodg->setStato($value->stato);

            if (!isset($value->id)) {
                $em->persist($precipeodg);
                $em->flush(); //esegue l'update
                $value->id = $precipeodg->getId();
            }

            $relRegistriOdg_delete = $repository_rel_registri_odg->findByIdOdg((int)$value->id);
            // REGISTRI
            //rimuovo tutte le relazioni con l'id dell'odg (per poi riaggiornale ovvero ricrearle)
            foreach ($relRegistriOdg_delete as $relRegistriOdg_delete) {
                $em->remove($relRegistriOdg_delete);
            }
            //creo le relazioni da aggiornare nella tabella RelRegistriOdg
            foreach ($value->id_registri as $k) {
                $relRegistriOdg = new RelRegistriOdg();
                $relRegistriOdg->setIdOdg((int)$value->id);
                $relRegistriOdg->setIdRegistri($k);

                //aggiorno (in realt� ricreo) le relazioni del registro
                $em->persist($relRegistriOdg); //create
            }
            $relUffici_delete = $repository_rel_uffici_odg->findByIdOdgPreCipe((int)$value->id);
            // UFFICI
            foreach ($relUffici_delete as $relUffici_delete) {
                $em->remove($relUffici_delete);
            }
            foreach ($value->id_uffici as $k) {
                $relUfficiOdg = new RelUfficiPreCipe();
                $relUfficiOdg->setIdOdgPreCipe((int)$value->id);
                $relUfficiOdg->setIdUffici($k);

                //aggiorno (in realt� ricreo) le relazioni del registro
                $em->persist($relUfficiOdg); //create
            }

            $em->persist($precipeodg);
            $em->flush(); //esegue l'update
            //$response = new Response(json_encode($value->id_registri), Response::HTTP_OK);
            //return $this->setBaseHeaders($response);
        }


        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("precipe");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->flush(); //esegue l'update

        $response = new Response(json_encode($data), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }



	/**
     * @SWG\Post(
     *     path="/api/precipe",
     *     summary="Creazione Pre-Cipe e relativi OdG",
     *     tags={"Pre-Cipe"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */

    /**
     * @Route("/precipe", name="precipe_item_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREATE_UFFICI')")
     */
    public function precipeItemCreateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $precipe = new PreCipe();
        $precipe->setData(new \DateTime($this->formatDateStringCustom($data->data)));

        $em->persist($precipe);
        $em->flush(); //esegue l'update

        //salvo ogni odg del precipe
        foreach ($data->precipe_odg as $item => $value) {
            $precipeodg = new PreCipeOdg();

            $precipeodg->setIdPrecipe($precipe->getId());
            //$precipeodg->setProgressivo($value->progressivo);
            $precipeodg->setIdTitolari($value->id_titolari);
            $precipeodg->setIdFascicoli($value->id_fascicoli);
            //$precipeodg->setIdArgomenti($value->id_argomenti);
            $precipeodg->setOrdine($value->ordine);
            $precipeodg->setDenominazione($value->denominazione);
            $precipeodg->setRisultanza($value->risultanza);
            $precipeodg->setAnnotazioni($value->annotazioni);
            //$precipeodg->setStato($value->stato);

            $em->persist($precipeodg);
            $em->flush(); //esegue l'update

            //creo le relazioni da aggiornare nella tabella RelRegistriOdg
            foreach ($value->id_registri as $k) {
                $relRegistriOdg = new RelRegistriOdg();
                $relRegistriOdg->setIdOdg($precipeodg->getId());
                $relRegistriOdg->setIdRegistri($k);

                //aggiorno (in realt� ricreo) le relazioni del registro
                $em->persist($relRegistriOdg); //create
            }
            foreach ($value->id_uffici as $k) {
                $relUfficiOdg = new RelUfficiPreCipe();
                $relUfficiOdg->setIdOdgPreCipe($precipeodg->getId());
                $relUfficiOdg->setIdUffici($k);

                //aggiorno (in realt� ricreo) le relazioni del registro
                $em->persist($relUfficiOdg); //create
            }



            $em->persist($precipeodg);
            $em->flush(); //esegue l'update
            //$response = new Response(json_encode($value->id_registri), Response::HTTP_OK);
            //return $this->setBaseHeaders($response);
        }






        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("precipe");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($precipe);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($precipe), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


	/**
     * @SWG\Delete(
     *     path="/api/precipe/{id}",
     *     summary="Eliminazione Pre-Cipe",
     *     tags={"Pre-Cipe"},
     *     operationId="idCPreCipe",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id del Pre-Cipe",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"),
	 *     @SWG\Response(response=409, description="Il Pre-Cipe contiene degli Ordini del Giorno, impossibile eliminarlo.")
     * )
     */


    /**
     * @Route("/precipe/{id}", name="precipe_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_UFFICI')")
     */
    public function precipeItemDeleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:PreCipe');
        $precipe = $repository->findOneById($id);

        $repositoryPreCipeOdg = $em->getRepository('UserBundle:PreCipeOdg');
        $ordini = $repositoryPreCipeOdg->findOneByIdPreCipe($id);


        if ($ordini) {
            $response_array = array("error" => ["code" => 409, "message" => "Il Pre-Cipe contiene degli Ordini del Giorno, impossibile eliminarlo."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {

            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("precipe");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente


            $em->remove($precipe); //delete
            $em->flush(); //esegue l'update

            $response = new Response($this->serialize($precipe), Response::HTTP_OK);
            return $this->setBaseHeaders($response);
        }
    }


	/**
     * @SWG\Post(
     *     path="/api/precipe/{id}/{tipo}/upload",
     *     summary="Upload files di un Pre-Cipe",
     *     tags={"Pre-Cipe"},
     *     produces={"application/json"},
	 *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id del Pre-Cipe",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
	 *     @SWG\Parameter(
     *         name="tipo",
     *         in="path",
     *         description="tipo di allegato [TLX, APG, OSS]",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),
     *     ),
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
     * @Route("/precipe/{id}/{tipo}/upload", name="uploadPreCipe")
     * @Method("POST")
     */
    public function uploadPreCipeAction(Request $request, $id, $tipo)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:PreCipe');
        $precipe = $repository->findOneById($id);
        $dataPrecipe = $precipe->getData()->format('Y-m-d');


        $path_file = Costanti::URL_ALLEGATI_PRECIPE . $dataPrecipe . "/" . $tipo . "/";

        $file = $request->files->get('file');


        $nome_file = $file->getClientOriginalName();
        $nome_file = $this->sostituisciAccenti($nome_file);


        //memorizzo il file nel database
        $allegato = new Allegati();
        $allegato->setData(new \DateTime());
        $allegato->setFile($path_file . $nome_file);


        $em->persist($allegato);
        $em->flush(); //esegue query

        $id_allegato_creato = $allegato->getId();

        $allegatoRel = new RelAllegatiPreCipe();
        $allegatoRel->setIdAllegati($id_allegato_creato);
        $allegatoRel->setIdPrecipe($id);
        $allegatoRel->setTipo($tipo);


        $array = array(
            'id' => $id_allegato_creato,
            'id_precipe' => $id,
            'data' => filemtime($file) * 1000,
            'dimensione' => $file->getClientSize(),
            'nome' => $nome_file,
            'relURI' => $path_file . $nome_file,
            'tipo' => $this->getExtension($file->getMimeType()),
            'mime_tipe' => $file->getMimeType(),
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


        try {
            $em->persist($allegatoRel);
            $em->flush(); //esegue query

            //copio fisicamente il file
            $file->move(Costanti::PATH_ASSOLUTO_ALLEGATI. "/" . $path_file, $nome_file);

        } catch (\Doctrine\ORM\EntityNotFoundException $ex) {
            echo "Exception Found - " . $ex->getMessage() . "<br/>";
        }


        $response = new Response(json_encode($array), Response::HTTP_OK);
        return $this->setBaseHeaders($response, "upload");
    }


	/**
     * @SWG\Delete(
     *     path="/api/precipe/{id}/{tipo}/upload/{idallegato}",
     *     summary="Eliminazione file di un Pre-Cipe",
     *     tags={"Pre-Cipe"},
     *     operationId="idAllegato",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id del Cipe",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
	 *     @SWG\Parameter(
     *         name="tipo",
     *         in="path",
     *         description="tipo di allegato [TLX, APG, OSS]",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),
     *     ),
	 *     @SWG\Parameter(
     *         name="idallegato",
     *         in="path",
     *         description="Id dell'allegato da eliminare",
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
     * @Route("/precipe/{id}/{tipo}/upload/{idallegato}", name="uploadDeletePrecipe")
     * @Method("DELETE")
     */
    public function precipeAllegatiItemDeleteAction(Request $request, $id, $tipo, $idallegato)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:RelAllegatiPreCipe');
        $relazione_allegato = $repository->findBy(array('idAllegati' => $idallegato, 'idPreCipe' => $id));
        //$relazione_allegato = $repository->findOneById($idallegato);

        $repository_file = $em->getRepository('UserBundle:Allegati');
        $file = $repository_file->findOneById($idallegato);

        //$idRelAllegatiRegistri = $relazione_allegato[0]->getId();

        if (!$relazione_allegato[0]) {
            $response_array = array("error" => ["code" => 409, "message" => "Questo file non e' allegato a questo precipe."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {
            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("precipe");
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
     * @Route("/areariservata/precipe/{id}", name="precipe_area_riservata_delete")
     * @Method("DELETE")
     * //@Security("is_granted('ROLE_DELETE_AREARISERVATA_PRECIPE')")
     */
    public function precipeAreaRiservataDeleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserBundle:PreCipe');
        $precipe = $repository->findOneById($id);



        //#########
        //######### chiamo l'api per il LOGIN
        //#########
        $browser = $this->container->get('buzz');

        $fields = array("username"=>"mosic", "password" => "cowpony-butter-vizor");
        $response = $browser->submit("http://area-riservata.mosic2.celata.com/api-token-auth/", $fields, "POST");
        $content = json_decode($response->getContent());
        //$response = json_decode($response->getContent());
        $token = $content->token;


        //Aggiorno lo stato del precipe
        if ($response->getStatusCode() == 200) {
            $response_array = array("success" => ["code" => 200, "message" => "Procedura presa in carico"]);
            $precipe->setPublicReservedStatus(json_encode($response_array));

            $em->persist($precipe);
            $em->flush(); //esegue l'update

            $response_array = array(
                "response" => Response::HTTP_OK,
                "data" => "Procedura presa in carico"
            );

        } else {
            $response_array = array("error" => ["code" => 401, "message" => "Errore nella login"]);
            $precipe->setPublicReservedStatus(json_encode($response_array));

            $em->persist($precipe);
            $em->flush(); //esegue l'update

            $response_array = array(
                "response" => 401,
                "data" => "Errore nella login"
            );
            $response = new Response(json_encode($response_array), Response::HTTP_OK);
            return $this->setBaseHeaders($response);
        }


        //#########
        //######### chiamo l'api per prendere l'url dell'area riservata
        //#########
        $browser = $this->container->get('buzz');

        $headers = array(
            'Accept' => '*/*',
            'Content-Type' => 'application/json',
            'Cache-Control' => 'no-cache',
            'Authorization' => 'JWT ' . $token
            // Add any other header needed by the API
        );
        $response = $browser->get("http://area-riservata.mosic2.celata.com/seduta/precipe/". $id, $headers);
        $content = json_decode($response->getContent());
        $id_precipe = $content->id;




        //#########
        //######### chiamo l'api della delete
        //#########
        $browser = $this->container->get('buzz');

        $headers = array(
            'Accept' => '*/*',
            'Cache-Control' => 'no-cache',
            'Authorization' => 'JWT ' . $token
            // Add any other header needed by the API
        );

        $response = $browser->delete("http://area-riservata.mosic2.celata.com/precipe/". $id_precipe, $headers);

        //Aggiorno lo stato del precipe
        if ($response->getStatusCode() == 204) {
            $response_array = array(
                "response" => 204,
                "data" => array("message" => "Documenti e o.d.g. rimossi dall'area riservata")
            );
            $precipe->setPublicReservedStatus("");
            $precipe->setPublicReservedUrl("");
            $em->persist($precipe);
            $em->flush(); //esegue l'update
        } else {
            $response_array = array("error" => ["code" => 409, "message" => "Errore nella rimozione del precipe"]);
        }

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);


    }

    /**
     * @Route("/areariservata/precipe/{id}", name="precipe_area_riservata")
     * @Method("GET")
     * //@Security("is_granted('ROLE_READ_AREARISERVATA_PRECIPE')")
     */
    public function precipeAreaRiservataAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserBundle:PreCipe');
        $precipe = $repository->findOneById($id);



        //#########
        //######### costruisco il json da mandare
        //#########

        $repository = $this->getDoctrine()->getRepository('UserBundle:PreCipe');
        $precipe = $repository->findOneById($id);

        $repositoryPreCipeOdg = $this->getDoctrine()->getRepository('UserBundle:PreCipeOdg');
        $ordini = $repositoryPreCipeOdg->findByIdPreCipe($id);

        $repositoryRelRegistriOdg = $this->getDoctrine()->getRepository('UserBundle:RelRegistriOdg');

        $limite_ordini =0;
        $array_no_doppioni = "";
        foreach ($ordini as $i => $v) {
            if ($limite_ordini < 100000) {
                $limite_ordini++;

                $registri_precipe = $repositoryRelRegistriOdg->findByIdOdg($v->getId());
                $registri_precipe = $this->mergeRegistriOdg($this->serialize($registri_precipe));



                //$registri_precipe = json_decode($this->serialize($registri_precipe));
                $arrayTemp = json_decode($this->serialize($v));


                $arrayTemp->id_punto_odg = $arrayTemp->id; unset($arrayTemp->id);

                switch (count($registri_precipe)) {
                    case 1:
                        $arrayTemp->id_registri = [$registri_precipe];
                        break;
                    case 0:
                        $arrayTemp->id_registri = [];
                        break;
                    default:
                        $arrayTemp->id_registri = $registri_precipe;
                }

                $array_allegati = "";
                //ricavo gli allegati per ogni registro nella lista $arrayTemp->id_registri
                foreach ($arrayTemp->id_registri as $i => $v) {
                    $repositoryRegistri = $this->getDoctrine()->getRepository('UserBundle:Registri');
                    $allegatiR = $repositoryRegistri->getAllegatiByIdRegistro($v);
                    foreach ($allegatiR as $i => $v) {
                        if (in_array($allegatiR[$i]['relURI'], $array_no_doppioni)) {
                           // unset($v);
                           // continue;
                        }
                        $array_no_doppioni[] = $allegatiR[$i]['relURI'];
                        $allegatiR[$i]['id_allegato'] = $allegatiR[$i]['id']; unset($allegatiR[$i]['id']);
                        $allegatiR[$i]['data'] = date("Y-m-d", $allegatiR[$i]['data'] / 1000);
                        if ($allegatiR[$i]['dimensione'] == false) {
                            $allegatiR[$i]['dimensione'] = 0;
                        }

                    }

                    $array_allegati[] = $allegatiR;
                }
                $arrayTemp->allegati = $allegatiR;

                $arrayOrdini[] = $arrayTemp;
            }
        }


        $precipeTemp = json_decode($this->serialize($precipe));
        $precipeTemp->ufficiale = $precipeTemp->ufficiale_riunione; unset($precipeTemp->ufficiale_riunione);
        $precipeTemp->precipe_odg = $arrayOrdini;
        $precipeTemp->data = substr($precipeTemp->data, 0, 10);

        foreach ($precipeTemp->precipe_odg as $item) {
            unset($item->id_registri);
            unset($item->id_pre_cipe);
            unset($item->progressivo);
            unset($item->id_titolari);
            unset($item->id_fascicoli);
            unset($item->id_argomenti);
            unset($item->id_uffici);
            unset($item->ordine);
            unset($item->risultanza);
            unset($item->annotazioni);
            unset($item->stato);
        }

        $precipeTemp->id_seduta = $precipeTemp->id; unset($precipeTemp->id);
        $precipeTemp->punti_odg = $precipeTemp->precipe_odg; unset($precipeTemp->precipe_odg);


        //print_r(json_encode($precipeTemp));
        //$response = new Response(json_encode("fine"), Response::HTTP_OK);
        //return $this->setBaseHeaders($response);



        $command = "/opt/php-5.6.25/bin/php -f mosic-script/precipe-area-riservata.php " . $id . " '". str_replace("'", " ",json_encode($precipeTemp)) ."'";
        exec( "$command > /dev/null &", $arrOutput );


        $response_array = array("success" => ["code" => 200, "message" => "Procedura presa in carico"]);

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);

        $response = new Response();
        return $this->setBaseHeaders($response);
    }




    /**
     * @Route("/areariservata/precipe/check/{id}", name="precipe_area_riservata_check")
     * @Method("GET")
     * //@Security("is_granted('ROLE_READ_AREARISERVATA_PRECIPE_CHECK')")
     */
    public function precipeAreaRiservataCheckAction(Request $request, $id) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:PreCipe');
        $precipe = $repository->findOneById($id);

        $status = $precipe->getPublicReservedStatus();
        $url = $precipe->getPublicReservedUrl();

        if ($status != "") {
            $array_temp = explode("(", $status);
            $array_temp2 = explode(",", $array_temp[1]);

            $response_array = array(
                "response" => Response::HTTP_OK,
                "data" => array(
                    "message" => $array_temp[0],
                    "files_uploaded" => (int) $array_temp2[0],
                    "files_total" =>(int) substr($array_temp2[1], 0,-1)
                )
            );
        } else {
            $response_array = array(
                "response" => Response::HTTP_OK,
                "data" => array(
                    "message" => "Pre-CIPE non pubblicato",
                    "files_uploaded" => 0,
                    "files_total" => 0
                )
            );
        }

        if ($url != "") {
            $response_array['data']['public_reserved_url'] = $url;
        }
        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);

    }












    /**
     * @Route("/precipe", name="precipe_options")
     * @Method("OPTIONS")
     */
    public function precipeOptions(Request $request)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/precipe/{id2}", name="precipe_item_options")
     * @Method("OPTIONS")
     */
    public function precipeItemOptions(Request $request, $id2)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/precipe/{id}/{tipo}/upload", name="PreCipeUploadDelete_item_options")
     * @Method("OPTIONS")
     */
    public function precipeUploadDeleteItemOptions(Request $request, $id, $tipo)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/precipe/{id}/{tipo}/upload/{idallegato}", name="PreCipeUploadDeleteIdAllegato_item_options")
     * @Method("OPTIONS")
     */
    public function precipeUploadDeleteIdAllegatoItemOptions(Request $request, $id, $tipo, $idallegato)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/areariservata/precipe/{id}", name="PreCipeAreaRiservata")
     * @Method("OPTIONS")
     */
    public function precipeAreaRiservataOptions(Request $request, $id)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/areariservata/precipe/check/{id}", name="provapost_options")
     * @Method("OPTIONS")
     */
    public function provaPostOptions(Request $request, $id)
    {
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
