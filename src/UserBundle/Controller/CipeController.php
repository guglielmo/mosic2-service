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
use UserBundle\Entity\Cipe;
use UserBundle\Entity\CipeOdg;
use UserBundle\Entity\LastUpdates;
use UserBundle\Entity\RelAllegatiCipe;
use UserBundle\Entity\Registri;
use UserBundle\Entity\Costanti;
use Doctrine\ORM\EntityNotFoundException;
use UserBundle\Entity\RelRegistriOdgCipe;
use Sensio\Bundle\BuzzBundle;
use UserBundle\Entity\RelUfficiCipe;


class CipeController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;


	/**
     * @SWG\Tag(
     *   name="Cipe",
     *   description="Tutte le Api dei Cipe"
     * )
     */


    /**
     * @SWG\Get(
     *     path="/api/cipe",
     *     summary="Lista Cipe",
     *     tags={"Cipe"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":151,"limit":"99999","offset":0,"data":{{"id":153,"data":1496008800000
,"ufficiale_riunione":"","giorno":"","ora":"","sede":"","id_presidente":0,"id_segretario":0,"id_direttore"
:0,"public_reserved_status":"","public_reserved_url":"","allegati_TLX":"","allegati_APG":"","allegati_OSS"
:"","allegati_EST":""}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     */


    /**
     * @Route("/cipe", name="cipe")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_CIPE')")
     */
    public function cipeAction(Request $request)
    {
        //prendo i parametri get
        $limit = ($request->query->get('limit') != "") ? $request->query->get('limit') : 100;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;
        $sortBy = ($request->query->get('sort_by') != "") ? $request->query->get('sort_by') : 'id';
        $sortType = ($request->query->get('sort_order') != "") ? $request->query->get('sort_order') : "DESC";

        $repository = $this->getDoctrine()->getRepository('UserBundle:Cipe');
        $cipe = $repository->listaPrecipe($limit, $offset, $sortBy, $sortType);

        $arrayPrecipe = array();

        foreach ($cipe as $item => $value) {
            $allegatiTLX = "";
            $allegatiAPG = "";
            $allegatiOSS = "";
            $allegatiEST = "";

            $allegati = $repository->getAllegatiByIdCipe($value->getId());
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
                    case "EST":
                        $allegatiEST[] = $v;
                        break;
                }
                //print_r($v->tipologia);
            }

            $arrayTemp = json_decode($this->serialize($value));
            $arrayTemp->allegati_TLX = $allegatiTLX;
            $arrayTemp->allegati_APG = $allegatiAPG;
            $arrayTemp->allegati_OSS = $allegatiOSS;
            $arrayTemp->allegati_EST = $allegatiEST;
            $arrayPrecipe[] = $arrayTemp;
        }

        //$serialize = json_decode($this->serialize($cipe));

        //print_r($tags);

        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($cipe),
            "limit" => $limit,
            "offset" => $offset,
            "data" => $this->formatDateJsonArrayCustom($arrayPrecipe, array('data'))
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


	/**
     * @SWG\Get(
     *     path="/api/cipe/{id}",
     *     summary="Singolo Cipe",
     *     tags={"Cipe"},
     *     operationId="idCipe",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id del Cipe",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":151,"limit":"99999","offset":0,"data":{{"id":153,"data":1496008800000
,"ufficiale_riunione":"","giorno":"","ora":"","sede":"","id_presidente":0,"id_segretario":0,"id_direttore"
:0,"public_reserved_status":"","public_reserved_url":"","allegati_TLX":"","allegati_APG":"","allegati_OSS"
:"","allegati_EST":""}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))

     * )
     */


    /**
     * @Route("/cipe/{id}", name="cipe_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_CIPE')")
     */
    public function cipeItemAction(Request $request, $id)
    {

        $repository = $this->getDoctrine()->getRepository('UserBundle:Cipe');
        $cipe = $repository->findOneById($id);

        $repositoryCipeOdg = $this->getDoctrine()->getRepository('UserBundle:CipeOdg');
        $ordini = $repositoryCipeOdg->findByIdCipe($id);

        $repositoryRelRegistriOdgCipe = $this->getDoctrine()->getRepository('UserBundle:RelRegistriOdgCipe');
        $repositoryRelUfficiCipe = $this->getDoctrine()->getRepository('UserBundle:RelUfficiCipe');

        foreach ($ordini as $i => $v) {
            $registri_cipe = $repositoryRelRegistriOdgCipe->findByIdOdgCipe($v->getId());
            $registri_cipe = $this->mergeRegistriOdg($this->serialize($registri_cipe));
            $uffici_cipe = $repositoryRelUfficiCipe->findByIdOdgCipe($v->getId());
            $uffici_cipe = $this->mergeUfficiOdg($this->serialize($uffici_cipe));


            //$registri_cipe = json_decode($this->serialize($registri_cipe));
            $arrayTemp = json_decode($this->serialize($v));



            switch (count($registri_cipe)) {
                case 1:
                    $arrayTemp->id_registri = [$registri_cipe];
                    break;
                case 0:
                    $arrayTemp->id_registri = [];
                    break;
                default:
                    $arrayTemp->id_registri = $registri_cipe;
            }
            switch (count($uffici_cipe)) {
                case 1:
                    $arrayTemp->id_uffici = [$uffici_cipe];
                    break;
                case 0:
                    $arrayTemp->id_uffici = [];
                    break;
                default:
                    $arrayTemp->id_uffici = $uffici_cipe;
            }

            //ricavo gli allegati per ogni registro nella lista $arrayTemp->id_registri
            foreach ($arrayTemp->id_registri as $i => $v) {
                $repositoryRegistri = $this->getDoctrine()->getRepository('UserBundle:Registri');
                $allegatiR = $repositoryRegistri->getAllegatiByIdRegistro($v);
                $arrayTemp->allegati[$v] = $allegatiR;

                $arrayTempallegatiR = json_decode($this->serialize($allegatiR));

                $arrayTemp->allegati_esclusi = [];
                foreach ($arrayTempallegatiR as $k => $z) {
                    if ($z->escluso == 1) {
                        $arrayTemp->allegati_esclusi[] = $z->id;
                    }
                }
                $arrayTemp->allegati_esclusi_approvati = [];
            }

            $arrayOrdini[] = $arrayTemp;
        }


        $allegati = $repository->getAllegatiByIdCipe($id);
        $allegati = json_decode($this->serialize($allegati));

        $allegatiTLX = "";
        $allegatiAPG = "";
        $allegatiOSS = "";
        $allegatiEST = "";
        foreach ($allegati as $i => $v) {
            //$response = new Response(json_encode($v), Response::HTTP_OK);
            //return $this->setBaseHeaders($response);

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
                case "EST":
                    $allegatiEST[] = $v;
                    break;

            }
        }

        $cipeTemp = json_decode($this->serialize($cipe));
        $cipeTemp->cipe_odg = $arrayOrdini;
        $cipeTemp->allegati_TLX = $allegatiTLX;
        $cipeTemp->allegati_APG = $allegatiAPG;
        $cipeTemp->allegati_OSS = $allegatiOSS;
        $cipeTemp->allegati_EST = $allegatiEST;


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($cipe),
            "limit" => 1,
            "offset" => 0,
            "data" => $this->formatDateJsonArrayCustom([$cipeTemp], array('data'))[0]
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }



	/**
     * @SWG\Put(
     *     path="/api/cipe/{id}",
     *     summary="Salvataggio Cipe e relativi OdG",
     *     tags={"Cipe"},
     *     operationId="idCipe",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="query",
     *         description="id del Cipe",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
	 *     @SWG\Parameter(
     *         name="cipe",
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
	 *					@SWG\Property(property="giorno", type="string"),
	 *					@SWG\Property(property="ora", type="string"),
	 *					@SWG\Property(property="sede", type="string"),
	 *					@SWG\Property(property="id_presidente", type="integer"),
	 *					@SWG\Property(property="id_segretario", type="integer"),
	 *					@SWG\Property(property="id_direttore", type="integer"),
	 *					@SWG\Property(property="public_reserved_status", type="integer"),
	 *					@SWG\Property(property="cipe_odg",
	 *						type="array",
	 *						@SWG\Items(
	 *							@SWG\Property(property="id", type="integer"),
	 *							@SWG\Property(property="id_cipe", type="integer"),
	 *							@SWG\Property(property="progressivo", type="integer"),
	 *							@SWG\Property(property="id_titolari", type="integer"),
	 *							@SWG\Property(property="id_fascicoli", type="integer"),
	 *							@SWG\Property(property="id_sotto_fascicoli", type="integer"),
	 *							@SWG\Property(property="id_argomenti", type="integer"),
	 *							@SWG\Property(property="_tipo_argomenti", type="integer"),
	 *							@SWG\Property(property="id_uffici", type="array"),
	 *							@SWG\Property(property="ordine", type="integer"),
	 *							@SWG\Property(property="denominazione", type="string"),
	 *							@SWG\Property(property="risultanza", type="integer"),
	 *							@SWG\Property(property="id_esito", type="integer"),
	 *							@SWG\Property(property="id_delibera", type="integer"),
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
     *					@SWG\Property(property="allegati_OSS", type="string"),
     *					@SWG\Property(property="allegati_EST", type="string")
     *             )
	 *			)
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */

    /**
     * @Route("/cipe/{id}", name="cipe_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_CIPE')")
     */
    public function cipeItemSaveAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $repository = $em->getRepository('UserBundle:Cipe');
        $cipe = $repository->findOneById($data->id);

        $cipe->setData(new \DateTime($this->zulu_to_rome($data->data)));
        //$cipe->setData(new \DateTime('2016-07-18'));

        $repository_odg = $em->getRepository('UserBundle:CipeOdg');
        $repository_rel_registri_odg = $em->getRepository('UserBundle:RelRegistriOdgCipe');
        $repository_rel_uffici_odg = $em->getRepository('UserBundle:RelUfficiCipe');


        //salvo ogni odg del cipe
        foreach ($data->cipe_odg as $item => $value) {

            if (isset($value->id)) {
                $cipeodg = $repository_odg->findOneById((int)$value->id);
            } else {
                $cipeodg = new CipeOdg();
            }


            $cipeodg->setIdCipe($id);
            $cipeodg->setIdTitolari($value->id_titolari);
            $cipeodg->setIdFascicoli($value->id_fascicoli);
            $cipeodg->setOrdine($value->ordine);
            $cipeodg->setDenominazione($value->denominazione);
            $cipeodg->setNumeroDelibera($value->numero_delibera);
            $cipeodg->setRisultanza($value->risultanza);
            if (isset($value->annotazioni)) { $cipeodg->setAnnotazioni($value->annotazioni);}

            //$precipeodg->setStato($value->stato);

            if (!isset($value->id)) {
                $em->persist($cipeodg);
                $em->flush(); //esegue l'update
                $value->id = $cipeodg->getId();
            }


            $relRegistriOdg_delete = $repository_rel_registri_odg->findByIdOdgCipe((int)$value->id);
            // REGISTRI
            //rimuovo tutte le relazioni con l'id dell'odg (per poi riaggiornale ovvero ricrearle)
            foreach ($relRegistriOdg_delete as $relRegistriOdg_delete) {
                $em->remove($relRegistriOdg_delete);
            }
            //creo le relazioni da aggiornare nella tabella RelAmministrazioniRegistri
            foreach ($value->id_registri as $k) {
                $relRegistriOdg = new RelRegistriOdgCipe();
                $relRegistriOdg->setIdOdgCipe((int)$value->id);
                $relRegistriOdg->setIdRegistri($k);

                //aggiorno (in realt� ricreo) le relazioni del registro
                $em->persist($relRegistriOdg); //create
            }

            $relUffici_delete = $repository_rel_uffici_odg->findByIdOdgCipe((int)$value->id);
            // UFFICI
            foreach ($relUffici_delete as $relUffici_delete) {
                $em->remove($relUffici_delete);
            }
            foreach ($value->id_uffici as $k) {
                $relUfficiOdg = new RelUfficiCipe();
                $relUfficiOdg->setIdOdgCipe((int)$value->id);
                $relUfficiOdg->setIdUffici($k);

                //aggiorno (in realt� ricreo) le relazioni del registro
                $em->persist($relUfficiOdg); //create
            }


            // ALLEGATI ESCLUSI
            foreach ($value->allegati as $k) {
                foreach ($k as $a => $b) {
                    $repository = $em->getRepository('UserBundle:Allegati');
                    $repository_allegatiEsclusi = $repository->findOneBy(array("id" => $b->id));

                    $repository_allegatiEsclusi->setEscluso(0);

                    $em->persist($repository_allegatiEsclusi);
                    $em->flush(); //esegue l'update
                }
            }
            foreach ($value->allegati_esclusi as $k) {
                $repository = $em->getRepository('UserBundle:Allegati');
                $repository_allegatiEsclusi = $repository->findOneBy(array("id" => $k));

                $repository_allegatiEsclusi->setEscluso(1);

                $em->persist($repository_allegatiEsclusi);
                $em->flush(); //esegue l'update
            }



                $em->persist($cipeodg);
            $em->flush(); //esegue l'update

            //$response = new Response(json_encode($value->id_registri), Response::HTTP_OK);
            //return $this->setBaseHeaders($response);
        }


        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("cipe");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->flush(); //esegue l'update

        $response = new Response(json_encode($data), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


	/**
     * @SWG\Post(
     *     path="/api/cipe",
     *     summary="Creazione Cipe e relativi OdG",
     *     tags={"Cipe"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */


    /**
     * @Route("/cipe", name="cipe_item_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREATE_CIPE')")
     */
    public function cipeItemCreateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent());
        $dataCipeOdg = json_decode($request->getContent());
        $dataCipeOdg = $dataCipeOdg->cipe_odg;
        foreach ($dataCipeOdg as $item => $value) {
            //print_r($value->ordine);
            $check = $this->checkCampiObbligatori($value,["denominazione","ordine","id_fascicoli","id_registri","id_titolari","id_uffici"]);
            if ($check != "ok") {
                $response_array = array("error" =>  ["code" => 409, "message" => "Il campo ".$check." e' obbligatorio"]);
                $response = new Response(json_encode($response_array), 409);
                return $this->setBaseHeaders($response);
            }
        }


        $cipe = new Cipe();
        $cipe->setData(new \DateTime($this->zulu_to_rome($data->data)));

        $em->persist($cipe);
        $em->flush(); //esegue l'update


        //salvo ogni odg del cipe
        foreach ($data->cipe_odg as $item => $value) {
            $cipeodg = new CipeOdg();

            $cipeodg->setIdCipe($cipe->getId());
            //$cipeodg->setProgressivo($value->progressivo);
            $cipeodg->setIdTitolari($value->id_titolari);
            $cipeodg->setIdFascicoli($value->id_fascicoli);
            //$cipeodg->setIdArgomenti($value->id_argomenti);
            $cipeodg->setOrdine($value->ordine);
            $cipeodg->setDenominazione($value->denominazione);
            $cipeodg->setRisultanza($value->risultanza);
            $cipeodg->setNumeroDelibera($value->numero_delibera);
            if (isset($value->annotazioni)) { $cipeodg->setAnnotazioni($value->annotazioni);}
            //$precipeodg->setStato($value->stato);

            $em->persist($cipeodg);
            $em->flush(); //esegue l'update


            // REGISTRI
            //creo le relazioni da aggiornare nella tabella RelAmministrazioniRegistri
            foreach ($value->id_registri as $k) {
                $relRegistriOdg = new RelRegistriOdgCipe();
                $relRegistriOdg->setIdOdgCipe($cipeodg->getId());
                $relRegistriOdg->setIdRegistri($k);

                //aggiorno (in realt� ricreo) le relazioni del registro
                $em->persist($relRegistriOdg); //create
            }
            foreach ($value->id_uffici as $k) {
                $relUfficiOdg = new RelUfficiCipe();
                $relUfficiOdg->setIdOdgCipe($cipeodg->getId());
                $relUfficiOdg->setIdUffici($k);

                //aggiorno (in realt� ricreo) le relazioni del registro
                $em->persist($relUfficiOdg); //create
            }

            $em->persist($cipeodg);
            $em->flush(); //esegue l'update
        }






        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("cipe");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->persist($cipe);
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($cipe), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }



	/**
     * @SWG\Delete(
     *     path="/api/cipe/{id}",
     *     summary="Eliminazione cipe",
     *     tags={"Cipe"},
     *     operationId="idCipe",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id del cipe",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"),
	 *     @SWG\Response(response=409, description="Il cipe contiene degli Ordini del Giorno, impossibile eliminarlo.")
     * )
     */

    /**
     * @Route("/cipe/{id}", name="cipe_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_CIPE')")
     */
    public function cipeItemDeleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:Cipe');
        $cipe = $repository->findOneById($id);

        $repositoryCipeOdg = $em->getRepository('UserBundle:CipeOdg');
        $ordini = $repositoryCipeOdg->findOneByIdCipe($id);


        if ($ordini) {
            $response_array = array("error" => ["code" => 409, "message" => "Il cipe contiene degli Ordini del Giorno, impossibile eliminarlo."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {

            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("cipe");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente


            $em->remove($cipe); //delete
            $em->flush(); //esegue l'update

            $response = new Response($this->serialize($cipe), Response::HTTP_OK);
            return $this->setBaseHeaders($response);
        }
    }



	
	/**
     * @SWG\Post(
     *     path="/api/cipe/{id}/{tipo}/upload",
     *     summary="Upload files di un Cipe",
     *     tags={"Cipe"},
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
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"),
	 *     @SWG\Response(response=409, description="Il file e' troppo grande. (max 25 MB)"),
	 *     @SWG\Response(response=409, description="Tipo di file non permesso (solo PDF)")
     * )
     */


    /**
     * @Route("/cipe/{id}/{tipo}/upload", name="uploadCipe")
     * @Method("POST")
     */
    public function uploadCipeAction(Request $request, $id, $tipo)
    {

        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:Cipe');
        $cipe = $repository->findOneById($id);
        $dataPrecipe = $cipe->getData()->format('Y-m-d');


        $path_file = Costanti::URL_ALLEGATI_CIPE . "/" . $dataPrecipe . "/" . $tipo . "/";

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


        $allegatoRel = new RelAllegatiCipe();
        $allegatoRel->setIdAllegati($id_allegato_creato);
        $allegatoRel->setIdCipe($id);
        $allegatoRel->setTipo($tipo);


        $array = array(
            'id' => $id_allegato_creato,
            'id_cipe' => $id,
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


        try {
            $em->persist($allegatoRel);
            $em->flush(); //esegue query

            //copio fisicamente il file
            $file->move($_SERVER['DOCUMENT_ROOT'] . "/" . Costanti::PATH_IN_SERVER . $path_file, $nome_file);

        } catch (\Doctrine\ORM\EntityNotFoundException $ex) {
            echo "Exception Found - " . $ex->getMessage() . "<br/>";
        }


        $response = new Response(json_encode($array), Response::HTTP_OK);
        return $this->setBaseHeaders($response, "upload");
    }



	/**
     * @SWG\Delete(
     *     path="/api/cipe/{id}/{tipo}/upload/{idallegato}",
     *     summary="Eliminazione file di un Cipe",
     *     tags={"Cipe"},
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
     * @Route("/cipe/{id}/{tipo}/upload/{idallegato}", name="uploadDeletePrecipe")
     * @Method("DELETE")
     */
    public function cipeAllegatiItemDeleteAction(Request $request, $id, $tipo, $idallegato)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:RelAllegatiCipe');
        $relazione_allegato = $repository->findBy(array('idAllegati' => $idallegato, 'idCipe' => $id));
        //$relazione_allegato = $repository->findOneById($idallegato);

        $repository_file = $em->getRepository('UserBundle:Allegati');
        $file = $repository_file->findOneById($idallegato);

        //$idRelAllegatiRegistri = $relazione_allegato[0]->getId();

        if (!$relazione_allegato[0]) {
            $response_array = array("error" => ["code" => 409, "message" => "Questo file non e' allegato a questo cipe."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {
            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("cipe");
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
     * @Route("/areariservata/cipe/{id}", name="cipe_area_riservata")
     * @Method("GET")
     * //@Security("is_granted('ROLE_READ_AREARISERVATA_CIPE')")
     */
    public function precipeAreaRiservataAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserBundle:Cipe');
        $precipe = $repository->findOneById($id);




        //#########
        //######### costruisco il json da mandare
        //#########

        $repository = $this->getDoctrine()->getRepository('UserBundle:Cipe');
        $precipe = $repository->findOneById($id);

        $repositoryPreCipeOdg = $this->getDoctrine()->getRepository('UserBundle:CipeOdg');
        $ordini = $repositoryPreCipeOdg->findByIdCipe($id);

        $repositoryRelRegistriOdg = $this->getDoctrine()->getRepository('UserBundle:RelRegistriOdgCipe');


        $limite_ordini =0;
        $array_no_doppioni = "";
        foreach ($ordini as $i => $v) {
            if ($limite_ordini < 100000) {
                $limite_ordini++;

                $registri_precipe = $repositoryRelRegistriOdg->findByIdOdgCipe($v->getId());
                $registri_precipe = $this->mergeRegistriOdg($this->serialize($registri_precipe));



                //$registri_precipe = json_decode($this->serialize($registri_precipe));
                $arrayTemp = json_decode($this->serialize($v));


                $arrayTemp->id_punto_odg = $arrayTemp->id; unset($arrayTemp->id);
                $arrayTemp->denominazione = str_replace('“','', $arrayTemp->denominazione);
                $arrayTemp->denominazione = str_replace('”','', $arrayTemp->denominazione);


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

                $array_allegati = array();
                //ricavo gli allegati per ogni registro nella lista $arrayTemp->id_registri
                $countAllegati = 0;
                foreach ($arrayTemp->id_registri as $i => $v) {
                    $repositoryRegistri = $this->getDoctrine()->getRepository('UserBundle:Registri');
                    $allegatiR = $repositoryRegistri->getAllegatiByIdRegistro($v);
                    foreach ($allegatiR as $ii => $vv) {

                        $vv['id_allegato'] = $vv['id']; unset($vv['id']);
                        $vv['data'] = date("Y-m-d", $vv['data'] / 1000);
                        if ($vv['dimensione'] == false) {
                            $vv['dimensione'] = 0;
                        }
                        if ($vv['escluso'] == 1) {
                            unset($vv);continue;
                        }

                        $array_allegati[$countAllegati] = $vv;
                        $countAllegati++;
                    }

                }

                $arrayTemp->allegati = $array_allegati;
                $arrayOrdini[] = $arrayTemp;
            }
        }


//        $data = $request->query->all();       //to get all GET params.
//
//        $response = new Response(json_encode($request), Response::HTTP_OK);
//return $this->setBaseHeaders($response);

        $precipeTemp = json_decode($this->serialize($precipe));
        $precipeTemp->ufficiale = $precipeTemp->ufficiale_riunione; unset($precipeTemp->ufficiale_riunione);
        $precipeTemp->precipe_odg = $arrayOrdini;
        $precipeTemp->data = substr($precipeTemp->data, 0, 10);

        foreach ($precipeTemp->precipe_odg as $item) {
            unset($item->id_registri);
            unset($item->id_pre_cipe);
            //unset($item->progressivo);
            unset($item->id_titolari);
            unset($item->id_fascicoli);
            unset($item->id_argomenti);
            unset($item->id_uffici);
            //unset($item->ordine);
            unset($item->risultanza);
            unset($item->annotazioni);
            unset($item->stato);
        }

        unset($precipeTemp->ufficiale);
        unset($precipeTemp->public_reserved_status);
        unset($precipeTemp->public_reserved_url);

        unset($precipeTemp->giorno);
        unset($precipeTemp->ora);
        unset($precipeTemp->sede);
        unset($precipeTemp->id_presidente);
        unset($precipeTemp->id_segretario);
        unset($precipeTemp->id_direttore);

        $precipeTemp->tipo = "cipe";
        $precipeTemp->id_seduta = $precipeTemp->id; unset($precipeTemp->id);
        $precipeTemp->punti_odg = $precipeTemp->precipe_odg; unset($precipeTemp->precipe_odg);


        //print_r(json_encode($precipeTemp));
        //$response = new Response(json_encode($precipeTemp), Response::HTTP_OK);
        //return $this->setBaseHeaders($response);

        //unset($precipeTemp->punti_odg[1]);
        //unset($precipeTemp->punti_odg[2]);
        //unset($precipeTemp->punti_odg[3]);
        //unset($precipeTemp->punti_odg[4]);
        //unset($precipeTemp->punti_odg[5]);
        //unset($precipeTemp->punti_odg[6]);

        //unset($precipeTemp->punti_odg[7]);
        //unset($precipeTemp->punti_odg[8]);
        //unset($precipeTemp->punti_odg[9]);
        //unset($precipeTemp->punti_odg[10]);
        //unset($precipeTemp->punti_odg[11]);
        //unset($precipeTemp->punti_odg[12]);
        //unset($precipeTemp->punti_odg[13]);
        //unset($precipeTemp->punti_odg[14]);
        //unset($precipeTemp->punti_odg[15]);
        //unset($precipeTemp->punti_odg[16]);
        //unset($precipeTemp->punti_odg[17]);
        //unset($precipeTemp->punti_odg[18]);

        //unset($precipeTemp->punti_odg[18]->allegati[1]);
//        unset($precipeTemp->punti_odg[18]->allegati[2]);
//        unset($precipeTemp->punti_odg[18]->allegati[3]);
//        unset($precipeTemp->punti_odg[18]->allegati[4]);
//        unset($precipeTemp->punti_odg[18]->allegati[5]);
//        unset($precipeTemp->punti_odg[18]->allegati[6]);
//        unset($precipeTemp->punti_odg[18]->allegati[7]);
//        unset($precipeTemp->punti_odg[18]->allegati[8]);
//        unset($precipeTemp->punti_odg[18]->allegati[9]);
//        unset($precipeTemp->punti_odg[18]->allegati[10]);
//
//        unset($precipeTemp->punti_odg[19]);
//        unset($precipeTemp->punti_odg[20]);
//        unset($precipeTemp->punti_odg[21]);
//        unset($precipeTemp->punti_odg[22]);
//        unset($precipeTemp->punti_odg[23]);
//        unset($precipeTemp->punti_odg[24]);
//        unset($precipeTemp->punti_odg[25]);
//        unset($precipeTemp->punti_odg[26]);
//        unset($precipeTemp->punti_odg[27]);
//        unset($precipeTemp->punti_odg[28]);


        $command = Costanti::PATH_PHP . " -f mosic-script/cipe-area-riservata.php " . $id . " '". str_replace("'", " ",json_encode($precipeTemp)) ."'";
        exec( "$command > /dev/null &", $arrOutput );


        $response_array = array("success" => ["code" => 200, "message" => "Procedura presa in carico"]);

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);





        $response = new Response();
        return $this->setBaseHeaders($response);
    }




    /**
     * @Route("/areariservata/cipe/{id}", name="cipe_area_riservata_delete")
     * @Method("DELETE")
     * //@Security("is_granted('ROLE_DELETE_AREARISERVATA_PRECIPE')")
     */
    public function cipeAreaRiservataDeleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('UserBundle:Cipe');
        $cipe = $repository->findOneById($id);



        //#########
        //######### chiamo l'api per il LOGIN
        //#########

        $ch = curl_init();
        $fields = array("username"=>"mosic", "password" => "cowpony-butter-vizor");

        curl_setopt($ch, CURLOPT_URL,"http://area-riservata.programmazioneeconomica.gov.it/api-token-auth/");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);
        $info = curl_getinfo($ch);
        curl_close ($ch);
        $token =json_decode($server_output)->token;




//        $browser = $this->container->get('buzz');
//
//        $fields = array("username"=>"mosic", "password" => "cowpony-butter-vizor");
//        $response = $browser->submit("http://area-riservata.mosic2.celata.com/api-token-auth/", $fields, "POST");
//
//
//
//        $content = json_decode($response->getContent());
//        //$response = json_decode($response->getContent());
//        $token = $content->token;




        //Aggiorno lo stato del cipe
        if ($info['http_code'] == 200) {
            $response_array = array("success" => ["code" => 200, "message" => "Procedura presa in carico"]);
            $cipe->setPublicReservedStatus(json_encode($response_array));

            $em->persist($cipe);
            $em->flush(); //esegue l'update

            $response_array = array(
                "response" => Response::HTTP_OK,
                "data" => "Procedura presa in carico"
            );

        } else {
            $response_array = array("error" => ["code" => 401, "message" => "Errore nella login"]);
            $cipe->setPublicReservedStatus(json_encode($response_array));

            $em->persist($cipe);
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

        $ch = curl_init();
        $headers = [
            'Accept: */*',
            'Cache-Control: no-cache',
            'Authorization: JWT ' . $token
        ];

        curl_setopt($ch, CURLOPT_URL,"http://area-riservata.programmazioneeconomica.gov.it/seduta/cipe/". $id);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        $info = curl_getinfo($ch);

        curl_close ($ch);

        $id_cipe =json_decode($server_output)->id;



//        $browser = $this->container->get('buzz');
//
//        $headers = array(
//            'Accept' => '*/*',
//            'Content-Type' => 'application/json',
//            'Cache-Control' => 'no-cache',
//            'Authorization' => 'JWT ' . $token
//            // Add any other header needed by the API
//        );
//        $response = $browser->get("http://area-riservata.mosic2.celata.com/seduta/cipe/". $id, $headers);
//        $content = json_decode($response->getContent());
//        $id_cipe = $content->id;




        //#########
        //######### chiamo l'api della delete
        //#########

        $ch = curl_init();
        $headers = [
            'Accept: */*',
            'Cache-Control: no-cache',
            'Content-Type: application/json',
            'Authorization: JWT ' . $token
        ];

        curl_setopt($ch, CURLOPT_URL,"http://area-riservata.programmazioneeconomica.gov.it/cipe/". $id_cipe);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);
        $info = curl_getinfo($ch);

        curl_close ($ch);





//        $browser = $this->container->get('buzz');
//
//        $headers = array(
//            'Accept' => '*/*',
//            'Cache-Control' => 'no-cache',
//            'Authorization' => 'JWT ' . $token
//            // Add any other header needed by the API
//        );
//
//        $response = $browser->delete("http://area-riservata.mosic2.celata.com/cipe/". $id_cipe, $headers);

        //Aggiorno lo stato del cipe
        if ($info['http_code'] == 204) {
            $response_array = array(
                "response" => 204,
                "data" => array("message" => "Documenti e o.d.g. rimossi dall'area riservata")
            );
            $cipe->setufficialeRiunione("");
            $cipe->setPublicReservedStatus("");
            $cipe->setPublicReservedUrl("");
            $em->persist($cipe);
            $em->flush(); //esegue l'update
        } else {
            $response_array = array("error" => ["code" => 409, "message" => "Errore nella rimozione del cipe"]);
        }

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);


    }


    /**
     * @Route("/areariservata/cipe/check/{id}", name="cipe_area_riservata_check")
     * //@Security("is_granted('ROLE_READ_AREARISERVATA_CIPE_CHECK')")
     * @Method("GET")
     */
    public function cipeAreaRiservataCheckAction(Request $request, $id) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:Cipe');
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
                    "message" => "CIPE non pubblicato",
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
     * @Route("/cipe", name="cipe_options")
     * @Method("OPTIONS")
     */
    public function cipeOptions(Request $request)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/cipe/{id2}", name="cipe_item_options")
     * @Method("OPTIONS")
     */
    public function cipeItemOptions(Request $request, $id2)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/cipe/{id}/{tipo}/upload", name="CipeUploadDelete_item_options")
     * @Method("OPTIONS")
     */
    public function cipeUploadDeleteItemOptions(Request $request, $id, $tipo)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/cipe/{id}/{tipo}/upload/{idallegato}", name="CipeUploadDeleteIdAllegato_item_options")
     * @Method("OPTIONS")
     */
    public function cipeUploadDeleteIdAllegatoItemOptions(Request $request, $id, $tipo, $idallegato)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    
    /**
     * @Route("/areariservata/cipe/{id}", name="CipeAreaRiservata")
     * @Method("OPTIONS")
     */
    public function cipeAreaRiservataOptions(Request $request, $id)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/areariservata/cipe/check/{id}", name="check_options")
     * @Method("OPTIONS")
     */
    public function provaPostOptions(Request $request, $id)
    {
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
