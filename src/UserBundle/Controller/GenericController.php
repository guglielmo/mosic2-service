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
     * @Security("is_granted('ROLE_READ_FIRMATARITIPO')")
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
     * @Security("is_granted('ROLE_READ_CIPEESITI')")
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
     * @Security("is_granted('ROLE_READ_CIPEESITITIPO')")
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
     * @Security("is_granted('ROLE_READ_CIPEARGOMENTITIPO')")
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
     * @Route("/monitor/group", name="monitorAll")
     * @Method("GET")
     */
    public function monitorAllAction(Request $request) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:Delibere');
        $delibere = $repository->getDelibereByYear("all");

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

                    "nr" => null,
                    "consegna" => null,
                    "cd" => null,
                    "mef" => null,
                    "seg" => null,
                    "pre" => null,
                    "cc" => null,
                    "gu" => null,

                    "arrivo" => null,
                    "cd_invio_giorni" => null,
                    "cd_invio_giorni_tot" => null,
                    "cd_ritorno_giorni" => null,
                    "cd_ritorno_giorni_tot" => null,
                    "mef_invio_giorni" => null,
                    "mef_invio_giorni_tot" => null,
                    "mef_ritorno_giorni" => null,
                    "mef_ritorno_giorni_tot" => null,
                    "seg_invio_giorni" => null,
                    "seg_invio_giorni_tot" => null,
                    "seg_ritorno_giorni" => null,
                    "seg_ritorno_giorni_tot" => null,
                    "pre_invio_giorni" => null,
                    "pre_invio_giorni_tot" => null,
                    "pre_ritorno_giorni" => null,
                    "pre_ritorno_giorni_tot" => null,
                    "cc_invio_giorni" => null,
                    "cc_invio_giorni_tot" => null,
                    "cc_ritorno_giorni" => null,
                    "cc_ritorno_giorni_tot" => null,
                    "gu_invio_giorni" => null,
                    "gu_invio_giorni_tot" => null,
                    "gu_ritorno_giorni" => null,
                    "gu_ritorno_giorni_tot" => null
                );
            }

            //Analisi delle fasi
            $arrayDelibere[$item->data][$item->id]['nr'] = $item->numero;
            if ($item->data_consegna != "") {
                $arrayDelibere[$item->data][$item->id]['consegna'] = ($item->data_consegna - $item->data) / 86400000;
            }
            if ($item->data_direttore_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['cd'] = ($item->data_direttore_ritorno - $item->data_direttore_invio) / 86400000;
            }
            if ($item->data_mef_pec == null || $item->data_mef_pec == "" || $item->data_mef_pec == "0000-00-00") {
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data][$item->id]['mef'] = ($item->data_mef_ritorno - $item->data_mef_invio) / 86400000;
                }
            } else {
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data][$item->id]['mef'] = ($item->data_mef_ritorno - $item->data_mef_pec) / 86400000;
                }
            }
            if ($item->data_segretario_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['seg'] = ($item->data_segretario_ritorno - $item->data_segretario_invio) / 86400000;
            }
            if ($item->data_presidente_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['pre'] = ($item->data_presidente_ritorno - $item->data_presidente_invio) / 86400000;
            }
            if ($item->data_registrazione_cc != "") {
                $arrayDelibere[$item->data][$item->id]['cc'] = ($item->data_registrazione_cc - $item->data_invio_cc) / 86400000;
            }
            if ($item->data_gu != "" && $item->data_invio_gu != 0) {
                $arrayDelibere[$item->data][$item->id]['gu'] = ($item->data_gu - $item->data_invio_gu) / 86400000;
            }


            //statistica
            if ($item->data_consegna != "") {
                $arrayDelibere[$item->data][$item->id]['arrivo'] = ($item->data_consegna - $item->data) / 86400000;
            }
            if ($item->data_direttore_invio != "") {
                $arrayDelibere[$item->data][$item->id]['cd_invio_giorni'] = ($item->data_direttore_invio - $item->data_consegna) / 86400000;
                $arrayDelibere[$item->data][$item->id]['cd_invio_giorni_tot'] = ($item->data_direttore_invio - $item->data) / 86400000;
            }
            if ($item->data_direttore_ritorno != "") {
                if ($item->data_direttore_invio == null) { $item->data_direttore_invio = $item->data_direttore_ritorno; }
                $arrayDelibere[$item->data][$item->id]['cd_ritorno_giorni'] = ($item->data_direttore_ritorno - $item->data_direttore_invio) / 86400000;
                $arrayDelibere[$item->data][$item->id]['cd_ritorno_giorni_tot'] = ($item->data_direttore_ritorno - $item->data) / 86400000;
            }

            if ($item->data_mef_pec == null || $item->data_mef_pec == "" || $item->data_mef_pec == "0000-00-00") {
                if ($item->data_mef_invio != "") {
                    $arrayDelibere[$item->data][$item->id]['mef_invio_giorni'] = ($item->data_mef_invio - $item->data_direttore_ritorno) / 86400000;
                    $arrayDelibere[$item->data][$item->id]['mef_invio_giorni_tot'] = ($item->data_mef_invio - $item->data) / 86400000;
                }
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data][$item->id]['mef_ritorno_giorni'] = ($item->data_mef_ritorno - $item->data_mef_invio) / 86400000;
                    $arrayDelibere[$item->data][$item->id]['mef_ritorno_giorni_tot'] = ($item->data_mef_ritorno - $item->data) / 86400000;
                }
            } else {
                if ($item->data_mef_pec != "") {
                    $arrayDelibere[$item->data][$item->id]['mef_invio_giorni'] = ($item->data_mef_pec - $item->data_direttore_ritorno) / 86400000;
                    $arrayDelibere[$item->data][$item->id]['mef_invio_giorni_tot'] = ($item->data_mef_pec - $item->data) / 86400000;
                }
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data][$item->id]['mef_ritorno_giorni'] = ($item->data_mef_ritorno - $item->data_mef_pec) / 86400000;
                    $arrayDelibere[$item->data][$item->id]['mef_ritorno_giorni_tot'] = ($item->data_mef_ritorno - $item->data) / 86400000;
                }
            }

            if ($item->data_segretario_invio != "" && $item->data_mef_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['seg_invio_giorni'] = ($item->data_segretario_invio - $item->data_mef_ritorno) / 86400000;
                $arrayDelibere[$item->data][$item->id]['seg_invio_giorni_tot'] = ($item->data_segretario_invio - $item->data) / 86400000;
            }
            if ($item->data_segretario_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['seg_ritorno_giorni'] = ($item->data_segretario_ritorno - $item->data_segretario_invio) / 86400000;
                $arrayDelibere[$item->data][$item->id]['seg_ritorno_giorni_tot'] = ($item->data_segretario_ritorno - $item->data) / 86400000;
            }

            if ($item->data_presidente_invio != "" && $item->data_segretario_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['pre_invio_giorni'] = ($item->data_presidente_invio - $item->data_segretario_ritorno) / 86400000;
                $arrayDelibere[$item->data][$item->id]['pre_invio_giorni_tot'] = ($item->data_presidente_invio - $item->data) / 86400000;
            }
            if ($item->data_presidente_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['pre_ritorno_giorni'] = ($item->data_presidente_ritorno - $item->data_presidente_invio) / 86400000;
                $arrayDelibere[$item->data][$item->id]['pre_ritorno_giorni_tot'] = ($item->data_presidente_ritorno - $item->data) / 86400000;
            }

            if ($item->data_invio_cc != "") {
                $arrayDelibere[$item->data][$item->id]['cc_invio_giorni'] = ($item->data_invio_cc - $item->data_presidente_ritorno) / 86400000;
                $arrayDelibere[$item->data][$item->id]['cc_invio_giorni_tot'] = ($item->data_invio_cc - $item->data) / 86400000;
            }
            if ($item->data_registrazione_cc != "") {
                $arrayDelibere[$item->data][$item->id]['cc_ritorno_giorni'] = ($item->data_registrazione_cc - $item->data_invio_cc) / 86400000;
                $arrayDelibere[$item->data][$item->id]['cc_ritorno_giorni_tot'] = ($item->data_registrazione_cc - $item->data) / 86400000;
            }

            if ($item->data_invio_gu != "") {
                if ($item->data_registrazione_cc == null) { $item->data_registrazione_cc = $item->data_invio_gu; }
                $arrayDelibere[$item->data][$item->id]['gu_invio_giorni'] = ($item->data_invio_gu - $item->data_registrazione_cc) / 86400000;
                $arrayDelibere[$item->data][$item->id]['gu_invio_giorni_tot'] = ($item->data_invio_gu - $item->data) / 86400000;
            }
            if ($item->data_gu != "") {
                if ($item->data_invio_gu == null) { $item->data_invio_gu = $item->data_gu; }
                $arrayDelibere[$item->data][$item->id]['gu_ritorno_giorni'] = ($item->data_gu - $item->data_invio_gu) / 86400000;
                $arrayDelibere[$item->data][$item->id]['gu_ritorno_giorni_tot'] = ($item->data_gu - $item->data) / 86400000;
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
            $arrayDelibereGroup[$i]['statistica']['arrivo'] = 0;
            $arrayDelibereGroup[$i]['statistica']['cd_invio_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['cd_invio_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['cd_ritorno_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['cd_ritorno_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['mef_invio_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['mef_invio_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['mef_ritorno_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['mef_ritorno_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['seg_invio_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['seg_invio_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['seg_ritorno_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['seg_ritorno_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['pre_invio_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['pre_invio_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['pre_ritorno_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['pre_ritorno_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['cc_invio_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['cc_invio_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['cc_ritorno_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['cc_ritorno_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['gu_invio_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['gu_invio_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['gu_ritorno_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['gu_ritorno_giorni_tot'] = 0;

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


                if ($arrayDelibere[$i][$k]['consegna'] != null) {
                    $arrayDelibereGroup[$i]['analisi']['consegna'] = $arrayDelibereGroup[$i]['analisi']['consegna'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['consegna_media'] = $arrayDelibereGroup[$i]['analisi']['consegna_media'] + $arrayDelibere[$i][$k]['consegna'];
                }

                if ($arrayDelibere[$i][$k]['cd'] != null || $arrayDelibere[$i][$k]['cd'] == 0) {
                    $arrayDelibereGroup[$i]['analisi']['cd'] = $arrayDelibereGroup[$i]['analisi']['cd'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['cd_media'] = $arrayDelibereGroup[$i]['analisi']['cd_media'] + $arrayDelibere[$i][$k]['cd'];
                }

                if ($arrayDelibere[$i][$k]['mef'] != null) {
                    $arrayDelibereGroup[$i]['analisi']['mef'] = $arrayDelibereGroup[$i]['analisi']['mef'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['mef_media'] = $arrayDelibereGroup[$i]['analisi']['mef_media'] + $arrayDelibere[$i][$k]['mef'];
                }

                if ($arrayDelibere[$i][$k]['seg'] != null) {
                    $arrayDelibereGroup[$i]['analisi']['seg'] = $arrayDelibereGroup[$i]['analisi']['seg'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['seg_media'] = $arrayDelibereGroup[$i]['analisi']['seg_media'] + $arrayDelibere[$i][$k]['seg'];
                }

                if ($arrayDelibere[$i][$k]['pre'] != null) {
                    $arrayDelibereGroup[$i]['analisi']['pre'] = $arrayDelibereGroup[$i]['analisi']['pre'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['pre_media'] = $arrayDelibereGroup[$i]['analisi']['pre_media'] + $arrayDelibere[$i][$k]['pre'];
                }

                if ($arrayDelibere[$i][$k]['cc'] != null) {
                    $arrayDelibereGroup[$i]['analisi']['cc'] = $arrayDelibereGroup[$i]['analisi']['cc'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['cc_media'] = $arrayDelibereGroup[$i]['analisi']['cc_media'] + $arrayDelibere[$i][$k]['cc'];
                }

                if ($arrayDelibere[$i][$k]['gu'] != null ) {
                    $arrayDelibereGroup[$i]['analisi']['gu'] = $arrayDelibereGroup[$i]['analisi']['gu'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['gu_media'] = $arrayDelibereGroup[$i]['analisi']['gu_media'] + $arrayDelibere[$i][$k]['gu'];
                }



                $arrayDelibereGroup[$i]['statistica']['count'] = $arrayDelibereGroup[$i]['statistica']['count'] + 1;

                $arrayDelibereGroup[$i]['statistica']['arrivo'] = $arrayDelibereGroup[$i]['statistica']['arrivo'] + $arrayDelibere[$i][$k]['arrivo'];

                $arrayDelibereGroup[$i]['statistica']['cd_invio_giorni'] = $arrayDelibereGroup[$i]['statistica']['cd_invio_giorni'] + $arrayDelibere[$i][$k]['cd_invio_giorni'];

                $arrayDelibereGroup[$i]['statistica']['cd_invio_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['cd_invio_giorni_tot'] + $arrayDelibere[$i][$k]['cd_invio_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['cd_ritorno_giorni'] = $arrayDelibereGroup[$i]['statistica']['cd_ritorno_giorni'] + $arrayDelibere[$i][$k]['cd_ritorno_giorni'];

                $arrayDelibereGroup[$i]['statistica']['cd_ritorno_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['cd_ritorno_giorni_tot'] + $arrayDelibere[$i][$k]['cd_ritorno_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['mef_invio_giorni'] = $arrayDelibereGroup[$i]['statistica']['mef_invio_giorni'] + $arrayDelibere[$i][$k]['mef_invio_giorni'];

                $arrayDelibereGroup[$i]['statistica']['mef_invio_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['mef_invio_giorni_tot'] + $arrayDelibere[$i][$k]['mef_invio_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['mef_ritorno_giorni'] = $arrayDelibereGroup[$i]['statistica']['mef_ritorno_giorni'] + $arrayDelibere[$i][$k]['mef_ritorno_giorni'];

                $arrayDelibereGroup[$i]['statistica']['mef_ritorno_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['mef_ritorno_giorni_tot'] + $arrayDelibere[$i][$k]['mef_ritorno_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['seg_invio_giorni'] = $arrayDelibereGroup[$i]['statistica']['seg_invio_giorni'] + $arrayDelibere[$i][$k]['seg_invio_giorni'];

                $arrayDelibereGroup[$i]['statistica']['seg_invio_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['seg_invio_giorni_tot'] + $arrayDelibere[$i][$k]['seg_invio_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['seg_ritorno_giorni'] = $arrayDelibereGroup[$i]['statistica']['seg_ritorno_giorni'] + $arrayDelibere[$i][$k]['seg_ritorno_giorni'];

                $arrayDelibereGroup[$i]['statistica']['seg_ritorno_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['seg_ritorno_giorni_tot'] + $arrayDelibere[$i][$k]['seg_ritorno_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['pre_invio_giorni'] = $arrayDelibereGroup[$i]['statistica']['pre_invio_giorni'] + $arrayDelibere[$i][$k]['pre_invio_giorni'];

                $arrayDelibereGroup[$i]['statistica']['pre_invio_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['pre_invio_giorni_tot'] + $arrayDelibere[$i][$k]['pre_invio_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['pre_ritorno_giorni'] = $arrayDelibereGroup[$i]['statistica']['pre_ritorno_giorni'] + $arrayDelibere[$i][$k]['pre_ritorno_giorni'];

                $arrayDelibereGroup[$i]['statistica']['pre_ritorno_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['pre_ritorno_giorni_tot'] + $arrayDelibere[$i][$k]['pre_ritorno_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['cc_invio_giorni'] = $arrayDelibereGroup[$i]['statistica']['cc_invio_giorni'] + $arrayDelibere[$i][$k]['cc_invio_giorni'];

                $arrayDelibereGroup[$i]['statistica']['cc_invio_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['cc_invio_giorni_tot'] + $arrayDelibere[$i][$k]['cc_invio_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['cc_ritorno_giorni'] = $arrayDelibereGroup[$i]['statistica']['cc_ritorno_giorni'] + $arrayDelibere[$i][$k]['cc_ritorno_giorni'];

                $arrayDelibereGroup[$i]['statistica']['cc_ritorno_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['cc_ritorno_giorni_tot'] + $arrayDelibere[$i][$k]['cc_ritorno_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['gu_invio_giorni'] = $arrayDelibereGroup[$i]['statistica']['gu_invio_giorni'] + $arrayDelibere[$i][$k]['gu_invio_giorni'];

                $arrayDelibereGroup[$i]['statistica']['gu_invio_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['gu_invio_giorni_tot'] + $arrayDelibere[$i][$k]['gu_invio_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['gu_ritorno_giorni'] = $arrayDelibereGroup[$i]['statistica']['gu_ritorno_giorni'] + $arrayDelibere[$i][$k]['gu_ritorno_giorni'];

                $arrayDelibereGroup[$i]['statistica']['gu_ritorno_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['gu_ritorno_giorni_tot'] + $arrayDelibere[$i][$k]['gu_ritorno_giorni_tot'];


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
                $arrayDelibereGroup[$i]['statistica']['arrivo'] = round($arrayDelibereGroup[$i]['statistica']['arrivo'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['cd_invio_giorni'] = round($arrayDelibereGroup[$i]['statistica']['cd_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['cd_invio_giorni'] = round($arrayDelibereGroup[$i]['statistica']['cd_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['cd_invio_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['cd_invio_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['cd_ritorno_giorni'] = round($arrayDelibereGroup[$i]['statistica']['cd_ritorno_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['cd_ritorno_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['cd_ritorno_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['mef_invio_giorni'] = round($arrayDelibereGroup[$i]['statistica']['mef_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['mef_invio_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['mef_invio_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['mef_ritorno_giorni'] = round($arrayDelibereGroup[$i]['statistica']['mef_ritorno_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['mef_ritorno_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['mef_ritorno_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['seg_invio_giorni'] = $this->negativeToZero(round($arrayDelibereGroup[$i]['statistica']['seg_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']));
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['seg_invio_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['seg_invio_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['seg_ritorno_giorni'] = round($arrayDelibereGroup[$i]['statistica']['seg_ritorno_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['seg_ritorno_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['seg_ritorno_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['pre_invio_giorni'] = $this->negativeToZero(round($arrayDelibereGroup[$i]['statistica']['pre_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']));
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['pre_invio_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['pre_invio_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['pre_ritorno_giorni'] = round($arrayDelibereGroup[$i]['statistica']['pre_ritorno_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['pre_ritorno_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['pre_ritorno_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['cc_invio_giorni'] = round($arrayDelibereGroup[$i]['statistica']['cc_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['cc_invio_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['cc_invio_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['cc_ritorno_giorni'] = round($arrayDelibereGroup[$i]['statistica']['cc_ritorno_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['cc_ritorno_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['cc_ritorno_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['gu_invio_giorni'] = round($arrayDelibereGroup[$i]['statistica']['gu_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['gu_invio_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['gu_invio_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['gu_ritorno_giorni'] = round($arrayDelibereGroup[$i]['statistica']['gu_ritorno_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['gu_ritorno_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['gu_ritorno_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
        }


        // riformatto l'array
        $arrayDelibereGroupFormattato = array();
        foreach ($arrayDelibereGroup as $kk => $vv) {
            $idAnno = date("Y",$kk / 1000);

            $arrayDelibereGroupFormattato[$idAnno][] = array (
                "id" => $kk,
                "situazione" => $arrayDelibereGroup[$kk]['situazione'],
                "statistica" => $arrayDelibereGroup[$kk]['statistica'],
                "analisi" => $arrayDelibereGroup[$kk]['analisi']
            );
        }
        $arrayDelibereGroupFormattato2 = array();
        foreach ($arrayDelibereGroupFormattato as $xx => $zz) {
            $arrayDelibereGroupFormattato2[] = array(
                "id" => $xx,
                "group" => $arrayDelibereGroupFormattato[$xx]
            );
        }



        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($delibere),
            "data" => $arrayDelibereGroupFormattato2,
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

                    "nr" => null,
                    "consegna" => null,
                    "cd" => null,
                    "mef" => null,
                    "seg" => null,
                    "pre" => null,
                    "cc" => null,
                    "gu" => null,

                    "arrivo" => null,
                    "cd_invio_giorni" => null,
                    "cd_invio_giorni_tot" => null,
                    "cd_ritorno_giorni" => null,
                    "cd_ritorno_giorni_tot" => null,
                    "mef_invio_giorni" => null,
                    "mef_invio_giorni_tot" => null,
                    "mef_ritorno_giorni" => null,
                    "mef_ritorno_giorni_tot" => null,
                    "seg_invio_giorni" => null,
                    "seg_invio_giorni_tot" => null,
                    "seg_ritorno_giorni" => null,
                    "seg_ritorno_giorni_tot" => null,
                    "pre_invio_giorni" => null,
                    "pre_invio_giorni_tot" => null,
                    "pre_ritorno_giorni" => null,
                    "pre_ritorno_giorni_tot" => null,
                    "cc_invio_giorni" => null,
                    "cc_invio_giorni_tot" => null,
                    "cc_ritorno_giorni" => null,
                    "cc_ritorno_giorni_tot" => null,
                    "gu_invio_giorni" => null,
                    "gu_invio_giorni_tot" => null,
                    "gu_ritorno_giorni" => null,
                    "gu_ritorno_giorni_tot" => null
                );
            }

            //Analisi delle fasi
            $arrayDelibere[$item->data][$item->id]['nr'] = $item->numero;
            if ($item->data_consegna != "") {
                $arrayDelibere[$item->data][$item->id]['consegna'] = ($item->data_consegna - $item->data) / 86400000;
            }
            if ($item->data_direttore_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['cd'] = ($item->data_direttore_ritorno - $item->data_direttore_invio) / 86400000;
            }
            if ($item->data_mef_pec == null || $item->data_mef_pec == "" || $item->data_mef_pec == "0000-00-00") {
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data][$item->id]['mef'] = ($item->data_mef_ritorno - $item->data_mef_invio) / 86400000;
                }
            } else {
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data][$item->id]['mef'] = ($item->data_mef_ritorno - $item->data_mef_pec) / 86400000;
                }
            }
            if ($item->data_segretario_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['seg'] = ($item->data_segretario_ritorno - $item->data_segretario_invio) / 86400000;
            }
            if ($item->data_presidente_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['pre'] = ($item->data_presidente_ritorno - $item->data_presidente_invio) / 86400000;
            }
            if ($item->data_registrazione_cc != "") {
                $arrayDelibere[$item->data][$item->id]['cc'] = ($item->data_registrazione_cc - $item->data_invio_cc) / 86400000;
            }
            if ($item->data_gu != "" && $item->data_invio_gu != 0) {
                $arrayDelibere[$item->data][$item->id]['gu'] = ($item->data_gu - $item->data_invio_gu) / 86400000;
            }


            //statistica
            if ($item->data_consegna != "") {
                $arrayDelibere[$item->data][$item->id]['arrivo'] = ($item->data_consegna - $item->data) / 86400000;
            }
            if ($item->data_direttore_invio != "") {
                $arrayDelibere[$item->data][$item->id]['cd_invio_giorni'] = ($item->data_direttore_invio - $item->data_consegna) / 86400000;
                $arrayDelibere[$item->data][$item->id]['cd_invio_giorni_tot'] = ($item->data_direttore_invio - $item->data) / 86400000;
            }
            if ($item->data_direttore_ritorno != "") {
                if ($item->data_direttore_invio == null) { $item->data_direttore_invio = $item->data_direttore_ritorno; }
                $arrayDelibere[$item->data][$item->id]['cd_ritorno_giorni'] = ($item->data_direttore_ritorno - $item->data_direttore_invio) / 86400000;
                $arrayDelibere[$item->data][$item->id]['cd_ritorno_giorni_tot'] = ($item->data_direttore_ritorno - $item->data) / 86400000;
            }

            if ($item->data_mef_pec == null || $item->data_mef_pec == "" || $item->data_mef_pec == "0000-00-00") {
                if ($item->data_mef_invio != "") {
                    $arrayDelibere[$item->data][$item->id]['mef_invio_giorni'] = ($item->data_mef_invio - $item->data_direttore_ritorno) / 86400000;
                    $arrayDelibere[$item->data][$item->id]['mef_invio_giorni_tot'] = ($item->data_mef_invio - $item->data) / 86400000;
                }
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data][$item->id]['mef_ritorno_giorni'] = ($item->data_mef_ritorno - $item->data_mef_invio) / 86400000;
                    $arrayDelibere[$item->data][$item->id]['mef_ritorno_giorni_tot'] = ($item->data_mef_ritorno - $item->data) / 86400000;
                }
            } else {
                if ($item->data_mef_pec != "") {
                    $arrayDelibere[$item->data][$item->id]['mef_invio_giorni'] = ($item->data_mef_pec - $item->data_direttore_ritorno) / 86400000;
                    $arrayDelibere[$item->data][$item->id]['mef_invio_giorni_tot'] = ($item->data_mef_pec - $item->data) / 86400000;
                }
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data][$item->id]['mef_ritorno_giorni'] = ($item->data_mef_ritorno - $item->data_mef_pec) / 86400000;
                    $arrayDelibere[$item->data][$item->id]['mef_ritorno_giorni_tot'] = ($item->data_mef_ritorno - $item->data) / 86400000;
                }
            }

            if ($item->data_segretario_invio != "" && $item->data_mef_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['seg_invio_giorni'] = ($item->data_segretario_invio - $item->data_mef_ritorno) / 86400000;
                $arrayDelibere[$item->data][$item->id]['seg_invio_giorni_tot'] = ($item->data_segretario_invio - $item->data) / 86400000;
            }
            if ($item->data_segretario_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['seg_ritorno_giorni'] = ($item->data_segretario_ritorno - $item->data_segretario_invio) / 86400000;
                $arrayDelibere[$item->data][$item->id]['seg_ritorno_giorni_tot'] = ($item->data_segretario_ritorno - $item->data) / 86400000;
            }

            if ($item->data_presidente_invio != "" && $item->data_segretario_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['pre_invio_giorni'] = ($item->data_presidente_invio - $item->data_segretario_ritorno) / 86400000;
                $arrayDelibere[$item->data][$item->id]['pre_invio_giorni_tot'] = ($item->data_presidente_invio - $item->data) / 86400000;
            }
            if ($item->data_presidente_ritorno != "") {
                $arrayDelibere[$item->data][$item->id]['pre_ritorno_giorni'] = ($item->data_presidente_ritorno - $item->data_presidente_invio) / 86400000;
                $arrayDelibere[$item->data][$item->id]['pre_ritorno_giorni_tot'] = ($item->data_presidente_ritorno - $item->data) / 86400000;
            }

            if ($item->data_invio_cc != "") {
                $arrayDelibere[$item->data][$item->id]['cc_invio_giorni'] = ($item->data_invio_cc - $item->data_presidente_ritorno) / 86400000;
                $arrayDelibere[$item->data][$item->id]['cc_invio_giorni_tot'] = ($item->data_invio_cc - $item->data) / 86400000;
            }
            if ($item->data_registrazione_cc != "") {
                $arrayDelibere[$item->data][$item->id]['cc_ritorno_giorni'] = ($item->data_registrazione_cc - $item->data_invio_cc) / 86400000;
                $arrayDelibere[$item->data][$item->id]['cc_ritorno_giorni_tot'] = ($item->data_registrazione_cc - $item->data) / 86400000;
            }

            if ($item->data_invio_gu != "") {
                if ($item->data_registrazione_cc == null) { $item->data_registrazione_cc = $item->data_invio_gu; }
                $arrayDelibere[$item->data][$item->id]['gu_invio_giorni'] = ($item->data_invio_gu - $item->data_registrazione_cc) / 86400000;
                $arrayDelibere[$item->data][$item->id]['gu_invio_giorni_tot'] = ($item->data_invio_gu - $item->data) / 86400000;
            }
            if ($item->data_gu != "") {
                if ($item->data_invio_gu == null) { $item->data_invio_gu = $item->data_gu; }
                $arrayDelibere[$item->data][$item->id]['gu_ritorno_giorni'] = ($item->data_gu - $item->data_invio_gu) / 86400000;
                $arrayDelibere[$item->data][$item->id]['gu_ritorno_giorni_tot'] = ($item->data_gu - $item->data) / 86400000;
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
            $arrayDelibereGroup[$i]['statistica']['arrivo'] = 0;
            $arrayDelibereGroup[$i]['statistica']['cd_invio_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['cd_invio_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['cd_ritorno_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['cd_ritorno_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['mef_invio_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['mef_invio_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['mef_ritorno_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['mef_ritorno_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['seg_invio_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['seg_invio_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['seg_ritorno_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['seg_ritorno_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['pre_invio_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['pre_invio_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['pre_ritorno_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['pre_ritorno_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['cc_invio_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['cc_invio_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['cc_ritorno_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['cc_ritorno_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['gu_invio_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['gu_invio_giorni_tot'] = 0;
            $arrayDelibereGroup[$i]['statistica']['gu_ritorno_giorni'] = 0;
            $arrayDelibereGroup[$i]['statistica']['gu_ritorno_giorni_tot'] = 0;

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


                if ($arrayDelibere[$i][$k]['consegna'] != null) {
                    $arrayDelibereGroup[$i]['analisi']['consegna'] = $arrayDelibereGroup[$i]['analisi']['consegna'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['consegna_media'] = $arrayDelibereGroup[$i]['analisi']['consegna_media'] + $arrayDelibere[$i][$k]['consegna'];
                }

                if ($arrayDelibere[$i][$k]['cd'] != null || $arrayDelibere[$i][$k]['cd'] == 0) {
                    $arrayDelibereGroup[$i]['analisi']['cd'] = $arrayDelibereGroup[$i]['analisi']['cd'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['cd_media'] = $arrayDelibereGroup[$i]['analisi']['cd_media'] + $arrayDelibere[$i][$k]['cd'];
                }

                if ($arrayDelibere[$i][$k]['mef'] != null) {
                    $arrayDelibereGroup[$i]['analisi']['mef'] = $arrayDelibereGroup[$i]['analisi']['mef'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['mef_media'] = $arrayDelibereGroup[$i]['analisi']['mef_media'] + $arrayDelibere[$i][$k]['mef'];
                }

                if ($arrayDelibere[$i][$k]['seg'] != null) {
                    $arrayDelibereGroup[$i]['analisi']['seg'] = $arrayDelibereGroup[$i]['analisi']['seg'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['seg_media'] = $arrayDelibereGroup[$i]['analisi']['seg_media'] + $arrayDelibere[$i][$k]['seg'];
                }

                if ($arrayDelibere[$i][$k]['pre'] != null) {
                    $arrayDelibereGroup[$i]['analisi']['pre'] = $arrayDelibereGroup[$i]['analisi']['pre'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['pre_media'] = $arrayDelibereGroup[$i]['analisi']['pre_media'] + $arrayDelibere[$i][$k]['pre'];
                }

                if ($arrayDelibere[$i][$k]['cc'] != null) {
                    $arrayDelibereGroup[$i]['analisi']['cc'] = $arrayDelibereGroup[$i]['analisi']['cc'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['cc_media'] = $arrayDelibereGroup[$i]['analisi']['cc_media'] + $arrayDelibere[$i][$k]['cc'];
                }

                if ($arrayDelibere[$i][$k]['gu'] != null ) {
                    $arrayDelibereGroup[$i]['analisi']['gu'] = $arrayDelibereGroup[$i]['analisi']['gu'] + 1;
                    $arrayDelibereGroup[$i]['analisi']['gu_media'] = $arrayDelibereGroup[$i]['analisi']['gu_media'] + $arrayDelibere[$i][$k]['gu'];
                }



                $arrayDelibereGroup[$i]['statistica']['count'] = $arrayDelibereGroup[$i]['statistica']['count'] + 1;

                $arrayDelibereGroup[$i]['statistica']['arrivo'] = $arrayDelibereGroup[$i]['statistica']['arrivo'] + $arrayDelibere[$i][$k]['arrivo'];

                $arrayDelibereGroup[$i]['statistica']['cd_invio_giorni'] = $arrayDelibereGroup[$i]['statistica']['cd_invio_giorni'] + $arrayDelibere[$i][$k]['cd_invio_giorni'];
                
                $arrayDelibereGroup[$i]['statistica']['cd_invio_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['cd_invio_giorni_tot'] + $arrayDelibere[$i][$k]['cd_invio_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['cd_ritorno_giorni'] = $arrayDelibereGroup[$i]['statistica']['cd_ritorno_giorni'] + $arrayDelibere[$i][$k]['cd_ritorno_giorni'];

                $arrayDelibereGroup[$i]['statistica']['cd_ritorno_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['cd_ritorno_giorni_tot'] + $arrayDelibere[$i][$k]['cd_ritorno_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['mef_invio_giorni'] = $arrayDelibereGroup[$i]['statistica']['mef_invio_giorni'] + $arrayDelibere[$i][$k]['mef_invio_giorni'];

                $arrayDelibereGroup[$i]['statistica']['mef_invio_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['mef_invio_giorni_tot'] + $arrayDelibere[$i][$k]['mef_invio_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['mef_ritorno_giorni'] = $arrayDelibereGroup[$i]['statistica']['mef_ritorno_giorni'] + $arrayDelibere[$i][$k]['mef_ritorno_giorni'];

                $arrayDelibereGroup[$i]['statistica']['mef_ritorno_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['mef_ritorno_giorni_tot'] + $arrayDelibere[$i][$k]['mef_ritorno_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['seg_invio_giorni'] = $arrayDelibereGroup[$i]['statistica']['seg_invio_giorni'] + $arrayDelibere[$i][$k]['seg_invio_giorni'];

                $arrayDelibereGroup[$i]['statistica']['seg_invio_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['seg_invio_giorni_tot'] + $arrayDelibere[$i][$k]['seg_invio_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['seg_ritorno_giorni'] = $arrayDelibereGroup[$i]['statistica']['seg_ritorno_giorni'] + $arrayDelibere[$i][$k]['seg_ritorno_giorni'];

                $arrayDelibereGroup[$i]['statistica']['seg_ritorno_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['seg_ritorno_giorni_tot'] + $arrayDelibere[$i][$k]['seg_ritorno_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['pre_invio_giorni'] = $arrayDelibereGroup[$i]['statistica']['pre_invio_giorni'] + $arrayDelibere[$i][$k]['pre_invio_giorni'];

                $arrayDelibereGroup[$i]['statistica']['pre_invio_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['pre_invio_giorni_tot'] + $arrayDelibere[$i][$k]['pre_invio_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['pre_ritorno_giorni'] = $arrayDelibereGroup[$i]['statistica']['pre_ritorno_giorni'] + $arrayDelibere[$i][$k]['pre_ritorno_giorni'];

                $arrayDelibereGroup[$i]['statistica']['pre_ritorno_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['pre_ritorno_giorni_tot'] + $arrayDelibere[$i][$k]['pre_ritorno_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['cc_invio_giorni'] = $arrayDelibereGroup[$i]['statistica']['cc_invio_giorni'] + $arrayDelibere[$i][$k]['cc_invio_giorni'];

                $arrayDelibereGroup[$i]['statistica']['cc_invio_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['cc_invio_giorni_tot'] + $arrayDelibere[$i][$k]['cc_invio_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['cc_ritorno_giorni'] = $arrayDelibereGroup[$i]['statistica']['cc_ritorno_giorni'] + $arrayDelibere[$i][$k]['cc_ritorno_giorni'];

                $arrayDelibereGroup[$i]['statistica']['cc_ritorno_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['cc_ritorno_giorni_tot'] + $arrayDelibere[$i][$k]['cc_ritorno_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['gu_invio_giorni'] = $arrayDelibereGroup[$i]['statistica']['gu_invio_giorni'] + $arrayDelibere[$i][$k]['gu_invio_giorni'];

                $arrayDelibereGroup[$i]['statistica']['gu_invio_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['gu_invio_giorni_tot'] + $arrayDelibere[$i][$k]['gu_invio_giorni_tot'];

                $arrayDelibereGroup[$i]['statistica']['gu_ritorno_giorni'] = $arrayDelibereGroup[$i]['statistica']['gu_ritorno_giorni'] + $arrayDelibere[$i][$k]['gu_ritorno_giorni'];

                $arrayDelibereGroup[$i]['statistica']['gu_ritorno_giorni_tot'] = $arrayDelibereGroup[$i]['statistica']['gu_ritorno_giorni_tot'] + $arrayDelibere[$i][$k]['gu_ritorno_giorni_tot'];


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
                $arrayDelibereGroup[$i]['statistica']['arrivo'] = round($arrayDelibereGroup[$i]['statistica']['arrivo'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['cd_invio_giorni'] = round($arrayDelibereGroup[$i]['statistica']['cd_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['cd_invio_giorni'] = round($arrayDelibereGroup[$i]['statistica']['cd_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['cd_invio_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['cd_invio_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['cd_ritorno_giorni'] = round($arrayDelibereGroup[$i]['statistica']['cd_ritorno_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['cd_ritorno_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['cd_ritorno_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['mef_invio_giorni'] = round($arrayDelibereGroup[$i]['statistica']['mef_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['mef_invio_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['mef_invio_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['mef_ritorno_giorni'] = round($arrayDelibereGroup[$i]['statistica']['mef_ritorno_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['mef_ritorno_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['mef_ritorno_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['seg_invio_giorni'] = $this->negativeToZero(round($arrayDelibereGroup[$i]['statistica']['seg_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']));
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['seg_invio_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['seg_invio_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['seg_ritorno_giorni'] = round($arrayDelibereGroup[$i]['statistica']['seg_ritorno_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['seg_ritorno_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['seg_ritorno_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['pre_invio_giorni'] = $this->negativeToZero(round($arrayDelibereGroup[$i]['statistica']['pre_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']));
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['pre_invio_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['pre_invio_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['pre_ritorno_giorni'] = round($arrayDelibereGroup[$i]['statistica']['pre_ritorno_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['pre_ritorno_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['pre_ritorno_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['cc_invio_giorni'] = round($arrayDelibereGroup[$i]['statistica']['cc_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['cc_invio_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['cc_invio_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['cc_ritorno_giorni'] = round($arrayDelibereGroup[$i]['statistica']['cc_ritorno_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['cc_ritorno_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['cc_ritorno_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['gu_invio_giorni'] = round($arrayDelibereGroup[$i]['statistica']['gu_invio_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['gu_invio_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['gu_invio_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['gu_ritorno_giorni'] = round($arrayDelibereGroup[$i]['statistica']['gu_ritorno_giorni'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
            if ($arrayDelibereGroup[$i]['statistica']['count'] != 0) {
                $arrayDelibereGroup[$i]['statistica']['gu_ritorno_giorni_tot'] = round($arrayDelibereGroup[$i]['statistica']['gu_ritorno_giorni_tot'] / $arrayDelibereGroup[$i]['statistica']['count']);
            }
        }


        // riformatto l'array
        $arrayDelibereGroupFormattato = array();
        foreach ($arrayDelibereGroup as $kk => $vv) {
            $idAnno = date("Y",$kk / 1000);

            $arrayDelibereGroupFormattato[$idAnno][] = array (
                "id" => $kk,
                "situazione" => $arrayDelibereGroup[$kk]['situazione'],
                "statistica" => $arrayDelibereGroup[$kk]['statistica'],
                "analisi" => $arrayDelibereGroup[$kk]['analisi']
            );
        }
        $arrayDelibereGroupFormattato2 = array();
        foreach ($arrayDelibereGroupFormattato as $xx => $zz) {
            $arrayDelibereGroupFormattato2[] = array(
                "id" => $xx,
                "group" => $arrayDelibereGroupFormattato[$xx]
            );
        }


        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($delibere),
            "data" => $arrayDelibereGroupFormattato2,
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
                    "data" => null,
                    "nr" => null,
                    "consegna" => null,
                    "cd" => null,
                    "mef" => null,
                    "seg" => null,
                    "pre" => null,
                    "cc" => null,
                    "gu" => null,
                );
                $arrayDelibere[$item->data]['statistica'][$item->id] = array(
                    "arrivo" => null,
                    "cd_invio_giorni" => null,
                    "cd_invio_giorni_tot" => null,
                    "cd_ritorno_giorni" => null,
                    "cd_ritorno_giorni_tot" => null,
                    "mef_invio_giorni" => null,
                    "mef_invio_giorni_tot" => null,
                    "mef_ritorno_giorni" => null,
                    "mef_ritorno_giorni_tot" => null,
                    "seg_invio_giorni" => null,
                    "seg_invio_giorni_tot" => null,
                    "seg_ritorno_giorni" => null,
                    "seg_ritorno_giorni_tot" => null,
                    "pre_invio_giorni" => null,
                    "pre_invio_giorni_tot" => null,
                    "pre_ritorno_giorni" => null,
                    "pre_ritorno_giorni_tot" => null,
                    "cc_invio_giorni" => null,
                    "cc_invio_giorni_tot" => null,
                    "cc_ritorno_giorni" => null,
                    "cc_ritorno_giorni_tot" => null,
                    "gu_invio_giorni" => null,
                    "gu_invio_giorni_tot" => null,
                    "gu_ritorno_giorni" => null,
                    "gu_ritorno_giorni_tot" => null
                );
            }

            //Analisi delle fasi
            $arrayDelibere[$item->data]['analisi'][$item->id]['data'] = $item->data;
            $arrayDelibere[$item->data]['analisi'][$item->id]['nr'] = $item->numero;
            if ($item->data_consegna != "") {
                $arrayDelibere[$item->data]['analisi'][$item->id]['consegna'] = ($item->data_consegna - $item->data) / 86400000;
            }
            if ($item->data_direttore_ritorno != "") {
                $arrayDelibere[$item->data]['analisi'][$item->id]['cd'] = ($item->data_direttore_ritorno - $item->data_direttore_invio) / 86400000;
            }
            if ($item->data_mef_pec == null || $item->data_mef_pec == "" || $item->data_mef_pec == "0000-00-00") {
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data]['analisi'][$item->id]['mef'] = ($item->data_mef_ritorno - $item->data_mef_invio) / 86400000;
                }
            } else {
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data]['analisi'][$item->id]['mef'] = ($item->data_mef_ritorno - $item->data_mef_pec) / 86400000;
                }
            }
            if ($item->data_segretario_ritorno != "") {
                $arrayDelibere[$item->data]['analisi'][$item->id]['seg'] = ($item->data_segretario_ritorno - $item->data_segretario_invio) / 86400000;
            }
            if ($item->data_presidente_ritorno != "") {
                $arrayDelibere[$item->data]['analisi'][$item->id]['pre'] = ($item->data_presidente_ritorno - $item->data_presidente_invio) / 86400000;
            }
            if ($item->data_registrazione_cc != "") {
                $arrayDelibere[$item->data]['analisi'][$item->id]['cc'] = ($item->data_registrazione_cc - $item->data_invio_cc) / 86400000;
            }
            if ($item->data_gu != "") {
                $arrayDelibere[$item->data]['analisi'][$item->id]['gu'] = ($item->data_gu - $item->data_invio_gu) / 86400000;
            }

            
            //statistica
            $arrayDelibere[$item->data]['statistica'][$item->id]['nr'] = $item->numero;
            if ($item->data_consegna != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['arrivo'] = $this->negativeToZero(round(($item->data_consegna - $item->data) / 86400000));
            }
            if ($item->data_direttore_invio != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['cd_invio_giorni'] = $this->negativeToZero(round(($item->data_direttore_invio - $item->data_consegna) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['cd_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_direttore_invio - $item->data) / 86400000));
            }
            if ($item->data_direttore_ritorno != "") {
                if ($item->data_direttore_invio == null) { $item->data_direttore_invio = $item->data_direttore_ritorno; }
                $arrayDelibere[$item->data]['statistica'][$item->id]['cd_ritorno_giorni'] = $this->negativeToZero(round(($item->data_direttore_ritorno - $item->data_direttore_invio) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['cd_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_direttore_ritorno - $item->data) / 86400000));
            }

            if ($item->data_mef_pec == null || $item->data_mef_pec == "" || $item->data_mef_pec == "0000-00-00") {
                if ($item->data_mef_invio != "") {
                    $arrayDelibere[$item->data]['statistica'][$item->id]['mef_invio_giorni'] = $this->negativeToZero(round(($item->data_mef_invio - $item->data_direttore_ritorno) / 86400000));
                    $arrayDelibere[$item->data]['statistica'][$item->id]['mef_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_mef_invio - $item->data) / 86400000));
                }
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data]['statistica'][$item->id]['mef_ritorno_giorni'] = $this->negativeToZero(round(($item->data_mef_ritorno - $item->data_mef_invio) / 86400000));
                    $arrayDelibere[$item->data]['statistica'][$item->id]['mef_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_mef_ritorno - $item->data) / 86400000));
                }
            } else {
                if ($item->data_mef_pec != "") {
                    $arrayDelibere[$item->data]['statistica'][$item->id]['mef_invio_giorni'] = $this->negativeToZero(round(($item->data_mef_pec - $item->data_direttore_ritorno) / 86400000));
                    $arrayDelibere[$item->data]['statistica'][$item->id]['mef_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_mef_pec - $item->data) / 86400000));
                }
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data]['statistica'][$item->id]['mef_ritorno_giorni'] = $this->negativeToZero(round(($item->data_mef_ritorno - $item->data_mef_pec) / 86400000));
                    $arrayDelibere[$item->data]['statistica'][$item->id]['mef_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_mef_ritorno - $item->data) / 86400000));
                }
            }

            if ($item->data_segretario_invio != "" && $item->data_mef_ritorno != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['seg_invio_giorni'] = $this->negativeToZero(($item->data_segretario_invio - $item->data_mef_ritorno) / 86400000);
                $arrayDelibere[$item->data]['statistica'][$item->id]['seg_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_segretario_invio - $item->data) / 86400000));
            }
            if ($item->data_segretario_ritorno != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['seg_ritorno_giorni'] = $this->negativeToZero(round(($item->data_segretario_ritorno - $item->data_segretario_invio) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['seg_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_segretario_ritorno - $item->data) / 86400000));
            }

            if ($item->data_presidente_invio != "" && $item->data_segretario_ritorno != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['pre_invio_giorni'] = $this->negativeToZero(round(($item->data_presidente_invio - $item->data_segretario_ritorno) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['pre_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_presidente_invio - $item->data) / 86400000));
            }
            if ($item->data_presidente_ritorno != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['pre_ritorno_giorni'] = $this->negativeToZero(round(($item->data_presidente_ritorno - $item->data_presidente_invio) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['pre_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_presidente_ritorno - $item->data) / 86400000));
            }

            if ($item->data_invio_cc != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['cc_invio_giorni'] = $this->negativeToZero(round(($item->data_invio_cc - $item->data_presidente_ritorno) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['cc_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_invio_cc - $item->data) / 86400000));
            }
            if ($item->data_registrazione_cc != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['cc_ritorno_giorni'] = $this->negativeToZero(round(($item->data_registrazione_cc - $item->data_invio_cc) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['cc_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_registrazione_cc - $item->data) / 86400000));
            }

            if ($item->data_invio_gu != "") {
                if ($item->data_registrazione_cc == null) { $item->data_registrazione_cc = $item->data_invio_gu; }
                $arrayDelibere[$item->data]['statistica'][$item->id]['gu_invio_giorni'] = $this->negativeToZero(round(($item->data_invio_gu - $item->data_registrazione_cc) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['gu_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_invio_gu - $item->data) / 86400000));
            }
            if ($item->data_gu != "") {
                if ($item->data_invio_gu == null) { $item->data_invio_gu = $item->data_gu; }
                $arrayDelibere[$item->data]['statistica'][$item->id]['gu_ritorno_giorni'] = $this->negativeToZero(round(($item->data_gu - $item->data_invio_gu) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['gu_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_gu - $item->data) / 86400000));
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
     * @Route("/monitor", name="monitorDataAll")
     * @Method("GET")
     */
    public function monitorDataAllAction(Request $request) {
        $repository = $this->getDoctrine()->getRepository('UserBundle:Delibere');
        $delibere = $repository->getDelibereByData("all");

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
                    "data" => null,
                    "nr" => null,
                    "consegna" => null,
                    "cd" => null,
                    "mef" => null,
                    "seg" => null,
                    "pre" => null,
                    "cc" => null,
                    "gu" => null,
                );
                $arrayDelibere[$item->data]['statistica'][$item->id] = array(
                    "arrivo" => null,
                    "cd_invio_giorni" => null,
                    "cd_invio_giorni_tot" => null,
                    "cd_ritorno_giorni" => null,
                    "cd_ritorno_giorni_tot" => null,
                    "mef_invio_giorni" => null,
                    "mef_invio_giorni_tot" => null,
                    "mef_ritorno_giorni" => null,
                    "mef_ritorno_giorni_tot" => null,
                    "seg_invio_giorni" => null,
                    "seg_invio_giorni_tot" => null,
                    "seg_ritorno_giorni" => null,
                    "seg_ritorno_giorni_tot" => null,
                    "pre_invio_giorni" => null,
                    "pre_invio_giorni_tot" => null,
                    "pre_ritorno_giorni" => null,
                    "pre_ritorno_giorni_tot" => null,
                    "cc_invio_giorni" => null,
                    "cc_invio_giorni_tot" => null,
                    "cc_ritorno_giorni" => null,
                    "cc_ritorno_giorni_tot" => null,
                    "gu_invio_giorni" => null,
                    "gu_invio_giorni_tot" => null,
                    "gu_ritorno_giorni" => null,
                    "gu_ritorno_giorni_tot" => null
                );
            }

            //Analisi delle fasi
            $arrayDelibere[$item->data]['analisi'][$item->id]['data'] = $item->data;
            $arrayDelibere[$item->data]['analisi'][$item->id]['nr'] = $item->numero;
            if ($item->data_consegna != "") {
                $arrayDelibere[$item->data]['analisi'][$item->id]['consegna'] = ($item->data_consegna - $item->data) / 86400000;
            }
            if ($item->data_direttore_ritorno != "") {
                $arrayDelibere[$item->data]['analisi'][$item->id]['cd'] = ($item->data_direttore_ritorno - $item->data_direttore_invio) / 86400000;
            }
            if ($item->data_mef_pec == null || $item->data_mef_pec == "" || $item->data_mef_pec == "0000-00-00") {
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data]['analisi'][$item->id]['mef'] = ($item->data_mef_ritorno - $item->data_mef_invio) / 86400000;
                }
            } else {
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data]['analisi'][$item->id]['mef'] = ($item->data_mef_ritorno - $item->data_mef_pec) / 86400000;
                }
            }
            if ($item->data_segretario_ritorno != "") {
                $arrayDelibere[$item->data]['analisi'][$item->id]['seg'] = ($item->data_segretario_ritorno - $item->data_segretario_invio) / 86400000;
            }
            if ($item->data_presidente_ritorno != "") {
                $arrayDelibere[$item->data]['analisi'][$item->id]['pre'] = ($item->data_presidente_ritorno - $item->data_presidente_invio) / 86400000;
            }
            if ($item->data_registrazione_cc != "") {
                $arrayDelibere[$item->data]['analisi'][$item->id]['cc'] = ($item->data_registrazione_cc - $item->data_invio_cc) / 86400000;
            }
            if ($item->data_gu != "") {
                $arrayDelibere[$item->data]['analisi'][$item->id]['gu'] = ($item->data_gu - $item->data_invio_gu) / 86400000;
            }


            //statistica
            $arrayDelibere[$item->data]['statistica'][$item->id]['nr'] = $item->numero;
            if ($item->data_consegna != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['arrivo'] = $this->negativeToZero(round(($item->data_consegna - $item->data) / 86400000));
            }
            if ($item->data_direttore_invio != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['cd_invio_giorni'] = $this->negativeToZero(round(($item->data_direttore_invio - $item->data_consegna) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['cd_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_direttore_invio - $item->data) / 86400000));
            }
            if ($item->data_direttore_ritorno != "") {
                if ($item->data_direttore_invio == null) { $item->data_direttore_invio = $item->data_direttore_ritorno; }
                $arrayDelibere[$item->data]['statistica'][$item->id]['cd_ritorno_giorni'] = $this->negativeToZero(round(($item->data_direttore_ritorno - $item->data_direttore_invio) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['cd_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_direttore_ritorno - $item->data) / 86400000));
            }

            if ($item->data_mef_pec == null || $item->data_mef_pec == "" || $item->data_mef_pec == "0000-00-00") {
                if ($item->data_mef_invio != "") {
                    $arrayDelibere[$item->data]['statistica'][$item->id]['mef_invio_giorni'] = $this->negativeToZero(round(($item->data_mef_invio - $item->data_direttore_ritorno) / 86400000));
                    $arrayDelibere[$item->data]['statistica'][$item->id]['mef_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_mef_invio - $item->data) / 86400000));
                }
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data]['statistica'][$item->id]['mef_ritorno_giorni'] = $this->negativeToZero(round(($item->data_mef_ritorno - $item->data_mef_invio) / 86400000));
                    $arrayDelibere[$item->data]['statistica'][$item->id]['mef_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_mef_ritorno - $item->data) / 86400000));
                }
            } else {
                if ($item->data_mef_pec != "") {
                    $arrayDelibere[$item->data]['statistica'][$item->id]['mef_invio_giorni'] = $this->negativeToZero(round(($item->data_mef_pec - $item->data_direttore_ritorno) / 86400000));
                    $arrayDelibere[$item->data]['statistica'][$item->id]['mef_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_mef_pec - $item->data) / 86400000));
                }
                if ($item->data_mef_ritorno != "") {
                    $arrayDelibere[$item->data]['statistica'][$item->id]['mef_ritorno_giorni'] = $this->negativeToZero(round(($item->data_mef_ritorno - $item->data_mef_pec) / 86400000));
                    $arrayDelibere[$item->data]['statistica'][$item->id]['mef_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_mef_ritorno - $item->data) / 86400000));
                }
            }

            if ($item->data_segretario_invio != "" && $item->data_mef_ritorno != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['seg_invio_giorni'] = $this->negativeToZero(($item->data_segretario_invio - $item->data_mef_ritorno) / 86400000);
                $arrayDelibere[$item->data]['statistica'][$item->id]['seg_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_segretario_invio - $item->data) / 86400000));
            }
            if ($item->data_segretario_ritorno != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['seg_ritorno_giorni'] = $this->negativeToZero(round(($item->data_segretario_ritorno - $item->data_segretario_invio) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['seg_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_segretario_ritorno - $item->data) / 86400000));
            }

            if ($item->data_presidente_invio != "" && $item->data_segretario_ritorno != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['pre_invio_giorni'] = $this->negativeToZero(round(($item->data_presidente_invio - $item->data_segretario_ritorno) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['pre_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_presidente_invio - $item->data) / 86400000));
            }
            if ($item->data_presidente_ritorno != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['pre_ritorno_giorni'] = $this->negativeToZero(round(($item->data_presidente_ritorno - $item->data_presidente_invio) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['pre_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_presidente_ritorno - $item->data) / 86400000));
            }

            if ($item->data_invio_cc != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['cc_invio_giorni'] = $this->negativeToZero(round(($item->data_invio_cc - $item->data_presidente_ritorno) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['cc_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_invio_cc - $item->data) / 86400000));
            }
            if ($item->data_registrazione_cc != "") {
                $arrayDelibere[$item->data]['statistica'][$item->id]['cc_ritorno_giorni'] = $this->negativeToZero(round(($item->data_registrazione_cc - $item->data_invio_cc) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['cc_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_registrazione_cc - $item->data) / 86400000));
            }

            if ($item->data_invio_gu != "") {
                if ($item->data_registrazione_cc == null) { $item->data_registrazione_cc = $item->data_invio_gu; }
                $arrayDelibere[$item->data]['statistica'][$item->id]['gu_invio_giorni'] = $this->negativeToZero(round(($item->data_invio_gu - $item->data_registrazione_cc) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['gu_invio_giorni_tot'] = $this->negativeToZero(round(($item->data_invio_gu - $item->data) / 86400000));
            }
            if ($item->data_gu != "") {
                if ($item->data_invio_gu == null) { $item->data_invio_gu = $item->data_gu; }
                $arrayDelibere[$item->data]['statistica'][$item->id]['gu_ritorno_giorni'] = $this->negativeToZero(round(($item->data_gu - $item->data_invio_gu) / 86400000));
                $arrayDelibere[$item->data]['statistica'][$item->id]['gu_ritorno_giorni_tot'] = $this->negativeToZero(round(($item->data_gu - $item->data) / 86400000));
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


        //unset($arrayDelibere[strtotime($data) * 1000]['situazione']);


        // riformatto l'array
        $arrayDelibereFormattato = array();

        foreach ($arrayDelibere as $item => $value ) {
            $dataCipe = 0;
            foreach ($value['analisi'] as $aa => $bb) {
                $dataCipe = ($bb['data']);
                $value['analisi'][$aa]['id'] = $aa;
                //break;
            }
            foreach ($value['statistica'] as $cc => $dd) {
                $value['statistica'][$cc]['id'] = $cc;
                //break;
            }


            $arrayDelibereFormattato[$dataCipe][] = array (
                "id" => $item,
                "analisi" => array_values($value['analisi']),
                "statistica" => array_values($value['statistica'])
            );
        }

        $arrayDelibereFormattato2 = array();
        foreach ($arrayDelibereFormattato as $xx => $zz) {
            $arrayDelibereFormattato2[] = $arrayDelibereFormattato[$xx][0];
        }





        $response_array = array(
            "response" => Response::HTTP_OK,
            "total_results" => count($delibere),
            "data" => $arrayDelibereFormattato2,
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
     * @Route("/monitor", name="monitorDataAllOptions")
     * @Method("OPTIONS")
     */
    public function monitorDataAllOptions(Request $request) {

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
     * @Route("/monitor/group", name="monitorAllOptions")
     * @Method("OPTIONS")
     */
    public function monitorAllOptions(Request $request) {

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
