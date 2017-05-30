<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



class GenericController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;

    /**
     * @Route("/firmataritipo", name="firmataritipo")
     * @Method("GET")
     */
    public function firmataritipoAction(Request $request) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:FirmatariTipo');
        $firmataritipo = $repository->findAll();


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($firmataritipo),
            "data" => json_decode($this->serialize($firmataritipo)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



    /**
     * @Route("/cipeesiti", name="cipeesiti")
     * @Method("GET")
     */
    public function cipeesitiAction(Request $request) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:CipeEsiti');
        $cipeesiti = $repository->findAll();


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($cipeesiti),
            "data" => json_decode($this->serialize($cipeesiti)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }


    /**
     * @Route("/cipeesititipo", name="cipeesititipo")
     * @Method("GET")
     */
    public function cipeesititipoAction(Request $request) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:CipeEsitiTipo');
        $cipeesititipo = $repository->findAll();


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($cipeesititipo),
            "data" => json_decode($this->serialize($cipeesititipo)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



    /**
     * @Route("/cipeargomentitipo", name="cipeargomentitipo")
     * @Method("GET")
     */
    public function cipeargomentitipoAction(Request $request) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:CipeArgomentiTipo');
        $cipeargomentitipo = $repository->findAll();


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($cipeargomentitipo),
            "data" => json_decode($this->serialize($cipeargomentitipo)),
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);

        return $this->setBaseHeaders($response);
    }



    /**
     * @Route("/monitor/group/{anno}", name="monitorAnno")
     * @Method("GET")
     */
    public function monitorAnnoAction(Request $request, $anno) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:Delibere');
        $delibere = $repository->getDelibereByYear($anno);

        $serialize = json_decode($this->serialize($delibere));
        $serialize = $this->formatDateJsonArrayCustom($serialize,array("data", "data_consegna", "data_direttore_invio", "data_direttore_ritorno",
            "data_mef_invio", "data_mef_pec", "data_mef_ritorno", "data_segretario_invio", "data_segretario_ritorno", "data_presidente_invio", "data_presidente_ritorno",
            "data_invio_cc", "data_registrazione_cc", "data_invio_gu", "data_gu"));

        $arrayDelibere = array();
        $arrayDelibereGroup = array();




        foreach ($serialize as $item) {

            if (!isset($arrayDelibere[$item->data][$item->id])) {
                $arrayDelibere[$item->data][$item->id] = array(
                    "da_acquisire" => null,
                    "CD_inviare" => null,
                    "CD_firma" => null,
                    "MEF_inviare" => null,
                    "MEF_firma" => null,
                    "SEG_inviare" => null,
                    "SEG_firma" => null,
                    "PRE_inviare" => null,
                    "PRE_firma" => null,
                    "CC_inviare" => null,
                    "CC_firma" => null,
                    "CC_tipo" => $item->tipo_registrazione_cc,
                    "GU_inviare" => null,
                    "GU_firma" => null,

                    "analisi_nr" => null,
                    "analisi_consegna" => null,
                    "analisi_cd" => null,
                    "analisi_mef" => null,
                    "analisi_seg" => null,
                    "analisi_pre" => null,
                    "analisi_cc" => null,
                    "analisi_gu" => null,

                    "statistica_arrivo" => null,
                    "statistica_cd_invio_giorni" => null,
                    "statistica_cd_invio_giorni_tot" => null,
                    "statistica_cd_ritorno_giorni" => null,
                    "statistica_cd_ritorno_giorni_tot" => null,
                    "statistica_mef_invio_giorni" => null,
                    "statistica_mef_invio_giorni_tot" => null,
                    "statistica_mef_ritorno_giorni" => null,
                    "statistica_mef_ritorno_giorni_tot" => null,
                    "statistica_seg_invio_giorni" => null,
                    "statistica_seg_invio_giorni_tot" => null,
                    "statistica_seg_ritorno_giorni" => null,
                    "statistica_seg_ritorno_giorni_tot" => null,
                    "statistica_pre_invio_giorni" => null,
                    "statistica_pre_invio_giorni_tot" => null,
                    "statistica_pre_ritorno_giorni" => null,
                    "statistica_pre_ritorno_giorni_tot" => null,
                    "statistica_cc_invio_giorni" => null,
                    "statistica_cc_invio_giorni_tot" => null,
                    "statistica_cc_ritorno_giorni" => null,
                    "statistica_cc_ritorno_giorni_tot" => null,
                    "statistica_gu_invio_giorni" => null,
                    "statistica_gu_invio_giorni_tot" => null,
                    "statistica_gu_ritorno_giorni" => null,
                    "statistica_gu_ritorno_giorni_tot" => null
                );
            }

            //Analisi delle fasi
            $arrayDelibere[$item->data][$item->id]['analisi_nr'] = $item->numero;
            if ($item->data_consegna != "") {
                $arrayDelibere[$item->data][$item->id]['analisi_consegna'] = ($item->data_consegna - $item->data) / 86400000;
            }
            if ($item->data_direttore_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['analisi_cd'] = ($item->data_direttore_ritorno - $item->data_direttore_invio) / 86400000;
            }
            if ($item->data_mef_pec == null || $item->data_mef_pec == "" || $item->data_mef_pec == "0000-00-00") {
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data][$item->id]['analisi_mef'] = ($item->data_mef_ritorno - $item->data_mef_invio) / 86400000;
                }
            } else {
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data][$item->id]['analisi_mef'] = ($item->data_mef_ritorno - $item->data_mef_pec) / 86400000;
                }
            }
            if ($item->data_segretario_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['analisi_seg'] = ($item->data_segretario_ritorno - $item->data_segretario_invio) / 86400000;
            }
            if ($item->data_presidente_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['analisi_pre'] = ($item->data_presidente_ritorno - $item->data_presidente_invio) / 86400000;
            }
            if ($item->data_registrazione_cc != "") {
                $arrayDelibere[$item->data][$item->id]['analisi_cc'] = ($item->data_registrazione_cc - $item->data_invio_cc) / 86400000;
            }
            if ($item->data_gu != "" && $item->data_invio_gu != 0) {
                $arrayDelibere[$item->data][$item->id]['analisi_gu'] = ($item->data_gu - $item->data_invio_gu) / 86400000;
            }


            //statistica
            if ($item->data_consegna != "") {
                $arrayDelibere[$item->data][$item->id]['statistica_arrivo'] = ($item->data_consegna - $item->data) / 86400000;
            }
            if ($item->data_direttore_invio != "") {
                $arrayDelibere[$item->data][$item->id]['statistica_cd_invio_giorni'] = ($item->data_direttore_invio - $item->data_consegna) / 86400000;
                $arrayDelibere[$item->data][$item->id]['statistica_cd_invio_giorni_tot'] = ($item->data_direttore_invio - $item->data) / 86400000;
            }
            if ($item->data_direttore_ritorno != "") {
                if ($item->data_direttore_invio == null) { $item->data_direttore_invio = $item->data_direttore_ritorno; }
                $arrayDelibere[$item->data][$item->id]['statistica_cd_ritorno_giorni'] = ($item->data_direttore_ritorno - $item->data_direttore_invio) / 86400000;
                $arrayDelibere[$item->data][$item->id]['statistica_cd_ritorno_giorni_tot'] = ($item->data_direttore_ritorno - $item->data) / 86400000;
            }

            if ($item->data_mef_pec == null || $item->data_mef_pec == "" || $item->data_mef_pec == "0000-00-00") {
                if ($item->data_mef_invio != "") {
                    $arrayDelibere[$item->data][$item->id]['statistica_mef_invio_giorni'] = ($item->data_mef_invio - $item->data_direttore_ritorno) / 86400000;
                    $arrayDelibere[$item->data][$item->id]['statistica_mef_invio_giorni_tot'] = ($item->data_mef_invio - $item->data) / 86400000;
                }
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data][$item->id]['statistica_mef_ritorno_giorni'] = ($item->data_mef_ritorno - $item->data_mef_invio) / 86400000;
                    $arrayDelibere[$item->data][$item->id]['statistica_mef_ritorno_giorni_tot'] = ($item->data_mef_ritorno - $item->data) / 86400000;
                }
            } else {
                if ($item->data_mef_pec != "") {
                    $arrayDelibere[$item->data][$item->id]['statistica_mef_invio_giorni'] = ($item->data_mef_pec - $item->data_direttore_ritorno) / 86400000;
                    $arrayDelibere[$item->data][$item->id]['statistica_mef_invio_giorni_tot'] = ($item->data_mef_pec - $item->data) / 86400000;
                }
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data][$item->id]['statistica_mef_ritorno_giorni'] = ($item->data_mef_ritorno - $item->data_mef_pec) / 86400000;
                    $arrayDelibere[$item->data][$item->id]['statistica_mef_ritorno_giorni_tot'] = ($item->data_mef_ritorno - $item->data) / 86400000;
                }
            }

            if ($item->data_segretario_invio != "" && $item->data_mef_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['statistica_seg_invio_giorni'] = ($item->data_segretario_invio - $item->data_mef_ritorno) / 86400000;
                $arrayDelibere[$item->data][$item->id]['statistica_seg_invio_giorni_tot'] = ($item->data_segretario_invio - $item->data) / 86400000;
            }
            if ($item->data_segretario_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['statistica_seg_ritorno_giorni'] = ($item->data_segretario_ritorno - $item->data_segretario_invio) / 86400000;
                $arrayDelibere[$item->data][$item->id]['statistica_seg_ritorno_giorni_tot'] = ($item->data_segretario_ritorno - $item->data) / 86400000;
            }

            if ($item->data_presidente_invio != "" && $item->data_segretario_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['statistica_pre_invio_giorni'] = ($item->data_presidente_invio - $item->data_segretario_ritorno) / 86400000;
                $arrayDelibere[$item->data][$item->id]['statistica_pre_invio_giorni_tot'] = ($item->data_presidente_invio - $item->data) / 86400000;
            }
            if ($item->data_presidente_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['statistica_pre_ritorno_giorni'] = ($item->data_presidente_ritorno - $item->data_presidente_invio) / 86400000;
                $arrayDelibere[$item->data][$item->id]['statistica_pre_ritorno_giorni_tot'] = ($item->data_presidente_ritorno - $item->data) / 86400000;
            }

            if ($item->data_invio_cc != "") {
                $arrayDelibere[$item->data][$item->id]['statistica_cc_invio_giorni'] = ($item->data_invio_cc - $item->data_presidente_ritorno) / 86400000;
                $arrayDelibere[$item->data][$item->id]['statistica_cc_invio_giorni_tot'] = ($item->data_invio_cc - $item->data) / 86400000;
            }
            if ($item->data_registrazione_cc != "") {
                $arrayDelibere[$item->data][$item->id]['statistica_cc_ritorno_giorni'] = ($item->data_registrazione_cc - $item->data_invio_cc) / 86400000;
                $arrayDelibere[$item->data][$item->id]['statistica_cc_ritorno_giorni_tot'] = ($item->data_registrazione_cc - $item->data) / 86400000;
            }

            if ($item->data_invio_gu != "") {
                if ($item->data_registrazione_cc == null) { $item->data_registrazione_cc = $item->data_invio_gu; }
                $arrayDelibere[$item->data][$item->id]['statistica_gu_invio_giorni'] = ($item->data_invio_gu - $item->data_registrazione_cc) / 86400000;
                $arrayDelibere[$item->data][$item->id]['statistica_gu_invio_giorni_tot'] = ($item->data_invio_gu - $item->data) / 86400000;
            }
            if ($item->data_gu != "") {
                if ($item->data_invio_gu == null) { $item->data_invio_gu = $item->data_gu; }
                $arrayDelibere[$item->data][$item->id]['statistica_gu_ritorno_giorni'] = ($item->data_gu - $item->data_invio_gu) / 86400000;
                $arrayDelibere[$item->data][$item->id]['statistica_gu_ritorno_giorni_tot'] = ($item->data_gu - $item->data) / 86400000;
            }



            //situazione
            if ($item->data_consegna == "") {
                $arrayDelibere[$item->data][$item->id]['da_acquisire'] = 1;
                continue;
            }
            if ($item->data_direttore_invio == "") {
                $arrayDelibere[$item->data][$item->id]['CD_inviare'] = 1;
                continue;
            }
            if (($item->data_direttore_ritorno =="")
                && (($item->data_direttore_invio != ""))
            ) {
                $arrayDelibere[$item->data][$item->id]['CD_firma'] = 1;
                continue;
            }

            if (($item->data_mef_invio == "")
                && ($item->data_mef_pec == "")
            ) {
                $arrayDelibere[$item->data][$item->id]['MEF_inviare'] = 1;
                continue;
            }
            if (($item->data_mef_ritorno == "")
                && (($item->data_mef_invio != ""))
                && (($item->data_mef_pec != ""))
            ) {
                $arrayDelibere[$item->data][$item->id]['MEF_firma'] = 1;
                continue;
            }

            if ($item->data_segretario_invio == "") {
                $arrayDelibere[$item->data][$item->id]['SEG_inviare'] = 1;
                //continue;
            }
            if (($item->data_segretario_ritorno == "")
                && (($item->data_segretario_invio != ""))
            ) {
                $arrayDelibere[$item->data][$item->id]['SEG_firma'] = 1;
                //continue;
            }

            if ($item->data_presidente_invio == "") {
                $arrayDelibere[$item->data][$item->id]['PRE_inviare'] = 1;
                continue;
            }
            if (($item->data_presidente_ritorno == "")
                && (($item->data_presidente_invio != ""))
            ) {
                $arrayDelibere[$item->data][$item->id]['PRE_firma'] = 1;
                continue;
            }

            if ($item->data_invio_cc == "") {
                $arrayDelibere[$item->data][$item->id]['CC_inviare'] = 1;
                continue;
            }
            if (($item->data_registrazione_cc == "")
                && (($item->data_invio_cc != ""))
            ) {
                $arrayDelibere[$item->data][$item->id]['CC_firma'] = 1;
                continue;
            }

            if ($item->data_invio_gu == "") {
                $arrayDelibere[$item->data][$item->id]['GU_inviare'] = 1;
                continue;
            }
            if (($item->data_gu == "")
                && (($item->data_invio_gu != ""))
            ) {
                $arrayDelibere[$item->data][$item->id]['GU_firma'] = 1;
                continue;
            }
        }



        //#### RAGGRUPPO PER DATA
        foreach ($arrayDelibere as $i => $v) {
            $arrayDelibereGroup[$i]['situazione']['count'] = count($arrayDelibere[$i]);
            $arrayDelibereGroup[$i]['situazione']['da_acquisire'] = 0;
            $arrayDelibereGroup[$i]['situazione']['CD_inviare'] = 0;
            $arrayDelibereGroup[$i]['situazione']['CD_firma'] = 0;
            $arrayDelibereGroup[$i]['situazione']['MEF_inviare'] = 0;
            $arrayDelibereGroup[$i]['situazione']['MEF_firma'] = 0;
            $arrayDelibereGroup[$i]['situazione']['SEG_inviare'] = 0;
            $arrayDelibereGroup[$i]['situazione']['SEG_firma'] = 0;
            $arrayDelibereGroup[$i]['situazione']['PRE_inviare'] = 0;
            $arrayDelibereGroup[$i]['situazione']['PRE_firma'] = 0;
            $arrayDelibereGroup[$i]['situazione']['CC_inviare'] = 0;
            $arrayDelibereGroup[$i]['situazione']['CC_firma'] = 0;
            $arrayDelibereGroup[$i]['situazione']['GU_inviare'] = 0;
            $arrayDelibereGroup[$i]['situazione']['GU_firma'] = 0;

            $arrayDelibereGroup[$i]['statistica']['count'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_arrivo'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_cd_invio_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_cd_invio_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_cd_ritorno_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_cd_ritorno_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_mef_invio_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_mef_invio_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_mef_ritorno_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_mef_ritorno_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_seg_invio_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_seg_invio_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_seg_ritorno_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_seg_ritorno_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_pre_invio_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_pre_invio_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_pre_ritorno_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_pre_ritorno_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_cc_invio_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_cc_invio_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_cc_ritorno_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_cc_ritorno_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_gu_invio_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_gu_invio_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_gu_ritorno_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['statistica_gu_ritorno_giorni_tot'] = 0;

            $arrayDelibereGroup[$i]['analisi']['count'] = count($arrayDelibere[$i]);
            $arrayDelibereGroup[$i]['analisi']['consegna'] = 0;
            $arrayDelibereGroup[$i]['analisi']['consegna_media'] = 0;
            $arrayDelibereGroup[$i]['analisi']['cd'] = 0;
            $arrayDelibereGroup[$i]['analisi']['cd_media'] = 0;
            $arrayDelibereGroup[$i]['analisi']['mef'] = 0;
            $arrayDelibereGroup[$i]['analisi']['mef_media'] = 0;
            $arrayDelibereGroup[$i]['analisi']['seg'] = 0;
            $arrayDelibereGroup[$i]['analisi']['seg_media'] = 0;
            $arrayDelibereGroup[$i]['analisi']['pre'] = 0;
            $arrayDelibereGroup[$i]['analisi']['pre_media'] = 0;
            $arrayDelibereGroup[$i]['analisi']['cc'] = 0;
            $arrayDelibereGroup[$i]['analisi']['cc_media'] = 0;
            $arrayDelibereGroup[$i]['analisi']['gu'] = 0;
            $arrayDelibereGroup[$i]['analisi']['gu_media'] = 0;

            foreach ($arrayDelibere[$i] as $k => $z) {
                if ($arrayDelibere[$i][$k]['da_acquisire'] != null) {
                    $arrayDelibereGroup[$i]['situazione']['da_acquisire'] = $arrayDelibereGroup[$i]['situazione']['da_acquisire'] + 1;
                }
                if ($arrayDelibere[$i][$k]['CD_inviare'] != null) {
                    $arrayDelibereGroup[$i]['situazione']['CD_inviare'] = $arrayDelibereGroup[$i]['situazione']['CD_inviare'] + 1;
                }
                if ($arrayDelibere[$i][$k]['CD_firma'] != null) {
                    $arrayDelibereGroup[$i]['situazione']['CD_firma'] = $arrayDelibereGroup[$i]['situazione']['CD_firma'] + 1;
                }
                if ($arrayDelibere[$i][$k]['MEF_inviare'] != null) {
                    $arrayDelibereGroup[$i]['situazione']['MEF_inviare'] = $arrayDelibereGroup[$i]['situazione']['MEF_inviare'] + 1;
                }
                if ($arrayDelibere[$i][$k]['MEF_firma'] != null) {
                    $arrayDelibereGroup[$i]['situazione']['MEF_firma'] = $arrayDelibereGroup[$i]['situazione']['MEF_firma'] + 1;
                }
                if ($arrayDelibere[$i][$k]['SEG_inviare'] != null) {
                    $arrayDelibereGroup[$i]['situazione']['SEG_inviare'] = $arrayDelibereGroup[$i]['situazione']['SEG_inviare'] + 1;
                }
                if ($arrayDelibere[$i][$k]['SEG_firma'] != null) {
                    $arrayDelibereGroup[$i]['situazione']['SEG_firma'] = $arrayDelibereGroup[$i]['situazione']['SEG_firma'] + 1;
                }
                if ($arrayDelibere[$i][$k]['PRE_inviare'] != null) {
                    $arrayDelibereGroup[$i]['situazione']['PRE_inviare'] = $arrayDelibereGroup[$i]['situazione']['PRE_inviare'] + 1;
                }
                if ($arrayDelibere[$i][$k]['PRE_firma'] != null) {
                    $arrayDelibereGroup[$i]['situazione']['PRE_firma'] = $arrayDelibereGroup[$i]['situazione']['PRE_firma'] + 1;
                }
                if ($arrayDelibere[$i][$k]['CC_inviare'] != null && $arrayDelibere[$i][$k]['CC_tipo'] != 1 && $arrayDelibere[$i][$k]['CC_tipo'] != 2 && $arrayDelibere[$i][$k]['CC_tipo'] != 3 && $arrayDelibere[$i][$k]['CC_tipo'] != 5) {
                    $arrayDelibereGroup[$i]['situazione']['CC_inviare'] = $arrayDelibereGroup[$i]['situazione']['CC_inviare'] + 1;
                }
                if ($arrayDelibere[$i][$k]['CC_firma'] != null && $arrayDelibere[$i][$k]['CC_tipo'] != 1 && $arrayDelibere[$i][$k]['CC_tipo'] != 2 && $arrayDelibere[$i][$k]['CC_tipo'] != 3 && $arrayDelibere[$i][$k]['CC_tipo'] != 5) {
                    $arrayDelibereGroup[$i]['situazione']['CC_firma'] = $arrayDelibereGroup[$i]['situazione']['CC_firma'] + 1;
                }
                if ($arrayDelibere[$i][$k]['GU_inviare'] != null) {
                    $arrayDelibereGroup[$i]['situazione']['GU_inviare'] = $arrayDelibereGroup[$i]['situazione']['GU_inviare'] + 1;
                }
                if ($arrayDelibere[$i][$k]['GU_firma'] != null) {
                    $arrayDelibereGroup[$i]['situazione']['GU_firma'] = $arrayDelibereGroup[$i]['situazione']['GU_firma'] + 1;
                }


                if ($arrayDelibere[$i][$k]['analisi_consegna'] != null) {
                    $arrayDelibereGroup[$i]['analisi']['consegna'] = $arrayDelibereGroup[$i]['analisi']['consegna'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['consegna_media'] = $arrayDelibereGroup[$i]['analisi']['consegna_media'] + $arrayDelibere[$i][$k]['analisi_consegna'];
                }

                if ($arrayDelibere[$i][$k]['analisi_cd'] != null || $arrayDelibere[$i][$k]['analisi_cd'] == 0) {
                    $arrayDelibereGroup[$i]['analisi']['cd'] = $arrayDelibereGroup[$i]['analisi']['cd'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['cd_media'] = $arrayDelibereGroup[$i]['analisi']['cd_media'] + $arrayDelibere[$i][$k]['analisi_cd'];
                }

                if ($arrayDelibere[$i][$k]['analisi_mef'] != null) {
                    $arrayDelibereGroup[$i]['analisi']['mef'] = $arrayDelibereGroup[$i]['analisi']['mef'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['mef_media'] = $arrayDelibereGroup[$i]['analisi']['mef_media'] + $arrayDelibere[$i][$k]['analisi_mef'];
                }

                if ($arrayDelibere[$i][$k]['analisi_seg'] != null) {
                    $arrayDelibereGroup[$i]['analisi']['seg'] = $arrayDelibereGroup[$i]['analisi']['seg'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['seg_media'] = $arrayDelibereGroup[$i]['analisi']['seg_media'] + $arrayDelibere[$i][$k]['analisi_seg'];
                }

                if ($arrayDelibere[$i][$k]['analisi_pre'] != null) {
                    $arrayDelibereGroup[$i]['analisi']['pre'] = $arrayDelibereGroup[$i]['analisi']['pre'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['pre_media'] = $arrayDelibereGroup[$i]['analisi']['pre_media'] + $arrayDelibere[$i][$k]['analisi_pre'];
                }

                if ($arrayDelibere[$i][$k]['analisi_cc'] != null) {
                    $arrayDelibereGroup[$i]['analisi']['cc'] = $arrayDelibereGroup[$i]['analisi']['cc'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['cc_media'] = $arrayDelibereGroup[$i]['analisi']['cc_media'] + $arrayDelibere[$i][$k]['analisi_cc'];
                }

                if ($arrayDelibere[$i][$k]['analisi_gu'] != null ) {
                    $arrayDelibereGroup[$i]['analisi']['gu'] = $arrayDelibereGroup[$i]['analisi']['gu'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['gu_media'] = $arrayDelibereGroup[$i]['analisi']['gu_media'] + $arrayDelibere[$i][$k]['analisi_gu'];
                }



                $arrayDelibereGroup[$i]['statistica']['count'] = $arrayDelibereGroup[$i]['statistica']['count'] + 1;

                $arrayDelibereGroup[$i]['statistica']['statistica_arrivo'] = $arrayDelibereGroup[$i]['statistica']['statistica_arrivo'] + $arrayDelibere[$i][$k]['statistica_arrivo'];

                $arrayDelibereGroup[$i]['statistica']['statistica_cd_invio_giorni'] = $arrayDelibereGroup[$i]['statistica']['statistica_cd_invio_giorni'] + $arrayDelibere[$i][$k]['statistica_cd_invio_giorni'];
                
                $arrayDelibereGroup[$i]['statistica']['statistica_cd_invio_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['statistica_cd_invio_giorni_tot'] + $arrayDelibere[$i][$k]['statistica_cd_invio_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['statistica_cd_ritorno_giorni'] = $arrayDelibereGroup[$i]['statistica']['statistica_cd_ritorno_giorni'] + $arrayDelibere[$i][$k]['statistica_cd_ritorno_giorni'];

                $arrayDelibereGroup[$i]['statistica']['statistica_cd_ritorno_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['statistica_cd_ritorno_giorni_tot'] + $arrayDelibere[$i][$k]['statistica_cd_ritorno_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['statistica_mef_invio_giorni'] = $arrayDelibereGroup[$i]['statistica']['statistica_mef_invio_giorni'] + $arrayDelibere[$i][$k]['statistica_mef_invio_giorni'];

                $arrayDelibereGroup[$i]['statistica']['statistica_mef_invio_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['statistica_mef_invio_giorni_tot'] + $arrayDelibere[$i][$k]['statistica_mef_invio_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['statistica_mef_ritorno_giorni'] = $arrayDelibereGroup[$i]['statistica']['statistica_mef_ritorno_giorni'] + $arrayDelibere[$i][$k]['statistica_mef_ritorno_giorni'];

                $arrayDelibereGroup[$i]['statistica']['statistica_mef_ritorno_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['statistica_mef_ritorno_giorni_tot'] + $arrayDelibere[$i][$k]['statistica_mef_ritorno_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['statistica_seg_invio_giorni'] = $arrayDelibereGroup[$i]['statistica']['statistica_seg_invio_giorni'] + $arrayDelibere[$i][$k]['statistica_seg_invio_giorni'];

                $arrayDelibereGroup[$i]['statistica']['statistica_seg_invio_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['statistica_seg_invio_giorni_tot'] + $arrayDelibere[$i][$k]['statistica_seg_invio_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['statistica_seg_ritorno_giorni'] = $arrayDelibereGroup[$i]['statistica']['statistica_seg_ritorno_giorni'] + $arrayDelibere[$i][$k]['statistica_seg_ritorno_giorni'];

                $arrayDelibereGroup[$i]['statistica']['statistica_seg_ritorno_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['statistica_seg_ritorno_giorni_tot'] + $arrayDelibere[$i][$k]['statistica_seg_ritorno_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['statistica_pre_invio_giorni'] = $arrayDelibereGroup[$i]['statistica']['statistica_pre_invio_giorni'] + $arrayDelibere[$i][$k]['statistica_pre_invio_giorni'];

                $arrayDelibereGroup[$i]['statistica']['statistica_pre_invio_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['statistica_pre_invio_giorni_tot'] + $arrayDelibere[$i][$k]['statistica_pre_invio_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['statistica_pre_ritorno_giorni'] = $arrayDelibereGroup[$i]['statistica']['statistica_pre_ritorno_giorni'] + $arrayDelibere[$i][$k]['statistica_pre_ritorno_giorni'];

                $arrayDelibereGroup[$i]['statistica']['statistica_pre_ritorno_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['statistica_pre_ritorno_giorni_tot'] + $arrayDelibere[$i][$k]['statistica_pre_ritorno_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['statistica_cc_invio_giorni'] = $arrayDelibereGroup[$i]['statistica']['statistica_cc_invio_giorni'] + $arrayDelibere[$i][$k]['statistica_cc_invio_giorni'];

                $arrayDelibereGroup[$i]['statistica']['statistica_cc_invio_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['statistica_cc_invio_giorni_tot'] + $arrayDelibere[$i][$k]['statistica_cc_invio_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['statistica_cc_ritorno_giorni'] = $arrayDelibereGroup[$i]['statistica']['statistica_cc_ritorno_giorni'] + $arrayDelibere[$i][$k]['statistica_cc_ritorno_giorni'];

                $arrayDelibereGroup[$i]['statistica']['statistica_cc_ritorno_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['statistica_cc_ritorno_giorni_tot'] + $arrayDelibere[$i][$k]['statistica_cc_ritorno_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['statistica_gu_invio_giorni'] = $arrayDelibereGroup[$i]['statistica']['statistica_gu_invio_giorni'] + $arrayDelibere[$i][$k]['statistica_gu_invio_giorni'];

                $arrayDelibereGroup[$i]['statistica']['statistica_gu_invio_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['statistica_gu_invio_giorni_tot'] + $arrayDelibere[$i][$k]['statistica_gu_invio_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['statistica_gu_ritorno_giorni'] = $arrayDelibereGroup[$i]['statistica']['statistica_gu_ritorno_giorni'] + $arrayDelibere[$i][$k]['statistica_gu_ritorno_giorni'];

                $arrayDelibereGroup[$i]['statistica']['statistica_gu_ritorno_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['statistica_gu_ritorno_giorni_tot'] + $arrayDelibere[$i][$k]['statistica_gu_ritorno_giorni_tot'];


            }


            //medie
            if ($arrayDelibereGroup[$i]['analisi']['consegna'] != 0) {
                $arrayDelibereGroup[$i]['analisi']['consegna_media'] = round($arrayDelibereGroup[$i]['analisi']['consegna_media'] / $arrayDelibereGroup[$i]['analisi']['consegna']);
            }
            if ($arrayDelibereGroup[$i]['analisi']['cd'] != 0) {
                $arrayDelibereGroup[$i]['analisi']['cd_media'] = round($arrayDelibereGroup[$i]['analisi']['cd_media'] / $arrayDelibereGroup[$i]['analisi']['cd']);
            }
            if ($arrayDelibereGroup[$i]['analisi']['mef'] != 0) {
                $arrayDelibereGroup[$i]['analisi']['mef_media'] = round($arrayDelibereGroup[$i]['analisi']['mef_media'] / $arrayDelibereGroup[$i]['analisi']['mef']);
            }
            if ($arrayDelibereGroup[$i]['analisi']['seg'] != 0) {
                $arrayDelibereGroup[$i]['analisi']['seg_media'] = round($arrayDelibereGroup[$i]['analisi']['seg_media'] / $arrayDelibereGroup[$i]['analisi']['seg']);
            }
            if ($arrayDelibereGroup[$i]['analisi']['pre'] != 0) {
                $arrayDelibereGroup[$i]['analisi']['pre_media'] = round($arrayDelibereGroup[$i]['analisi']['pre_media'] / $arrayDelibereGroup[$i]['analisi']['pre']);
            }
            if ($arrayDelibereGroup[$i]['analisi']['cc'] != 0) {
                $arrayDelibereGroup[$i]['analisi']['cc_media'] = round($arrayDelibereGroup[$i]['analisi']['cc_media'] / $arrayDelibereGroup[$i]['analisi']['cc']);
            }
            if ($arrayDelibereGroup[$i]['analisi']['gu'] != 0) {
                $arrayDelibereGroup[$i]['analisi']['gu_media'] = round($arrayDelibereGroup[$i]['analisi']['gu_media'] / $arrayDelibereGroup[$i]['analisi']['gu']);
            }


            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_arrivo'] = round($arrayDelibereGroup[$i]['statistica']['statistica_arrivo'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_cd_invio_giorni'] = round($arrayDelibereGroup[$i]['statistica']['statistica_cd_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_cd_invio_giorni'] = round($arrayDelibereGroup[$i]['statistica']['statistica_cd_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_cd_invio_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['statistica_cd_invio_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_cd_ritorno_giorni'] = round($arrayDelibereGroup[$i]['statistica']['statistica_cd_ritorno_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_cd_ritorno_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['statistica_cd_ritorno_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_mef_invio_giorni'] = round($arrayDelibereGroup[$i]['statistica']['statistica_mef_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_mef_invio_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['statistica_mef_invio_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_mef_ritorno_giorni'] = round($arrayDelibereGroup[$i]['statistica']['statistica_mef_ritorno_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_mef_ritorno_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['statistica_mef_ritorno_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_seg_invio_giorni'] = $this->negativeToZero(round($arrayDelibereGroup[$i]['statistica']['statistica_seg_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']));
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_seg_invio_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['statistica_seg_invio_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_seg_ritorno_giorni'] = round($arrayDelibereGroup[$i]['statistica']['statistica_seg_ritorno_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_seg_ritorno_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['statistica_seg_ritorno_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_pre_invio_giorni'] = $this->negativeToZero(round($arrayDelibereGroup[$i]['statistica']['statistica_pre_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']));
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_pre_invio_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['statistica_pre_invio_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_pre_ritorno_giorni'] = round($arrayDelibereGroup[$i]['statistica']['statistica_pre_ritorno_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_pre_ritorno_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['statistica_pre_ritorno_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_cc_invio_giorni'] = round($arrayDelibereGroup[$i]['statistica']['statistica_cc_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_cc_invio_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['statistica_cc_invio_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_cc_ritorno_giorni'] = round($arrayDelibereGroup[$i]['statistica']['statistica_cc_ritorno_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_cc_ritorno_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['statistica_cc_ritorno_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_gu_invio_giorni'] = round($arrayDelibereGroup[$i]['statistica']['statistica_gu_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_gu_invio_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['statistica_gu_invio_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_gu_ritorno_giorni'] = round($arrayDelibereGroup[$i]['statistica']['statistica_gu_ritorno_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['statistica_gu_ritorno_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['statistica_gu_ritorno_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
        }


        // riformatto l'array
        $arrayDelibereGroupFormattato = array();
        foreach ($arrayDelibereGroup as $kk => $vv) {
            $arrayDelibereGroupFormattato[] = array (
                "id" => $kk,
                "situazione" => $arrayDelibereGroup[$kk]['situazione'],
                "statistica" => $arrayDelibereGroup[$kk]['statistica'],
                "analisi" => $arrayDelibereGroup[$kk]['analisi']
            );
        }



        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($delibere),
            "data" => $arrayDelibereGroupFormattato,
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


    
    
    
    
    
    
    /**
     * @Route("/monitor/{data}", name="monitorData")
     * @Method("GET")
     */
    public function monitorDataAction(Request $request, $data) {
        $repository = $this->getDoctrine()->getRepository('UserBundle:Delibere');
        $delibere = $repository->getDelibereByData($data);

        $serialize = json_decode($this->serialize($delibere));
        $serialize = $this->formatDateJsonArrayCustom($serialize,array("data", "data_consegna", "data_direttore_invio", "data_direttore_ritorno",
            "data_mef_invio", "data_mef_pec", "data_mef_ritorno", "data_segretario_invio", "data_segretario_ritorno", "data_presidente_invio", "data_presidente_ritorno",
            "data_invio_cc", "data_registrazione_cc", "data_invio_gu", "data_gu"));

        $arrayDelibere = array();
        foreach ($serialize as $item) {

            if (!isset($arrayDelibere[$item->data][$item->id])) {
                $arrayDelibere[$item->data]['situazione'][$item->id] = array(
                    "da_acquisire" => null,
                    "CD_inviare" => null,
                    "CD_firma" => null,
                    "MEF_inviare" => null,
                    "MEF_firma" => null,
                    "SEG_inviare" => null,
                    "SEG_firma" => null,
                    "PRE_inviare" => null,
                    "PRE_firma" => null,
                    "CC_inviare" => null,
                    "CC_firma" => null,
                    "GU_inviare" => null,
                    "GU_firma" => null,
                );
                $arrayDelibere[$item->data]['analisi'][$item->id] = array(
                    "analisi_nr" => null,
                    "analisi_consegna" => null,
                    "analisi_cd" => null,
                    "analisi_mef" => null,
                    "analisi_seg" => null,
                    "analisi_pre" => null,
                    "analisi_cc" => null,
                    "analisi_gu" => null,
                );
                $arrayDelibere[$item->data]['statistica'][$item->id] = array(
                    "statistica_arrivo" => null,
                    "statistica_cd_invio_giorni" => null,
                    "statistica_cd_invio_giorni_tot" => null,
                    "statistica_cd_ritorno_giorni" => null,
                    "statistica_cd_ritorno_giorni_tot" => null,
                    "statistica_mef_invio_giorni" => null,
                    "statistica_mef_invio_giorni_tot" => null,
                    "statistica_mef_ritorno_giorni" => null,
                    "statistica_mef_ritorno_giorni_tot" => null,
                    "statistica_seg_invio_giorni" => null,
                    "statistica_seg_invio_giorni_tot" => null,
                    "statistica_seg_ritorno_giorni" => null,
                    "statistica_seg_ritorno_giorni_tot" => null,
                    "statistica_pre_invio_giorni" => null,
                    "statistica_pre_invio_giorni_tot" => null,
                    "statistica_pre_ritorno_giorni" => null,
                    "statistica_pre_ritorno_giorni_tot" => null,
                    "statistica_cc_invio_giorni" => null,
                    "statistica_cc_invio_giorni_tot" => null,
                    "statistica_cc_ritorno_giorni" => null,
                    "statistica_cc_ritorno_giorni_tot" => null,
                    "statistica_gu_invio_giorni" => null,
                    "statistica_gu_invio_giorni_tot" => null,
                    "statistica_gu_ritorno_giorni" => null,
                    "statistica_gu_ritorno_giorni_tot" => null
                );
            }

            //Analisi delle fasi
            $arrayDelibere[$item->data]['analisi'][$item->id]['analisi_nr'] = $item->numero;
            if ($item->data_consegna != "") {
                $arrayDelibere[$item->data]['analisi'][$item->id]['analisi_consegna'] = ($item->data_consegna - $item->data) / 86400000;
            }
            if ($item->data_direttore_ritorno != "") {
                $arrayDelibere[$item->data]['analisi'][$item->id]['analisi_cd'] = ($item->data_direttore_ritorno - $item->data_direttore_invio) / 86400000;
            }
            if ($item->data_mef_pec == null || $item->data_mef_pec == "" || $item->data_mef_pec == "0000-00-00") {
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data]['analisi'][$item->id]['analisi_mef'] = ($item->data_mef_ritorno - $item->data_mef_invio) / 86400000;
                }
            } else {
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data]['analisi'][$item->id]['analisi_mef'] = ($item->data_mef_ritorno - $item->data_mef_pec) / 86400000;
                }
            }
            if ($item->data_segretario_ritorno != "") {
                $arrayDelibere[$item->data]['analisi'][$item->id]['analisi_seg'] = ($item->data_segretario_ritorno - $item->data_segretario_invio) / 86400000;
            }
            if ($item->data_presidente_ritorno != "") {
                $arrayDelibere[$item->data]['analisi'][$item->id]['analisi_pre'] = ($item->data_presidente_ritorno - $item->data_presidente_invio) / 86400000;
            }
            if ($item->data_registrazione_cc != "") {
                $arrayDelibere[$item->data]['analisi'][$item->id]['analisi_cc'] = ($item->data_registrazione_cc - $item->data_invio_cc) / 86400000;
            }
            if ($item->data_gu != "") {
                $arrayDelibere[$item->data]['analisi'][$item->id]['analisi_gu'] = ($item->data_gu - $item->data_invio_gu) / 86400000;
            }

            
            //statistica
            $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_nr'] = $item->numero;
            if ($item->data_consegna != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_arrivo'] = $this->negativeToZero(round(($item->data_consegna - $item->data) / 86400000));
            }
            if ($item->data_direttore_invio != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_cd_invio_giorni'] = $this->negativeToZero(round(($item->data_direttore_invio - $item->data_consegna) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_cd_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_direttore_invio - $item->data) / 86400000));
            }
            if ($item->data_direttore_ritorno != "") {
                if ($item->data_direttore_invio == null) { $item->data_direttore_invio = $item->data_direttore_ritorno; }
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_cd_ritorno_giorni'] = $this->negativeToZero(round(($item->data_direttore_ritorno - $item->data_direttore_invio) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_cd_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_direttore_ritorno - $item->data) / 86400000));
            }

            if ($item->data_mef_pec == null || $item->data_mef_pec == "" || $item->data_mef_pec == "0000-00-00") {
                if ($item->data_mef_invio != "") {
                    $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_mef_invio_giorni'] = $this->negativeToZero(round(($item->data_mef_invio - $item->data_direttore_ritorno) / 86400000));
                    $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_mef_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_mef_invio - $item->data) / 86400000));
                }
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_mef_ritorno_giorni'] = $this->negativeToZero(round(($item->data_mef_ritorno - $item->data_mef_invio) / 86400000));
                    $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_mef_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_mef_ritorno - $item->data) / 86400000));
                }
            } else {
                if ($item->data_mef_pec != "") {
                    $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_mef_invio_giorni'] = $this->negativeToZero(round(($item->data_mef_pec - $item->data_direttore_ritorno) / 86400000));
                    $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_mef_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_mef_pec - $item->data) / 86400000));
                }
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_mef_ritorno_giorni'] = $this->negativeToZero(round(($item->data_mef_ritorno - $item->data_mef_pec) / 86400000));
                    $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_mef_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_mef_ritorno - $item->data) / 86400000));
                }
            }

            if ($item->data_segretario_invio != "" && $item->data_mef_ritorno != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_seg_invio_giorni'] = $this->negativeToZero(($item->data_segretario_invio - $item->data_mef_ritorno) / 86400000);
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_seg_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_segretario_invio - $item->data) / 86400000));
            }
            if ($item->data_segretario_ritorno != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_seg_ritorno_giorni'] = $this->negativeToZero(round(($item->data_segretario_ritorno - $item->data_segretario_invio) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_seg_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_segretario_ritorno - $item->data) / 86400000));
            }

            if ($item->data_presidente_invio != "" && $item->data_segretario_ritorno != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_pre_invio_giorni'] = $this->negativeToZero(round(($item->data_presidente_invio - $item->data_segretario_ritorno) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_pre_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_presidente_invio - $item->data) / 86400000));
            }
            if ($item->data_presidente_ritorno != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_pre_ritorno_giorni'] = $this->negativeToZero(round(($item->data_presidente_ritorno - $item->data_presidente_invio) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_pre_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_presidente_ritorno - $item->data) / 86400000));
            }

            if ($item->data_invio_cc != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_cc_invio_giorni'] = $this->negativeToZero(round(($item->data_invio_cc - $item->data_presidente_ritorno) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_cc_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_invio_cc - $item->data) / 86400000));
            }
            if ($item->data_registrazione_cc != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_cc_ritorno_giorni'] = $this->negativeToZero(round(($item->data_registrazione_cc - $item->data_invio_cc) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_cc_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_registrazione_cc - $item->data) / 86400000));
            }

            if ($item->data_invio_gu != "") {
                if ($item->data_registrazione_cc == null) { $item->data_registrazione_cc = $item->data_invio_gu; }
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_gu_invio_giorni'] = $this->negativeToZero(round(($item->data_invio_gu - $item->data_registrazione_cc) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_gu_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_invio_gu - $item->data) / 86400000));
            }
            if ($item->data_gu != "") {
                if ($item->data_invio_gu == null) { $item->data_invio_gu = $item->data_gu; }
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_gu_ritorno_giorni'] = $this->negativeToZero(round(($item->data_gu - $item->data_invio_gu) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['statistica_gu_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_gu - $item->data) / 86400000));
            }



            //situazione
            if ($item->data_consegna == "") {
                $arrayDelibere[$item->data]['situazione'][$item->id]['da_acquisire'] = 1;
                continue;
            }
            if ($item->data_direttore_invio == "") {
                $arrayDelibere[$item->data]['situazione'][$item->id]['CD_inviare'] = 1;
                continue;
            }
            if (($item->data_direttore_ritorno =="")
                && (($item->data_direttore_invio != ""))
            ) {
                $arrayDelibere[$item->data]['situazione'][$item->id]['CD_firma'] = 1;
                continue;
            }

            if (($item->data_mef_invio == "")
                && ($item->data_mef_pec == "")
            ) {
                $arrayDelibere[$item->data]['situazione'][$item->id]['MEF_inviare'] = 1;
                continue;
            }
            if (($item->data_mef_ritorno == "")
                && (($item->data_mef_invio != ""))
                && (($item->data_mef_pec != ""))
            ) {
                $arrayDelibere[$item->data]['situazione'][$item->id]['MEF_firma'] = 1;
                continue;
            }

            if ($item->data_segretario_invio == "") {
                $arrayDelibere[$item->data]['situazione'][$item->id]['SEG_inviare'] = 1;
                //continue;
            }
            if (($item->data_segretario_ritorno == "")
                && (($item->data_segretario_invio != ""))
            ) {
                $arrayDelibere[$item->data]['situazione'][$item->id]['SEG_firma'] = 1;
                //continue;
            }

            if ($item->data_presidente_invio == "") {
                $arrayDelibere[$item->data]['situazione'][$item->id]['PRE_inviare'] = 1;
                continue;
            }
            if (($item->data_presidente_ritorno == "")
                && (($item->data_presidente_invio != ""))
            ) {
                $arrayDelibere[$item->data]['situazione'][$item->id]['PRE_firma'] = 1;
                continue;
            }

            if ($item->data_invio_cc == "") {
                $arrayDelibere[$item->data]['situazione'][$item->id]['CC_inviare'] = 1;
                continue;
            }
            if (($item->data_registrazione_cc == "")
                && (($item->data_invio_cc != ""))
            ) {
                $arrayDelibere[$item->data]['situazione'][$item->id]['CC_firma'] = 1;
                continue;
            }

            if ($item->data_invio_gu == "") {
                $arrayDelibere[$item->data]['situazione'][$item->id]['GU_inviare'] = 1;
                continue;
            }
            if (($item->data_gu == "")
                && (($item->data_invio_gu != ""))
            ) {
                $arrayDelibere[$item->data]['situazione'][$item->id]['GU_firma'] = 1;
                continue;
            }
        }


        unset($arrayDelibere[strtotime($data) * 1000]['situazione']);


        // riformatto l'array
        $arrayDelibereFormattato = array();
        foreach ($arrayDelibere[strtotime($data) * 1000]['analisi'] as $item => $value ) {
            $arrayDelibereFormattato[] = array (
                "id" => $item,
                "analisi" => $value,
                "statistica" => $arrayDelibere[strtotime($data) * 1000]['statistica'][$item]
            );
        }


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($delibere),
            "data" => $arrayDelibereFormattato,
        );

        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }







    /**
     * @Route("/firmataritipo", name="firmataritipoOptions")
     * @Method("OPTIONS")
     */
    public function firmataritipoOptions(Request $request) {
			
        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/cipeesiti", name="cipeesitiOptions")
     * @Method("OPTIONS")
     */
    public function cipeesitiOptions(Request $request) {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/cipeesititipo", name="cipeesititipoOptions")
     * @Method("OPTIONS")
     */
    public function cipeesititipoOptions(Request $request) {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/cipeargomentitipo", name="cipeargomentitipoOptions")
     * @Method("OPTIONS")
     */
    public function cipeargomentitipoOptions(Request $request) {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


    /**
     * @Route("/monitor/{data}", name="monitorDataOptions")
     * @Method("OPTIONS")
     */
    public function monitorDataOptions(Request $request, $data) {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


    /**
     * @Route("/monitor/group/{anno}", name="monitorAnnoOptions")
     * @Method("OPTIONS")
     */
    public function monitorAnnoOptions(Request $request, $anno) {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }
}
