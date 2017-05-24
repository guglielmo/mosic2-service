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
     * @Route("/cipe", name="cipe")
     * @Method("GET")
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
     * @Route("/cipe/{id}", name="cipe_item")
     * @Method("GET")
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
                $arrayTemp->allegati_esclusi = [];
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
     * @Route("/cipe/{id}", name="cipe_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_UFFICI')")
     */
    public function cipeItemSaveAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $repository = $em->getRepository('UserBundle:Cipe');
        $cipe = $repository->findOneById($data->id);

        $cipe->setData(new \DateTime($this->formatDateStringCustom($data->data)));
        //$cipe->setData(new \DateTime('2016-07-18'));

        $repository_odg = $em->getRepository('UserBundle:CipeOdg');
        $repository_rel_registri_odg = $em->getRepository('UserBundle:RelRegistriOdgCipe');
        $repository_rel_uffici_odg = $em->getRepository('UserBundle:RelUfficiCipe');


        //salvo ogni odg del cipe
        foreach ($data->cipe_odg as $item => $value) {
            $cipeodg = $repository_odg->findOneById((int)$value->id);

            $cipeodg->setIdCipe($id);
            $cipeodg->setProgressivo($value->progressivo);
            $cipeodg->setIdTitolari($value->id_titolari);
            $cipeodg->setIdFascicoli($value->id_fascicoli);
            $cipeodg->setIdArgomenti($value->id_argomenti);
            //$cipeodg->setIdUffici($value->id_uffici);
            $cipeodg->setOrdine($value->ordine);
            $cipeodg->setDenominazione($value->denominazione);
            $cipeodg->setRisultanza($value->risultanza);
            $cipeodg->setAnnotazioni($value->annotazioni);
            $cipeodg->setStato($value->stato);


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
     * @Route("/cipe", name="cipe_item_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREATE_UFFICI')")
     */
    public function cipeItemCreateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $cipe = new Cipe();

        $cipe->setData(new \DateTime($this->formatDateStringCustom($data->data)));

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
     * @Route("/cipe/{id}", name="cipe_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_UFFICI')")
     */
    public function cipeItemDeleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:Cipe');
        $cipe = $repository->findOneById($id);

        $repositoryCipeOdg = $em->getRepository('UserBundle:CipeOdg');
        $ordini = $repositoryCipeOdg->findOneByIdCipe($id);


        if ($ordini) {
            $response_array = array("error" => ["code" => 409, "message" => "Il cipe non e' vuoto, impossibile eliminarlo."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {

            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("cipe");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente


            //$em->remove($cipe); //delete
            //$em->flush(); //esegue l'update

            $response = new Response($this->serialize($cipe), Response::HTTP_OK);
            return $this->setBaseHeaders($response);
        }
    }


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

        $allegatoRel = new RelAllegatiCipe();
        $allegatoRel->setIdAllegati($id_allegato_creato);
        $allegatoRel->setIdPrecipe($id);
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



        $command = "/opt/php-5.6.25/bin/php -f mosic-script/cipe-area-riservata.php " . $id . " '". str_replace("'", " ",json_encode($precipeTemp)) ."'";
        exec( "$command > /dev/null &", $arrOutput );


        $response_array = array("success" => ["code" => 200, "message" => "Procedura presa in carico"]);

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);





        $response = new Response();
        return $this->setBaseHeaders($response);
    }




    /**
     * @Route("/areariservata/cipe/check/{id}", name="cipe_area_riservata_check")
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
     * @Route("/areariservata/cipe/{id}", name="PreCipeAreaRiservata")
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
