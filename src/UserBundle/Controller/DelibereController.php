<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Entity\Delibere;
use UserBundle\Entity\DelibereCC;
use UserBundle\Entity\DelibereGiorni;
use UserBundle\Entity\Fascicoli;
use UserBundle\Entity\Registri;
use UserBundle\Entity\LastUpdates;
use UserBundle\Entity\Costanti;
use UserBundle\Entity\Allegati;
use UserBundle\Entity\RelAllegatiDelibere;
use UserBundle\Entity\RelFirmatariDelibere;
use UserBundle\Entity\RelTagsDelibere;
use UserBundle\Entity\RelUfficiDelibere;


class DelibereController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;

    /**
     * @Route("/delibere", name="delibere")
     * @Method("GET")
     */
    public function delibereAction(Request $request) {
        //prendo i parametri get
        $limit  = ($request->query->get('limit') != "") ? $request->query->get('limit') : 200;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;

        $sortBy  = ($request->query->get('sort_by') != "") ? $request->query->get('sort_by') : 'numero';
        $sortType = ($request->query->get('sort_order') != "") ? $request->query->get('sort_order') : "ASC";

        $argomento = ($request->query->get('argomento') != "") ? $request->query->get('argomento') : '';

        $repository = $this->getDoctrine()->getRepository('UserBundle:Delibere');
        $delibere = $repository->listaDelibere($limit, $offset, $sortBy, $sortType, $argomento);

        //converte i risultati in json
        $serialize = $this->serialize($delibere);

        $serialize = $this->mergeRelDelibereAll($serialize);

        $serialize = $this->formatDateJsonArrayCustom2($serialize, array('data', 'data_consegna', 'data_segretario_ritorno', 'data_segretario_invio',
                                                            'data_presidente_ritorno', 'data_presidente_invio', 'data_registrazione_cc',
                                                            'data_invio_cc', 'data_invio_gu', 'data_gu','data_direttore_invio','data_direttore_ritorno',
                                                            'data_mef_invio','data_mef_ritorno'));
        $serialize = $this->setFaseProceduraleDelibere($serialize);
        $serialize = $this->setCastDelibere($serialize, "all");


        $em = $this->getDoctrine()->getManager();
        $giorni = array();
        foreach ($serialize as $item => $value) {
            $tagArrayConvert = array_map('intval', explode(',', $serialize[$item]["id_tags"]));
            $serialize[$item]["id_tags"] = ($tagArrayConvert[0] == 0 ? array() : $tagArrayConvert);
            $repositoryDelibereGiorni = $em->getRepository('UserBundle:DelibereGiorni');
            $delibereGiorni = $repositoryDelibereGiorni->findOneBy(["idDelibere" => $serialize[$item]["id"]]);
            if (count($delibereGiorni) == 0) {
                $delibereGiorni = new DelibereGiorni();
            }
            $giorni = array(
                "dipe" => $delibereGiorni->getGiorniCapoDipartimento(),
                "mef" => $delibereGiorni->getGiorniMef(),
                "firme" => $delibereGiorni->getGiorniPresidente(),
                "cc" => $delibereGiorni->getGiorniCC(),
                "gu" => $delibereGiorni->getGiorniGU()
            );


            $repositoryDelibereCC = $em->getRepository('UserBundle:DelibereCC');
            $delibereCC = $repositoryDelibereCC->findBy(["idDelibere" => $serialize[$item]["id"]]);
            $arrayDelibereCC = array();
            foreach ($delibereCC as $k) {
                $arrayDelibereCC[] = array(
                    "id" => $k->getId(),
                    "tipo_documento" => $k->getTipoDocumento(),
                    "data_max_risposta" => strtotime($k->getDataRilievo()->modify('+'.$k->getGiorniRilievo().' days')->format('Y-m-d')) * 1000,
                    "data_risposta" => strtotime($k->getDataRisposta()->format('Y-m-d')) * 1000
                );

            }

            $serialize[$item]["giorni_iter"] = $giorni;
            $serialize[$item]["oss_cc"] = $arrayDelibereCC;
            $serialize[$item]["registrazione_cc"] = array(
                "data" => $serialize[$item]["data_registrazione_cc"],
                "foglio" => $serialize[$item]["foglio_cc"],
                "numero" => $serialize[$item]["registro_cc"]
            );
            $serialize[$item]["firme"] = array(
                "uff_a" => $serialize[$item]["data_consegna"],
                "cd_i" => $serialize[$item]["data_direttore_invio"],
                "cd_r" => $serialize[$item]["data_direttore_ritorno"],
                "mef_i" => $serialize[$item]["data_mef_invio"],
                "mef_r" => $serialize[$item]["data_mef_ritorno"],
                "seg_i" => $serialize[$item]["data_segretario_invio"],
                "seg_r" => $serialize[$item]["data_segretario_ritorno"],
                "pre_i" => $serialize[$item]["data_presidente_invio"],
                "pre_r" => $serialize[$item]["data_presidente_ritorno"],
                "cc_i" => $serialize[$item]["data_invio_cc"],
                "cc_r" => $serialize[$item]["data_registrazione_cc"],
                "gu_i" => $serialize[$item]["data_invio_gu"],
                "gu_r" => $serialize[$item]["data_gu"]
            );
            unset($serialize[$item]['data_consegna']);
            unset($serialize[$item]['data_direttore_invio']);
            unset($serialize[$item]['data_direttore_ritorno']);
            unset($serialize[$item]['data_mef_invio']);
            unset($serialize[$item]['data_mef_ritorno']);
            unset($serialize[$item]['data_segretario_invio']);
            unset($serialize[$item]['data_segretario_ritorno']);
            unset($serialize[$item]['data_presidente_invio']);
            unset($serialize[$item]['data_presidente_ritorno']);
            unset($serialize[$item]['data_invio_cc']);
            unset($serialize[$item]['data_registrazione_cc']);
            unset($serialize[$item]['data_invio_gu']);

            $serialize[$item]["note"] = array(
                "cd" => $serialize[$item]["note_direttore"],
                "mef" => $serialize[$item]["note_mef"],
                "seg" => $serialize[$item]["note_segretario"],
                "pre" => $serialize[$item]["note_presidente"],
                "cc" => $serialize[$item]["note_cc"],
                "pa" => $serialize[$item]["note_p"],
                "gu" => $serialize[$item]["note_gu"],
                "an" => $serialize[$item]["note"],
                "ns" => $serialize[$item]["note_servizio"],
            );
            unset($serialize[$item]['note_direttore']);
            unset($serialize[$item]['note_mef']);
            unset($serialize[$item]['note_segretario']);
            unset($serialize[$item]['note_presidente']);
            unset($serialize[$item]['note_cc']);
            unset($serialize[$item]['note_p']);
            unset($serialize[$item]['note_gu']);
            unset($serialize[$item]['note_servizio']);


            $serialize[$item]["pub_gu"] = array(
                "nr" => $serialize[$item]["numero_gu"],
                "data" => $serialize[$item]["data_gu"],
            );
            unset($serialize[$item]['numero_gu']);
            unset($serialize[$item]['data_gu']);



        }



        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($delibere),
            "limit" => $limit,
            "offset" => $offset,
            "data" => $serialize,
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
    
    
    /**
     * @Route("/delibere/{id}", name="delibere_item")
     * @Method("GET")
     */
    public function delibereItemAction(Request $request, $id) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:Delibere');
        $delibere = $repository->schedaDelibera($id);

        $repositoryCC = $this->getDoctrine()->getRepository('UserBundle:DelibereCC');
        $delibereCC = $repositoryCC->findBy(array("idDelibere" => $id));

        $delibereCC = $this->formatDateJsonArrayCustom(json_decode($this->serialize($delibereCC)),array("data_rilievo","data_risposta") );

        //converte i risultati in json
        $serialize = $this->serialize($delibere);

        $serialize = $this->mergeRelDelibereItem($serialize);

        $serialize = $this->formatDateJsonCustom($serialize, array('data', 'data_consegna', 'data_direttore_invio', 'data_direttore_ritorno',
            'data_mef_invio', 'data_mef_pec', 'data_mef_ritorno', 'data_segretario_invio',
            'data_segretario_ritorno', 'data_presidente_invio', 'data_presidente_ritorno',
            'data_invio_cc', 'data_registrazione_cc', 'data_invio_p', 'data_invio_gu', 'data_gu', 'data_ec_gu', 'data_co_gu'));

        $serialize = $this->setCastDelibere($serialize, "item");


        $allegati = $repository->getAllegatiByIdDelibere($id);
        $allegati = json_decode($this->serialize($allegati));

        $allegatiMEF = "";
        $allegatiCC = "";
        $allegatiGU = "";
        $allegatiDEL = "";
        $allegatiALL = "";

        foreach ($allegati as $i => $v) {
            switch ($v->tipologia) {
                case "MEF":
                    $allegatiMEF[] = $v;
                    break;
                case "CC":
                    $allegatiCC[] = $v;
                    break;
                case "GU":
                    $allegatiGU[] = $v;
                    break;
                case "DEL":
                    $allegatiDEL[] = $v;
                    break;
                case "ALL":
                    $allegatiALL[] = $v;
                    break;
            }
        }

        $serialize[0]['allegati_MEF'] = $allegatiMEF;
        $serialize[0]['allegati_CC'] = $allegatiCC;
        $serialize[0]['allegati_GU'] = $allegatiGU;
        $serialize[0]['allegati_DEL'] = $allegatiDEL;
        $serialize[0]['allegati_ALL'] = $allegatiALL;

        foreach ($delibereCC as $item => $value) {
            $value->numero_rilievo = (int) $value->numero_rilievo;
            $value->numero_risposta = (int) $value->numero_risposta;
        }

        $serialize[0]['rilievi_CC'] = $delibereCC;

        $serialize[0]['id_segretariato'] = explode(",",$serialize[0]['id_segretariato']);


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($delibere),
            "limit" => 1,
            "offset" => 0,
            "data" => $serialize[0],
        );
        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


    /**
     * @Route("/delibere/{id}", name="delibere_item_save")
     * @Method("PUT")
     */
    public function delibereItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $repository = $em->getRepository('UserBundle:Delibere');
        $delibere = $repository->findOneBy(array("id" =>$data->id));

        $repository_relFirmatariDelibere = $em->getRepository('UserBundle:RelFirmatariDelibere');
        $relFirmatariDelibere_delete = $repository_relFirmatariDelibere->findBy(array("idDelibere" => $data->id));

        $repository_relUfficiDelibere = $em->getRepository('UserBundle:RelUfficiDelibere');
        $relUfficiDelibere_delete = $repository_relUfficiDelibere->findBy(array("idDelibere" => $data->id));

        $repository_DelibereCC = $em->getRepository('UserBundle:DelibereCC');
        $DelibereCC_delete = $repository_DelibereCC->findBy(array("idDelibere" => $data->id));

        $repository_relTagsDelibere = $em->getRepository('UserBundle:RelTagsDelibere');
        $relTagsDelibere_delete = $repository_relTagsDelibere->findByIdDelibere($data->id);


        //$array_id_firmatari = explode(",", $data->id_segretariato); //$data->id_segretariato è già un array
        //$array_id_uffici = explode(",", $data->id_uffici);
        $array_id_tags = explode(",", $data->id_tags);


        $delibere->setNumero($data->numero);
        if ($data->data != null){ $delibere->setData(new \DateTime($this->formatDateStringCustom($data->data))); } else {$delibere->setData(null); }
        $delibere->setIdStato($data->id_stato);
        $delibere->setArgomento($data->argomento);
        $delibere->setFinanziamento($data->finanziamento);
        $delibere->setNote($data->note);
        $delibere->setNoteServizio($data->note_servizio);
        $delibere->setScheda($data->scheda);
        if ($data->data_consegna != null){ $delibere->setDataConsegna(new \DateTime($this->formatDateStringCustom($data->data_consegna)));} else {$delibere->setDataConsegna(null); }
        $delibere->setIdDirettore($data->id_direttore);
        if ($data->data_direttore_invio != null){ $delibere->setDataDirettoreInvio(new \DateTime($this->formatDateStringCustom($data->data_direttore_invio)));} else {$delibere->setDataDirettoreInvio(null); }
        if ($data->data_direttore_ritorno != null){ $delibere->setDataDirettoreRitorno(new \DateTime($this->formatDateStringCustom($data->data_direttore_ritorno)));} else {$delibere->setDataDirettoreRitorno(null); }
        $delibere->setNoteDirettore($data->note_direttore);
        $delibere->setInvioMef($data->invio_mef);
        if ($data->data_mef_invio != null){ $delibere->setDataMefInvio(new \DateTime($this->formatDateStringCustom($data->data_mef_invio)));} else {$delibere->setDataMefInvio(null); }
        if ($data->data_mef_pec != null){ $delibere->setDataMefPec(new \DateTime($this->formatDateStringCustom($data->data_mef_pec))); } else {$delibere->setDataMefPec(null); }
        if ($data->data_mef_ritorno != null){ $delibere->setDataMefRitorno(new \DateTime($this->formatDateStringCustom($data->data_mef_ritorno)));} else {$delibere->setDataMefRitorno(null); }
        $delibere->setIdSegretario($data->id_segretario);
        if ($data->data_segretario_invio != null){ $delibere->setDataSegretarioInvio(new \DateTime($this->formatDateStringCustom($data->data_segretario_invio)));} else {$delibere->setDataSegretarioInvio(null); }
        if ($data->data_segretario_ritorno != null){ $delibere->setDataSegretarioRitorno(new \DateTime($this->formatDateStringCustom($data->data_segretario_ritorno)));} else {$delibere->setDataSegretarioRitorno(null); }
        $delibere->setNoteSegretario($data->note_segretario);
        $delibere->setIdPresidente($data->id_presidente);
        if ($data->data_presidente_invio != null){ $delibere->setDataPresidenteInvio(new \DateTime($this->formatDateStringCustom($data->data_presidente_invio)));} else {$delibere->setDataPresidenteInvio(null); }
        if ($data->data_presidente_ritorno != null){ $delibere->setDataPresidenteRitorno(new \DateTime($this->formatDateStringCustom($data->data_presidente_ritorno)));} else {$delibere->setDataPresidenteRitorno(null); }
        $delibere->setNotePresidente($data->note_presidente);
        if ($data->data_invio_cc != null){ $delibere->setDataInvioCC(new \DateTime($this->formatDateStringCustom($data->data_invio_cc)));} else {$delibere->setDataInvioCC(null); }
        $delibere->setNumeroCC($data->numero_cc);
        if ($data->data_registrazione_cc != null){ $delibere->setDataRegistrazioneCC(new \DateTime($this->formatDateStringCustom($data->data_registrazione_cc)));} else {$delibere->setDataRegistrazioneCC(null); }
        $delibere->setIdRegistroCC($data->id_registro_cc);
        $delibere->setFoglioCC($data->foglio_cc);
        $delibere->setTipoRegistrazioneCC($data->tipo_registrazione_cc);
        $delibere->setNoteCC($data->note_cc);
        if ($data->data_invio_p != null){ $delibere->setDataInvioP(new \DateTime($this->formatDateStringCustom($data->data_invio_p)));} else {$delibere->setDataInvioP(null); }
        if ($data->data_invio_gu != null){ $delibere->setDataInvioGU(new \DateTime($this->formatDateStringCustom($data->data_invio_gu)));} else {$delibere->setDataInvioGU(null); }
        $delibere->setNumeroInvioGU($data->numero_invio_gu);
        $delibere->setTipoGU($data->tipo_gu);
        if ($data->data_gu != null){ $delibere->setDataGU(new \DateTime($this->formatDateStringCustom($data->data_gu)));} else {$delibere->setDataGU(null); }
        $delibere->setNumeroGU($data->numero_gu);
        if ($data->data_ec_gu != null){ $delibere->setDataEcGU(new \DateTime($this->formatDateStringCustom($data->data_ec_gu)));} else {$delibere->setDataEcGU(null); }
        $delibere->setNumeroEcGU($data->numero_ec_gu);
        if ($data->data_co_gu != null){ $delibere->setDataCoGU(new \DateTime($this->formatDateStringCustom($data->data_co_gu)));} else {$delibere->setDataCoGU(null); }
        $delibere->setNumeroCoGU($data->numero_co_gu);
        $delibere->setPubblicazioneGU($data->pubblicazione_gu);
        $delibere->setNoteGU($data->note_gu);


        // UFFICI
        foreach ($relUfficiDelibere_delete as $relUfficiDelibere_delete) {
            $em->remove($relUfficiDelibere_delete);
        }
        foreach ($data->id_uffici as $item) {
            $relUfficiDelibere = new RelUfficiDelibere();
            $relUfficiDelibere->setIdDelibere($data->id);
            $relUfficiDelibere->setIdUffici((int)$item);

            $em->persist($relUfficiDelibere); //create
        }


        // FIRMATARI (id_segretariato)
        foreach ($relFirmatariDelibere_delete as $relFirmatariDelibere_delete) {
            $em->remove($relFirmatariDelibere_delete);
        }
        foreach ($data->id_segretariato as $item) {
            $relFirmatariDelibere = new RelFirmatariDelibere();
            $relFirmatariDelibere->setIdDelibere($data->id);
            $relFirmatariDelibere->setIdFirmatari((int)$item);

            $em->persist($relFirmatariDelibere); //create
        }

        // RILIEVI CC (rilievi_CC)
        foreach ($DelibereCC_delete as $DelibereCC_delete) {
            $em->remove($DelibereCC_delete);
        }
        foreach ($data->rilievi_CC as $item) {

            $response = new Response(json_encode($item), Response::HTTP_OK);
            return $this->setBaseHeaders($response);

            $relDelibereCC = new DelibereCC();
            $relDelibereCC->setIdDelibere($data->id);
            $relDelibereCC->getTipoDocumento($item->tipo_documento);

            $em->persist($relDelibereCC); //create
        }

        // TAGS
        //rimuovo tutte le relazioni con l'id del fascicolo (per poi riaggiornale ovvero ricrearle)
        foreach ($relTagsDelibere_delete as $relTagsDelibere_delete) {
            $em->remove($relTagsDelibere_delete);
        }
        //creo le relazioni da aggiornare nella tabella RelTagsDelibere
        foreach ($array_id_tags as $item_tags) {
            $relTagsDelibere = new RelTagsDelibere();
            $relTagsDelibere->setIdDelibere($data->id);
            $relTagsDelibere->setIdTags($item_tags);
            //aggiorno (in realt� ricreo) le relazioni del fascicolo
            $em->persist($relTagsDelibere); //create
        }



        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("delibere");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente


        //Aggiorno/creo la tabella msc_delibere_giorni
        $repositoryDelibereGiorni = $em->getRepository('UserBundle:DelibereGiorni');
        $delibereGiorni = $repositoryDelibereGiorni->findOneBy(["idDelibere" => $id]);
        if (count($delibereGiorni) == 0) {
            $delibereGiorni = new DelibereGiorni();
            $delibereGiorni->setIdDelibere($id);
        }
        $delibereGiorni->setAcquisizioneSegretario($delibere->getDataConsegna());
        $delibereGiorni->setGiorniCapoDipartimento($this->differenceDate($data->data, $data->data_direttore_ritorno));
        $delibereGiorni->setGiorniMef($this->differenceDate($data->data_mef_invio,$data->data_mef_ritorno));
        $delibereGiorni->setGiorniSegretario($this->differenceDate($data->data_segretario_invio,$data->data_segretario_ritorno));
        $delibereGiorni->setGiorniPresidente($this->differenceDate($data->data_segretario_invio,$data->data_presidente_ritorno));
        $delibereGiorni->setGiorniCC($this->differenceDate($data->data_invio_cc,$data->data_registrazione_cc));
        $delibereGiorni->setGiorniGU($this->differenceDate($data->data_invio_gu,$data->data_gu));



        $em->persist($delibereGiorni); //create
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($relUfficiDelibere), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


    /**
     * @Route("/delibere", name="delibere_item_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREATE_FASCICOLI')")
     */
    public function delibereItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());

        $delibere = new Delibere();

        $delibere->setNumero($data->numero);
        if ($data->data != null){ $delibere->setData(new \DateTime($this->formatDateStringCustom($data->data))); }
        $delibere->setIdStato($data->id_stato);
        $delibere->setArgomento($data->argomento);
        $delibere->setFinanziamento($data->finanziamento);
        $delibere->setNote($data->note);
        $delibere->setNoteServizio($data->note_servizio);
        $delibere->setScheda($data->scheda);
        if ($data->data_consegna != null){ $delibere->setDataConsegna(new \DateTime($this->formatDateStringCustom($data->data_consegna)));}
        $delibere->setIdDirettore($data->id_direttore);
        if ($data->data_direttore_invio != null){ $delibere->setDataDirettoreInvio(new \DateTime($this->formatDateStringCustom($data->data_direttore_invio)));}
        if ($data->data_direttore_ritorno != null){ $delibere->setDataDirettoreRitorno(new \DateTime($this->formatDateStringCustom($data->data_direttore_ritorno)));}
        $delibere->setNoteDirettore($data->note_direttore);
        $delibere->setInvioMef($data->invio_mef);
        if ($data->data_mef_invio != null){ $delibere->setDataMefInvio(new \DateTime($this->formatDateStringCustom($data->data_mef_invio)));}
        if ($data->data_mef_pec != null){ $delibere->setDataMefPec(new \DateTime($this->formatDateStringCustom($data->data_mef_pec))); }
        if ($data->data_mef_ritorno != null){ $delibere->setDataMefRitorno(new \DateTime($this->formatDateStringCustom($data->data_mef_ritorno)));}
        $delibere->setIdSegretario($data->id_segretario);
        if ($data->data_segretario_invio != null){ $delibere->setDataSegretarioInvio(new \DateTime($this->formatDateStringCustom($data->data_segretario_invio)));}
        if ($data->data_segretario_ritorno != null){ $delibere->setDataSegretarioRitorno(new \DateTime($this->formatDateStringCustom($data->data_segretario_ritorno)));}
        $delibere->setNoteSegretario($data->note_segretario);
        $delibere->setIdPresidente($data->id_presidente);
        if ($data->data_presidente_invio != null){ $delibere->setDataPresidenteInvio(new \DateTime($this->formatDateStringCustom($data->data_presidente_invio)));}
        if ($data->data_presidente_ritorno != null){ $delibere->setDataPresidenteRitorno(new \DateTime($this->formatDateStringCustom($data->data_presidente_ritorno)));}
        $delibere->setNotePresidente($data->note_presidente);
        if ($data->data_invio_cc != null){ $delibere->setDataInvioCC(new \DateTime($this->formatDateStringCustom($data->data_invio_cc)));}
        $delibere->setNumeroCC($data->numero_cc);
        if ($data->data_registrazione_cc != null){ $delibere->setDataRegistrazioneCC(new \DateTime($this->formatDateStringCustom($data->data_registrazione_cc)));}
        $delibere->setIdRegistroCC($data->id_registro_cc);
        $delibere->setFoglioCC($data->foglio_cc);
        $delibere->setTipoRegistrazioneCC($data->tipo_registrazione_cc);
        $delibere->setNoteCC($data->note_cc);
        if ($data->data_invio_p != null){ $delibere->setDataInvioP(new \DateTime($this->formatDateStringCustom($data->data_invio_p)));}
        if ($data->data_invio_gu != null){ $delibere->setDataInvioGU(new \DateTime($this->formatDateStringCustom($data->data_invio_gu)));}
        $delibere->setNumeroInvioGU($data->numero_invio_gu);
        $delibere->setTipoGU($data->tipo_gu);
        if ($data->data_gu != null){ $delibere->setDataGU(new \DateTime($this->formatDateStringCustom($data->data_gu)));}
        $delibere->setNumeroGU($data->numero_gu);
        if ($data->data_ec_gu != null){ $delibere->setDataEcGU(new \DateTime($this->formatDateStringCustom($data->data_ec_gu)));}
        $delibere->setNumeroEcGU($data->numero_ec_gu);
        if ($data->data_co_gu != null){ $delibere->setDataCoGU(new \DateTime($this->formatDateStringCustom($data->data_co_gu)));}
        $delibere->setNumeroCoGU($data->numero_co_gu);
        $delibere->setPubblicazioneGU($data->pubblicazione_gu);
        $delibere->setNoteGU($data->note_gu);

        $em->persist($delibere);
        $em->flush(); //esegue query

        $id_delibere_creato = $delibere->getId();

        // UFFICI
        foreach ($data->id_uffici as $item) {
            $relUfficiDelibere = new RelUfficiDelibere();
            $relUfficiDelibere->setIdDelibere($id_delibere_creato);
            $relUfficiDelibere->setIdUffici((int)$item);
            $em->persist($relUfficiDelibere); //create
        }
        // FIRMATARI (id_segretariato)
        foreach ($data->id_segretariato as $item) {
            $relFirmatariDelibere = new RelFirmatariDelibere();
            $relFirmatariDelibere->setIdDelibere($id_delibere_creato);
            $relFirmatariDelibere->setIdFirmatari((int)$item);
            $em->persist($relFirmatariDelibere); //create
        }
        // TAGS
        //creo le relazioni da creare nella tabella RelTagsFascicoli
        foreach ($data->id_tags as $item) {
            $relTagsFascicoli = new RelTagsDelibere();
            $relTagsFascicoli->setIdFascicoli($id_delibere_creato);
            $relTagsFascicoli->setIdTags($item);
            //aggiorno (in realt� ricreo) le relazioni del fascicolo
            $em->persist($relTagsFascicoli); //create
        }

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("delibere");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->flush(); //esegue query


        $response = new Response($this->serialize($delibere), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }




    /**
     * @Route("/delibere/{id}", name="delibere_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_FASCICOLI')")
     */
    public function delibereItemDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:Delibere');
        $delibere = $repository->findOneById($id);

        $repository_RelUfficiDelibere = $em->getRepository('UserBundle:RelUfficiDelibere');
        $RelUfficiDelibere = $repository_RelUfficiDelibere->findByIdDelibere($id);//ricavo tutte le relazioni con l'id del delibere

        $repository_RelFirmatariDelibere = $em->getRepository('UserBundle:RelFirmatariDelibere');
        $RelFirmatariDelibere = $repository_RelFirmatariDelibere->findByIdDelibere($id);//ricavo tutte le relazioni con l'id del delibere

        $repository_RelAllegatiDelibere = $em->getRepository('UserBundle:RelAllegatiDelibere');
        $RelAllegatiDelibere = $repository_RelAllegatiDelibere->findByIdDelibere($id);//ricavo tutte le relazioni con l'id del delibere

        $repository_rel_tags = $em->getRepository('UserBundle:RelTagsDelibere');
        $relTagsDelibere_delete = $repository_rel_tags->findByIdDelibere($id);//ricavo tutte le relazioni con l'id del fascicolo

        //rimuovo tutte le relazioni con l'id del delibere (per poi riaggiornale ovvero ricrearle)
        foreach ($RelUfficiDelibere as $RelUfficiDelibere) {
            $em->remove($RelUfficiDelibere);
        }
        //rimuovo tutte le relazioni con l'id del delibere (per poi riaggiornale ovvero ricrearle)
        foreach ($RelFirmatariDelibere as $RelFirmatariDelibere) {
            $em->remove($RelFirmatariDelibere);
        }
        //rimuovo tutte le relazioni con l'id del delibere (per poi riaggiornale ovvero ricrearle)
        foreach ($RelAllegatiDelibere as $RelAllegatiDelibere) {
            $em->remove($RelAllegatiDelibere);
        }
        //rimuovo tutte le relazioni con l'id del delibere (per poi riaggiornale ovvero ricrearle)
        foreach ($relTagsDelibere_delete as $relTagsDelibere_delete) {
            $em->remove($relTagsDelibere_delete);
        }

        $em->remove($delibere); //delete

        //aggiorna la date della modifica nella tabella msc_last_updates
        $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("delibere");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->flush(); //esegue

        $response = new Response($this->serialize($delibere), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/delibere/{id}/{tipo}/upload", name="uploadDelibere")
     * @Method("POST")
     */
    public function uploadDelibereAction(Request $request, $id, $tipo)
    {

        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:Delibere');
        $delibere = $repository->findOneBy(array("id" => $id));

        $dataDelibere = $delibere->getData()->format('Y');
        $numeroDelibere = $delibere->getNumero();

        if ($tipo == "ALL" || $tipo == "DEL") {
            $path_file = Costanti::URL_ALLEGATI_DELIBERE . "per-anno/" . $dataDelibere . "/";
            $file = $request->files->get('file');
            $nome_file = $file->getClientOriginalName();
            $nome_file = "E". substr($dataDelibere, 2,4) . str_pad($numeroDelibere, 4, '0', STR_PAD_LEFT) . "-" . $tipo . "-" . $this->sostituisciAccenti($nome_file);


            if(file_exists($path_file . $nome_file)){
                // Directory
                $directory = $path_file . "E". substr($dataDelibere, 2,4) . str_pad($numeroDelibere, 4, '0', STR_PAD_LEFT) ."/versioni/";
                // Returns array of files
                $files = scandir($directory);
                // Count number of files and store them to variable..
                $num_files = count($files)-2; // Not counting the '.' and '..'.

                $path_file_version = $path_file . "E". substr($dataDelibere, 2,4) . str_pad($numeroDelibere, 4, '0', STR_PAD_LEFT) ."/versioni/". $num_files ."-". $nome_file;

                //memorizzo il file nel database
                $allegato = new Allegati();
                $allegato->setData(new \DateTime());
                $allegato->setFile($path_file_version);

                $em->persist($allegato);
                $em->flush(); //esegue query

                $id_allegato_creato = $allegato->getId();

                $allegatoRel = new RelAllegatiDelibere();
                $allegatoRel->setIdAllegati($id_allegato_creato);
                $allegatoRel->setIdDelibere($id);
                $allegatoRel->setTipo($tipo);

                $em->persist($allegatoRel);


                $repositoryAE = $em->getRepository('UserBundle:Allegati');
                $allegato_esistente = $repositoryAE->findOneBy(array("file" => $path_file . $nome_file));
                $repositoryAER = $em->getRepository('UserBundle:RelAllegatiDelibere');
                $allegato_esistente2 = $repositoryAER->findOneBy(array("idAllegati" => $allegato_esistente->getId()));



                //$response = new Response($allegato_esistente->getId(), Response::HTTP_OK);
                //return $this->setBaseHeaders($response, "upload");

                if (copy($path_file . $nome_file, $path_file_version)) {
                    //unlink($path_file . $nome_file);
                }
                $em->remove($allegato_esistente); //delete
                $em->remove($allegato_esistente2); //delete
                $em->flush(); //esegue l'update
            } else {

                //$response = new Response(json_encode("non esiste il file"), Response::HTTP_OK);
                //return $this->setBaseHeaders($response, "upload");

            }


        } else {
            $path_file = Costanti::URL_ALLEGATI_DELIBERE . $tipo . "/" . $dataDelibere . "-" . $numeroDelibere . "/";
            $file = $request->files->get('file');
            $nome_file = $file->getClientOriginalName();
            $nome_file = $this->sostituisciAccenti($nome_file);
        }

        //memorizzo il file nel database
        $allegato = new Allegati();
        $allegato->setData(new \DateTime());
        $allegato->setFile($path_file . $nome_file);

        $em->persist($allegato);
        $em->flush(); //esegue query

        $id_allegato_creato = $allegato->getId();

        $allegatoRel = new RelAllegatiDelibere();
        $allegatoRel->setIdAllegati($id_allegato_creato);
        $allegatoRel->setIdDelibere($id);
        $allegatoRel->setTipo($tipo);


        $array = array(
            'id' => $id_allegato_creato,
            'id_delibere' => $id,
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
     * @Route("/delibere/{id}/{tipo}/upload/{idallegato}", name="uploadDeleteDelibere")
     * @Method("DELETE")
     */
    public function delibereAllegatiItemDeleteAction(Request $request, $id, $tipo, $idallegato)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:RelAllegatiDelibere');
        $relazione_allegato = $repository->findBy(array('idAllegati' => $idallegato, 'idDelibere' => $id));
        //$relazione_allegato = $repository->findOneById($idallegato);

        $repository_file = $em->getRepository('UserBundle:Allegati');
        $file = $repository_file->findOneById($idallegato);

        //$idRelAllegatiRegistri = $relazione_allegato[0]->getId();

        if (!$relazione_allegato[0]) {
            $response_array = array("error" => ["code" => 409, "message" => "Questo file non e' allegato a questa delibera."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {
            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("delibere");
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
     * @Route("/deliberecc/{id}/upload", name="uploadDelibereCorteConti")
     * @Method("POST")
     */
    public function uploadDelibereCorteContiAction(Request $request, $id, $tipo)
    {

        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:Delibere');
        $delibere = $repository->findOneBy(array("id" => $id));

        $dataDelibere = $delibere->getData()->format('Y');
        $numeroDelibere = $delibere->getNumero();

        if ($tipo == "ALL" || $tipo == "DEL") {
            $path_file = Costanti::URL_ALLEGATI_DELIBERE . "per-anno/" . $dataDelibere . "/";
            $file = $request->files->get('file');
            $nome_file = $file->getClientOriginalName();
            $nome_file = "E". substr($dataDelibere, 2,4) . str_pad($numeroDelibere, 4, '0', STR_PAD_LEFT) . "-" . $tipo . "-" . $this->sostituisciAccenti($nome_file);


            if(file_exists($path_file . $nome_file)){
                // Directory
                $directory = $path_file . "E". substr($dataDelibere, 2,4) . str_pad($numeroDelibere, 4, '0', STR_PAD_LEFT) ."/versioni/";
                // Returns array of files
                $files = scandir($directory);
                // Count number of files and store them to variable..
                $num_files = count($files)-2; // Not counting the '.' and '..'.

                $path_file_version = $path_file . "E". substr($dataDelibere, 2,4) . str_pad($numeroDelibere, 4, '0', STR_PAD_LEFT) ."/versioni/". $num_files ."-". $nome_file;

                //memorizzo il file nel database
                $allegato = new Allegati();
                $allegato->setData(new \DateTime());
                $allegato->setFile($path_file_version);

                $em->persist($allegato);
                $em->flush(); //esegue query

                $id_allegato_creato = $allegato->getId();

                $allegatoRel = new RelAllegatiDelibere();
                $allegatoRel->setIdAllegati($id_allegato_creato);
                $allegatoRel->setIdDelibere($id);
                $allegatoRel->setTipo($tipo);

                $em->persist($allegatoRel);


                $repositoryAE = $em->getRepository('UserBundle:Allegati');
                $allegato_esistente = $repositoryAE->findOneBy(array("file" => $path_file . $nome_file));
                $repositoryAER = $em->getRepository('UserBundle:RelAllegatiDelibere');
                $allegato_esistente2 = $repositoryAER->findOneBy(array("idAllegati" => $allegato_esistente->getId()));



                //$response = new Response($allegato_esistente->getId(), Response::HTTP_OK);
                //return $this->setBaseHeaders($response, "upload");

                if (copy($path_file . $nome_file, $path_file_version)) {
                    //unlink($path_file . $nome_file);
                }
                $em->remove($allegato_esistente); //delete
                $em->remove($allegato_esistente2); //delete
                $em->flush(); //esegue l'update
            } else {

                //$response = new Response(json_encode("non esiste il file"), Response::HTTP_OK);
                //return $this->setBaseHeaders($response, "upload");

            }


        } else {
            $path_file = Costanti::URL_ALLEGATI_DELIBERE . $tipo . "/" . $dataDelibere . "-" . $numeroDelibere . "/";
            $file = $request->files->get('file');
            $nome_file = $file->getClientOriginalName();
            $nome_file = $this->sostituisciAccenti($nome_file);
        }

        //memorizzo il file nel database
        $allegato = new Allegati();
        $allegato->setData(new \DateTime());
        $allegato->setFile($path_file . $nome_file);

        $em->persist($allegato);
        $em->flush(); //esegue query

        $id_allegato_creato = $allegato->getId();

        $allegatoRel = new RelAllegatiDelibere();
        $allegatoRel->setIdAllegati($id_allegato_creato);
        $allegatoRel->setIdDelibere($id);
        $allegatoRel->setTipo($tipo);


        $array = array(
            'id' => $id_allegato_creato,
            'id_delibere' => $id,
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
            $file->move($path_file, $nome_file);

        } catch (\Doctrine\ORM\EntityNotFoundException $ex) {
            echo "Exception Found - " . $ex->getMessage() . "<br/>";
        }


        $response = new Response(json_encode($array), Response::HTTP_OK);
        return $this->setBaseHeaders($response, "upload");
    }



        
        
        
        
        
    /**
     * @Route("/delibere", name="delibere_options")
     * @Method("OPTIONS")
     */
    public function delibereOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
    
    /**
     * @Route("/delibere/{id2}", name="delibere_item_options")
     * @Method("OPTIONS")
     */
    public function delibereItemOptions(Request $request, $id2) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/delibere/{id}/{tipo}/upload", name="DelibereUpload_item_options")
     * @Method("OPTIONS")
     */
    public function delibereUploadItemOptions(Request $request, $id, $tipo)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/delibere/{id}/{tipo}/upload/{idallegato}", name="DelibereUploadDeleteIdAllegato_item_options")
     * @Method("OPTIONS")
     */
    public function delibereUploadDeleteIdAllegatoItemOptions(Request $request, $id, $tipo, $idallegato)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
