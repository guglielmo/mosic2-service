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
use UserBundle\Entity\RelAllegatiDelibereCCR;
use UserBundle\Entity\RelFirmatariDelibere;
use UserBundle\Entity\RelDelibereFascicoli;
use UserBundle\Entity\RelTagsDelibere;
use UserBundle\Entity\RelUfficiDelibere;


class DelibereController extends Controller
{
    use \UserBundle\Helper\ControllerHelper;


	    /**
     * @SWG\Tag(
     *   name="Delibere",
     *   description="Tutte le Api delle delibere"
     * )
     */


    /**
     * @SWG\Get(
     *     path="/api/delibere",
     *     summary="Lista delibere",
     *     tags={"Delibere"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":5110,"limit":"99999","offset":0,"data":{{"id":2584,"data":1494367200000
,"id_stato":null,"numero":99,"argomento":"test1234","finanziamento":0,"note":{"cd":null,"mef":null,"seg"
:null,"pre":null,"cc":null,"pa":null,"gu":null,"an":"aabb","ns":"sss"},"foglio_cc":null,"numero_cc":null
,"registro_cc":null,"id_uffici":{1},"id_tags":{11,15,16},"anno":"2017","situazione":0,"giorni_iter":
{"dipe":6,"mef":0,"firme":0,"cc":0,"gu":10},"oss_cc":{{"id":534,"tipo_documento":1,"data_max_risposta"
:1497823200000,"data_risposta":1495922400000}},"registrazione_cc":{"data":1494367200000,"foglio":null
,"numero":null},"firme":{"uff_a":1494367200000,"cd_i":1493589600000,"cd_r":1494885600000,"mef_i":1494367200000
,"mef_r":1494367200000,"seg_i":1494367200000,"seg_r":1494367200000,"pre_i":1494367200000,"pre_r":1494367200000
,"cc_i":1494367200000,"cc_r":1494367200000,"gu_i":1494885600000,"gu_r":1495749600000},"pub_gu":{"nr"
:null,"data":1495749600000}}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     */

    /**
     * @Route("/delibere", name="delibere")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_DELIBERE')")
     */
    public function delibereAction(Request $request) {

        //prendo i parametri get
        $limit  = ($request->query->get('limit') != "") ? $request->query->get('limit') : 200;
        $offset = ($request->query->get('offset') != "") ? $request->query->get('offset') : 0;

        $sortBy  = ($request->query->get('sort_by') != "") ? $request->query->get('sort_by') : 'numero';
        $sortType = ($request->query->get('sort_order') != "") ? $request->query->get('sort_order') : "ASC";

        $argomento = ($request->query->get('argomento') != "") ? $request->query->get('argomento') : '';

        $repository = $this->getDoctrine()->getRepository('UserBundle:Delibere');



$mscListaDelibere = microtime(true);
        $delibere = $repository->listaDelibere($limit, $offset, $sortBy, $sortType, $argomento);
$mscListaDelibere = microtime(true) - $mscListaDelibere;
//echo "<br><br> listaDelibere (cioè la query): ". $mscListaDelibere . ' seconds';


$mscSerialize = microtime(true);

        //converte i risultati in json
        //$serialize = $this->serialize($delibere);
        $serialize = json_encode($delibere, JSON_NUMERIC_CHECK);
$mscSerialize = microtime(true) - $mscSerialize;
//echo "<br><br> Serialize (cioè converte i dati in json): ". $mscSerialize . ' seconds';


$mscFormatDate = microtime(true);

        //crea gli array di id separati con la virgola Es [1,2,3]
        $serialize = $this->mergeRelDelibereAll($serialize);
        //formatta le date
//        $serialize = $this->formatDateJsonArrayCustom2($serialize, array('data', 'data_consegna', 'data_segretario_ritorno', 'data_segretario_invio',
//                                                            'data_presidente_ritorno', 'data_presidente_invio', 'data_registrazione_cc',
//                                                            'data_invio_cc', 'data_invio_gu', 'data_gu','data_direttore_invio','data_direttore_ritorno',
//                                                            'data_mef_invio','data_mef_ritorno'));

        $serialize = $this->setCastDelibere($serialize, "all");

$mscFormatDate = microtime(true) - $mscFormatDate;
//echo "<br><br> Formatta le date e altro..". $mscFormatDate . ' seconds';


        $em = $this->getDoctrine()->getManager();
        $giorni = array();

$mscForSerialize = microtime(true);

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
                "data" => ($serialize[$item]["data_registrazione_cc"] == 0)? null : $serialize[$item]["data_registrazione_cc"],
                "foglio" => $serialize[$item]["foglio_cc"],
                "numero" => $serialize[$item]["registro_cc"]
            );
            $serialize[$item]["firme"] = array(
                "uff_a" => ($serialize[$item]["data_consegna"] == 0)? null : $serialize[$item]["data_consegna"],
                "cd_i" => ($serialize[$item]["data_direttore_invio"] == 0)? null : $serialize[$item]["data_direttore_invio"],
                "cd_r" => ($serialize[$item]["data_direttore_ritorno"] == 0)? null : $serialize[$item]["data_direttore_ritorno"],
                "mef_i" => ($serialize[$item]["data_mef_pec"] == 0)? null : $serialize[$item]["data_mef_pec"],
                "mef_r" => ($serialize[$item]["data_mef_ritorno"] == 0)? null : $serialize[$item]["data_mef_ritorno"],
                "seg_i" => ($serialize[$item]["data_segretario_invio"] == 0)? null : $serialize[$item]["data_segretario_invio"],
                "seg_r" => ($serialize[$item]["data_segretario_ritorno"] == 0)? null : $serialize[$item]["data_segretario_ritorno"],
                "pre_i" => ($serialize[$item]["data_presidente_invio"] == 0)? null : $serialize[$item]["data_presidente_invio"],
                "pre_r" => ($serialize[$item]["data_presidente_ritorno"] == 0)? null : $serialize[$item]["data_presidente_ritorno"],
                "cc_i" => ($serialize[$item]["data_invio_cc"] == 0)? null : $serialize[$item]["data_invio_cc"],
                "cc_r" => ($serialize[$item]["data_registrazione_cc"] == 0)? null : $serialize[$item]["data_registrazione_cc"],
                "gu_i" => ($serialize[$item]["data_invio_gu"] == 0)? null : $serialize[$item]["data_invio_gu"],
                "gu_r" => ($serialize[$item]["data_gu"] == 0)? null : $serialize[$item]["data_gu"]
            );
            unset($serialize[$item]['data_consegna']);
            unset($serialize[$item]['data_direttore_invio']);
            unset($serialize[$item]['data_direttore_ritorno']);
            unset($serialize[$item]['data_mef_pec']);
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
                "data" => ($serialize[$item]["data_gu"] == 0)? null : $serialize[$item]["data_gu"],
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


$mscForSerialize = microtime(true) - $mscForSerialize;
//echo "<br><br> Ciclo che esegue operazioni per aggiungere dati al json: ". $mscForSerialize . ' seconds';

//echo "<br><br> TOTALE: ". ($mscListaDelibere + $mscSerialize + $mscFormatDate + $mscForSerialize) . " seconds";

//exit;


        $response = new Response(json_encode($response_array), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


	/**
     * @SWG\Get(
     *     path="/api/delibere/{id}",
     *     summary="Singola delibera",
     *     tags={"Delibere"},
     *     operationId="idDelibera",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id della delibera",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo",
     *       examples={
     *       "application/json": {"response":200,"total_results":5110,"limit":"99999","offset":0,"data":{{"id":2584,"data":1494367200000
,"id_stato":null,"numero":99,"argomento":"test1234","finanziamento":0,"note":{"cd":null,"mef":null,"seg"
:null,"pre":null,"cc":null,"pa":null,"gu":null,"an":"aabb","ns":"sss"},"foglio_cc":null,"numero_cc":null
,"registro_cc":null,"id_uffici":{1},"id_tags":{11,15,16},"anno":"2017","situazione":0,"giorni_iter":
{"dipe":6,"mef":0,"firme":0,"cc":0,"gu":10},"oss_cc":{{"id":534,"tipo_documento":1,"data_max_risposta"
:1497823200000,"data_risposta":1495922400000}},"registrazione_cc":{"data":1494367200000,"foglio":null
,"numero":null},"firme":{"uff_a":1494367200000,"cd_i":1493589600000,"cd_r":1494885600000,"mef_i":1494367200000
,"mef_r":1494367200000,"seg_i":1494367200000,"seg_r":1494367200000,"pre_i":1494367200000,"pre_r":1494367200000
,"cc_i":1494367200000,"cc_r":1494367200000,"gu_i":1494885600000,"gu_r":1495749600000},"pub_gu":{"nr"
:null,"data":1495749600000}}}}
     *       }
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))

     * )
     */    
    
    /**
     * @Route("/delibere/{id}", name="delibere_item")
     * @Method("GET")
     * @Security("is_granted('ROLE_READ_DELIBERE')")
     */
    public function delibereItemAction(Request $request, $id) {

        $repository = $this->getDoctrine()->getRepository('UserBundle:Delibere');
        $delibere = $repository->schedaDelibera($id);

        $repositoryCC = $this->getDoctrine()->getRepository('UserBundle:DelibereCC');
        $delibereCC = $repositoryCC->findBy(array("idDelibere" => $id));

        //converte i risultati in json
        $serialize = $this->serialize($delibere);
        //$serialize = json_encode($delibere, JSON_NUMERIC_CHECK);


        $delibereCC = $this->formatDateJsonArrayCustom(json_decode($this->serialize($delibereCC)),array("data_rilievo","data_risposta") );

        foreach ($delibereCC as $i => $v) {
            $allegatiCC = $repository->getAllegatiCCRByIdCCR($v->id);
            $v->allegati = $allegatiCC;


            //$response = new Response(json_encode($allegatiCC), Response::HTTP_OK);
            //return $this->setBaseHeaders($response);


        }


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
            if ($value->numero_rilievo == "") {$value->numero_rilievo = null;} else {$value->numero_rilievo = (int) $value->numero_rilievo;}
            if ($value->numero_risposta == "") {$value->numero_risposta = null;} else {$value->numero_risposta = (int) $value->numero_risposta;}
            if ($value->giorni_rilievo == "") {$value->giorni_rilievo = null;} else {$value->giorni_rilievo = (int) $value->giorni_rilievo;}
        }


        $repositoryOdgCipeDelibere = $this->getDoctrine()->getRepository('UserBundle:Delibere');
        $odgDelibere = $repositoryOdgCipeDelibere->cercaDataDelibera($serialize[0]['data'], $serialize[0]['numero_delibera']);
        $odgDelibere = json_decode($this->serialize($odgDelibere));


        $ArrayodgDelibere = array();
        $ArrayRegistri = array();
        foreach ($odgDelibere as $i => $v) {
            $ArrayRegistri[] = (int) $v->id_registri;
            $ArrayodgDelibere[$v->id] = array(
                "id_cipe" => (int) $v->id_cipe,
                "id_cipe_odg" => (int) $v->id,
                "ordine" => $v->ordine,
                "id_fascicoli" => (int) $v->id_fascicoli,
                "id_registri" => $ArrayRegistri
            );
        }


        //$response = new Response(json_encode(array_values($ArrayodgDelibere)), Response::HTTP_OK);
        //return $this->setBaseHeaders($response);



        $serialize[0]['rilievi_CC'] = $delibereCC;

        $serialize[0]['id_segretariato'] = explode(",",$serialize[0]['id_segretariato']);

        $serialize[0]['cipe_delibere'] = array_values($ArrayodgDelibere);


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
     * @SWG\Put(
     *     path="/api/delibere/{id}",
     *     summary="Salvataggio delibera",
     *     tags={"Delibere"},
     *     operationId="idDelibera",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="query",
     *         description="id della delibera",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
	 *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Richiesta",
     *         required=true,
 	 *         @SWG\Schema(
	 *				type="array",
     *              @SWG\Items(
     *                 type="object",
     *                 	@SWG\Property(property="id", type="integer"),
	 *                 	@SWG\Property(property="data", type="string"),
	 *					@SWG\Property(property="numero", type="integer"),
	 *					@SWG\Property(property="id_stato", type="integer"),
	 *					@SWG\Property(property="argomento", type="string"),
	 *					@SWG\Property(property="note", type="string"),
	 *					@SWG\Property(property="note_servizio", type="string"),
	 *					@SWG\Property(property="scheda", type="integer"),
	 *					@SWG\Property(property="finanziamento", type="integer"),
	 *					@SWG\Property(property="data_consegna", type="string"),
	 *					@SWG\Property(property="data_direttore_invio", type="string"),
	 *					@SWG\Property(property="data_direttore_ritorno", type="string"),
	 *					@SWG\Property(property="note_direttore", type="string"),
	 *					@SWG\Property(property="invio_mef", type="integer"),
	 *					@SWG\Property(property="data_mef_invio", type="string"),
	 *					@SWG\Property(property="data_mef_pec", type="string"),
	 *					@SWG\Property(property="data_mef_ritorno", type="string"),
	 *					@SWG\Property(property="note_mef", type="string"),
	 *					@SWG\Property(property="id_segretario", type="integer"),
	 *					@SWG\Property(property="data_segretario_invio", type="string"),
	 *					@SWG\Property(property="data_segretario_ritorno", type="string"),
	 *					@SWG\Property(property="note_segretario", type="string"),
	 *					@SWG\Property(property="id_presidente", type="integer"),
	 *					@SWG\Property(property="data_presidente_invio", type="string"),
	 *					@SWG\Property(property="data_presidente_ritorno", type="string"),
	 *					@SWG\Property(property="note_presidente", type="string"),
	 *					@SWG\Property(property="data_invio_cc", type="string"),
	 *					@SWG\Property(property="numero_cc", type="string"),
	 *					@SWG\Property(property="data_registrazione_cc", type="string"),
	 *					@SWG\Property(property="id_registro_cc", type="integer"),
	 *					@SWG\Property(property="foglio_cc", type="integer"),
	 *					@SWG\Property(property="tipo_registrazione_cc", type="integer"),
	 *					@SWG\Property(property="note_cc", type="string"),
	 *					@SWG\Property(property="data_invio_p", type="string"),
	 *					@SWG\Property(property="note_p", type="string"),
	 *					@SWG\Property(property="data_invio_gu", type="string"),
	 *					@SWG\Property(property="numero_invio_gu", type="string"),
	 *					@SWG\Property(property="tipo_gu", type="integer"),
	 *					@SWG\Property(property="data_gu", type="string"),
	 *					@SWG\Property(property="numero_gu", type="integer"),
	 *					@SWG\Property(property="data_ec_gu", type="string"),
	 *					@SWG\Property(property="numero_ec_gu", type="integer"),
	 *					@SWG\Property(property="data_co_gu", type="string"),
	 *					@SWG\Property(property="numero_co_gu", type="integer"),
	 *					@SWG\Property(property="pubblicazione_gu", type="integer"),
	 *					@SWG\Property(property="note_gu", type="string"),
	 *					@SWG\Property(property="id_uffici", type="array"),
	 *					@SWG\Property(property="id_segretariato", type="array"),
	 *					@SWG\Property(property="id_tags", type="array"),
	 *					@SWG\Property(property="allegati_MEF", type="string"),
	 *					@SWG\Property(property="allegati_CC", type="string"),
	 *					@SWG\Property(property="allegati_GU", type="string"),
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
	 *					@SWG\Property(property="allegati_ALL", type="string"),
	 *					@SWG\Property(property="rilievi_CC",
	 *						type="array",
	 *						@SWG\Items(
	 *							@SWG\Property(property="id", type="integer"),
	 *							@SWG\Property(property="id_delibere", type="integer"),
	 *							@SWG\Property(property="tipo_documento", type=""),
	 *							@SWG\Property(property="data_rilievo", type="string"),
	 *							@SWG\Property(property="numero_rilievo", type="integer"),
	 *							@SWG\Property(property="data_risposta", type="string"),
	 *							@SWG\Property(property="numero_risposta", type="integer"),
	 *							@SWG\Property(property="giorni_rilievo", type="string"),
	 *							@SWG\Property(property="tipo_rilievo", type="string"),
	 *							@SWG\Property(property="note_rilievo", type="string")
	 *						)	 
	 *					),
	 *					@SWG\Property(property="giorni_iter", type="string")
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
     * @Route("/delibere/{id}", name="delibere_item_save")
     * @Method("PUT")
     * @Security("is_granted('ROLE_EDIT_DELIBERE')")
     */
    public function delibereItemSaveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()),["numero","argomento","data","id_uffici"]);
        if ($check != "ok") {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il campo ".$check." e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

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

        $repository_relDelibereFascicoli = $em->getRepository('UserBundle:RelDelibereFascicoli');
        $relDelibereFascicoli_delete = $repository_relDelibereFascicoli->findByIdDelibere($data->id);


        //$array_id_firmatari = explode(",", $data->id_segretariato); //$data->id_segretariato è già un array
        //$array_id_uffici = explode(",", $data->id_uffici);
        $array_id_tags = explode(",", $data->id_tags);


        $delibere->setNumero($data->numero);
        if ($data->data != null){ $delibere->setData(new \DateTime($this->zulu_to_rome($data->data))); } else {$delibere->setData(null); }
        $delibere->setIdStato($data->id_stato);
        $delibere->setArgomento($data->argomento);
        $delibere->setFinanziamento($data->finanziamento);
        $delibere->setNote($data->note);
        $delibere->setNoteServizio($data->note_servizio);
        $delibere->setScheda($data->scheda);
        if ($data->data_consegna != null){ $delibere->setDataConsegna(new \DateTime($this->zulu_to_rome($data->data_consegna)));} else {$delibere->setDataConsegna(null); }
        $delibere->setIdDirettore($data->id_direttore);
        if ($data->data_direttore_invio   != null){ $delibere->setDataDirettoreInvio(new \DateTime($this->zulu_to_rome($data->data_direttore_invio)));} else {$delibere->setDataDirettoreInvio(null); }
        if ($data->data_direttore_ritorno != null){ $delibere->setDataDirettoreRitorno(new \DateTime($this->zulu_to_rome($data->data_direttore_ritorno)));} else {$delibere->setDataDirettoreRitorno(null); }
        $delibere->setNoteDirettore($data->note_direttore);
        $delibere->setInvioMef($data->invio_mef);
        if ($data->data_mef_invio != null){ $delibere->setDataMefInvio(new \DateTime($this->zulu_to_rome($data->data_mef_invio)));} else {$delibere->setDataMefInvio(null); }
        if ($data->data_mef_pec != null){ $delibere->setDataMefPec(new \DateTime($this->zulu_to_rome($data->data_mef_pec))); } else {$delibere->setDataMefPec(null); }
        if ($data->data_mef_ritorno != null){ $delibere->setDataMefRitorno(new \DateTime($this->zulu_to_rome($data->data_mef_ritorno)));} else {$delibere->setDataMefRitorno(null); }
        $delibere->setIdSegretario($data->id_segretario);
        if ($data->data_segretario_invio != null){ $delibere->setDataSegretarioInvio(new \DateTime($this->zulu_to_rome($data->data_segretario_invio)));} else {$delibere->setDataSegretarioInvio(null); }
        if ($data->data_segretario_ritorno != null){ $delibere->setDataSegretarioRitorno(new \DateTime($this->zulu_to_rome($data->data_segretario_ritorno)));} else {$delibere->setDataSegretarioRitorno(null); }
        $delibere->setNoteSegretario($data->note_segretario);
        $delibere->setIdPresidente($data->id_presidente);
        if ($data->data_presidente_invio != null){ $delibere->setDataPresidenteInvio(new \DateTime($this->zulu_to_rome($data->data_presidente_invio)));} else {$delibere->setDataPresidenteInvio(null); }
        if ($data->data_presidente_ritorno != null){ $delibere->setDataPresidenteRitorno(new \DateTime($this->zulu_to_rome($data->data_presidente_ritorno)));} else {$delibere->setDataPresidenteRitorno(null); }
        $delibere->setNotePresidente($data->note_presidente);
        if ($data->data_invio_cc != null){ $delibere->setDataInvioCC(new \DateTime($this->zulu_to_rome($data->data_invio_cc)));} else {$delibere->setDataInvioCC(null); }
        $delibere->setNumeroCC($data->numero_cc);
        if ($data->data_registrazione_cc != null){ $delibere->setDataRegistrazioneCC(new \DateTime($this->zulu_to_rome($data->data_registrazione_cc)));} else {$delibere->setDataRegistrazioneCC(null); }
        $delibere->setIdRegistroCC($data->id_registro_cc);
        $delibere->setFoglioCC($data->foglio_cc);
        $delibere->setTipoRegistrazioneCC($data->tipo_registrazione_cc);
        $delibere->setNoteCC($data->note_cc);
        if ($data->data_invio_p != null){ $delibere->setDataInvioP(new \DateTime($this->zulu_to_rome($data->data_invio_p)));} else {$delibere->setDataInvioP(null); }
        if ($data->data_invio_gu != null){ $delibere->setDataInvioGU(new \DateTime($this->zulu_to_rome($data->data_invio_gu)));} else {$delibere->setDataInvioGU(null); }
        $delibere->setNumeroInvioGU($data->numero_invio_gu);
        $delibere->setTipoGU($data->tipo_gu);
        if ($data->data_gu != null){ $delibere->setDataGU(new \DateTime($this->zulu_to_rome($data->data_gu)));} else {$delibere->setDataGU(null); }
        $delibere->setNumeroGU($data->numero_gu);
        if ($data->data_ec_gu != null){ $delibere->setDataEcGU(new \DateTime($this->zulu_to_rome($data->data_ec_gu)));} else {$delibere->setDataEcGU(null); }
        $delibere->setNumeroEcGU($data->numero_ec_gu);
        if ($data->data_co_gu != null){ $delibere->setDataCoGU(new \DateTime($this->zulu_to_rome($data->data_co_gu)));} else {$delibere->setDataCoGU(null); }
        $delibere->setNumeroCoGU($data->numero_co_gu);
        $delibere->setPubblicazioneGU($data->pubblicazione_gu);
        $delibere->setNoteGU($data->note_gu);

        $delibere->setNoteMef($data->note_mef);
        $delibere->setNoteFirma($data->note_firma);
        $delibere->setNoteP($data->note_p);


        $delibere->setSituazione($this->setFaseProceduraleDelibere($data));


        $em->persist($delibere); //create
        $em->flush(); //esegue l'update


//        // FASCICOLI
//        foreach ($relDelibereFascicoli_delete as $relDelibereFascicoli_delete) {
//            $em->remove($relDelibereFascicoli_delete);
//        }
//        foreach ($data->id_fascicoli as $item) {
//            $relDelibereFascicoli = new RelDelibereFascicoli();
//            $relDelibereFascicoli->setIdDelibere($data->id);
//            $relDelibereFascicoli->setIdFascicoli((int)$item);
//
//            $em->persist($relDelibereFascicoli); //create
//        }
        
        
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
//        foreach ($DelibereCC_delete as $DelibereCC_delete) {
//            $em->remove($DelibereCC_delete);
//        }
        foreach ($data->rilievi_CC as $item) {


            if($item->id == null) {
                $relDelibereCC = new DelibereCC();
                $relDelibereCC->setIdDelibere($id);

                if ($item->tipo_documento != null) {
                    $relDelibereCC->setTipoDocumento($item->tipo_documento);
                } else {
                    $relDelibereCC->setTipoDocumento(0);
                }
                if ($item->data_rilievo != null) {
                    $relDelibereCC->setDataRilievo(new \DateTime($this->zulu_to_rome($item->data_rilievo)));
                } else {
                    $relDelibereCC->setDataRilievo(null);
                }
                if (isset($item->numero_rilievo)) {
                    $relDelibereCC->setNumeroRilievo($item->numero_rilievo);
                } else {
                    $relDelibereCC->setNumeroRilievo('');
                }
                if ($item->data_risposta != null) {
                    $relDelibereCC->setDataRisposta(new \DateTime($this->zulu_to_rome($item->data_risposta)));
                } else {
                    $relDelibereCC->setDataRisposta(new \DateTime("0000-00-00"));
                }
                if ($item->data_rilievo != null) {
                    $relDelibereCC->setDataRilievo(new \DateTime($this->zulu_to_rome($item->data_rilievo)));
                } else {
                    $relDelibereCC->setDataRilievo(new \DateTime("0000-00-00"));
                }
                if (isset($item->numero_risposta)) {
                    $relDelibereCC->setNumeroRisposta($item->numero_risposta);
                } else {
                    $relDelibereCC->setNumeroRisposta('');
                }
                if (isset($item->giorni_rilievo) && $item->giorni_rilievo != null) {
                    $relDelibereCC->setGiorniRilievo($item->giorni_rilievo);
                } else {
                    $relDelibereCC->setGiorniRilievo(0);
                }
                if (isset($item->note_rilievo)) {
                    $relDelibereCC->setNoteRilievo($item->note_rilievo);
                } else {
                    $relDelibereCC->setNoteRilievo("");
                }

                $em->persist($relDelibereCC); //create
            } else {
                $repository_DelibereRilieviCC = $em->getRepository('UserBundle:DelibereCC');
                $DelibereRilieviCC = $repository_DelibereRilieviCC->findOneBy(array("id" => $item->id));


                if ($item->tipo_documento != null) {
                    $DelibereRilieviCC->setTipoDocumento($item->tipo_documento);
                } else {
                    $DelibereRilieviCC->setTipoDocumento(0);
                }

                if ($item->data_rilievo != null) {
                    $DelibereRilieviCC->setDataRilievo(new \DateTime($this->zulu_to_rome($item->data_rilievo)));
                } else {
                    $DelibereRilieviCC->setDataRilievo(null);
                }
                if (isset($item->numero_rilievo)) {
                    $DelibereRilieviCC->setNumeroRilievo($item->numero_rilievo);
                } else {
                    $DelibereRilieviCC->setNumeroRilievo('');
                }
                if ($item->data_risposta != null) {
                    $DelibereRilieviCC->setDataRisposta(new \DateTime($this->zulu_to_rome($item->data_risposta)));
                } else {
                    $DelibereRilieviCC->setDataRisposta(new \DateTime("0000-00-00"));
                }
                if ($item->data_rilievo != null) {
                    $DelibereRilieviCC->setDataRilievo(new \DateTime($this->zulu_to_rome($item->data_rilievo)));
                } else {
                    $DelibereRilieviCC->setDataRilievo(new \DateTime("0000-00-00"));
                }
                if (isset($item->numero_risposta)) {
                    $DelibereRilieviCC->setNumeroRisposta($item->numero_risposta);
                } else {
                    $DelibereRilieviCC->setNumeroRisposta('');
                }
                if (isset($item->giorni_rilievo) && $item->giorni_rilievo != null) {
                    $DelibereRilieviCC->setGiorniRilievo($item->giorni_rilievo);
                } else {
                    $DelibereRilieviCC->setGiorniRilievo(0);
                }
                if (isset($item->note_rilievo)) {
                    $DelibereRilieviCC->setNoteRilievo($item->note_rilievo);
                } else {
                    $DelibereRilieviCC->setNoteRilievo("");
                }


                $em->persist($DelibereRilieviCC); //create
            }

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
		
		$repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("monitor");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente
		
		$repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("monitor_group");
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
        $delibereGiorni->setGiorniMef($this->differenceDate($data->data_mef_pec,$data->data_mef_ritorno));
        $delibereGiorni->setGiorniSegretario($this->differenceDate($data->data_segretario_invio,$data->data_segretario_ritorno));
        $delibereGiorni->setGiorniPresidente($this->differenceDate($data->data_segretario_invio,$data->data_presidente_ritorno));
        $delibereGiorni->setGiorniCC($this->differenceDate($data->data_invio_cc,$data->data_registrazione_cc));
        $delibereGiorni->setGiorniGU($this->differenceDate($data->data_invio_gu,$data->data_gu));



        $em->persist($delibereGiorni); //create
        $em->flush(); //esegue l'update

        $response = new Response($this->serialize($relDelibereCC), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


	    /**
     * @SWG\Post(
     *     path="/api/delibere",
     *     summary="Creazione delibera",
     *     tags={"Delibere"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *       response="200", description="Operazione avvenuta con successo"
     *     ),
     *     @SWG\Response(response=401, description="Autorizzazione negata"))
     * )
     */


    /**
     * @Route("/delibere", name="delibere_item_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREATE_DELIBERE')")
     */
    public function delibereItemCreateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent());
        $check = $this->checkCampiObbligatori(json_decode($request->getContent()),["numero","argomento","data","id_uffici"]);
        if ($check != "ok") {
            $response_array = array("error" =>  ["code" => 409, "message" => "Il campo ".$check." e' obbligatorio"]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        }

        $delibere = new Delibere();

        $delibere->setNumero($data->numero);
        if ($data->data != null){ $delibere->setData(new \DateTime($this->zulu_to_rome($data->data))); }
        $delibere->setIdStato($data->id_stato);
        $delibere->setArgomento($data->argomento);
        $delibere->setFinanziamento($data->finanziamento);
        $delibere->setNote($data->note);
        $delibere->setNoteServizio($data->note_servizio);
        $delibere->setScheda($data->scheda);
        if ($data->data_consegna != null){ $delibere->setDataConsegna(new \DateTime($this->zulu_to_rome($data->data_consegna)));}
        $delibere->setIdDirettore($data->id_direttore);
        if ($data->data_direttore_invio != null){ $delibere->setDataDirettoreInvio(new \DateTime($this->zulu_to_rome($data->data_direttore_invio)));}
        if ($data->data_direttore_ritorno != null){ $delibere->setDataDirettoreRitorno(new \DateTime($this->zulu_to_rome($data->data_direttore_ritorno)));}
        $delibere->setNoteDirettore($data->note_direttore);
        $delibere->setInvioMef($data->invio_mef);
        if ($data->data_mef_invio != null){ $delibere->setDataMefInvio(new \DateTime($this->zulu_to_rome($data->data_mef_invio)));}
        if ($data->data_mef_pec != null){ $delibere->setDataMefPec(new \DateTime($this->zulu_to_rome($data->data_mef_pec))); }
        if ($data->data_mef_ritorno != null){ $delibere->setDataMefRitorno(new \DateTime($this->zulu_to_rome($data->data_mef_ritorno)));}
        $delibere->setIdSegretario($data->id_segretario);
        if ($data->data_segretario_invio != null){ $delibere->setDataSegretarioInvio(new \DateTime($this->zulu_to_rome($data->data_segretario_invio)));}
        if ($data->data_segretario_ritorno != null){ $delibere->setDataSegretarioRitorno(new \DateTime($this->zulu_to_rome($data->data_segretario_ritorno)));}
        $delibere->setNoteSegretario($data->note_segretario);
        $delibere->setIdPresidente($data->id_presidente);
        if ($data->data_presidente_invio != null){ $delibere->setDataPresidenteInvio(new \DateTime($this->zulu_to_rome($data->data_presidente_invio)));}
        if ($data->data_presidente_ritorno != null){ $delibere->setDataPresidenteRitorno(new \DateTime($this->zulu_to_rome($data->data_presidente_ritorno)));}
        $delibere->setNotePresidente($data->note_presidente);
        if ($data->data_invio_cc != null){ $delibere->setDataInvioCC(new \DateTime($this->zulu_to_rome($data->data_invio_cc)));}
        $delibere->setNumeroCC($data->numero_cc);
        if ($data->data_registrazione_cc != null){ $delibere->setDataRegistrazioneCC(new \DateTime($this->zulu_to_rome($data->data_registrazione_cc)));}
        $delibere->setIdRegistroCC($data->id_registro_cc);
        $delibere->setFoglioCC($data->foglio_cc);
        $delibere->setTipoRegistrazioneCC($data->tipo_registrazione_cc);
        $delibere->setNoteCC($data->note_cc);
        if ($data->data_invio_p != null){ $delibere->setDataInvioP(new \DateTime($this->zulu_to_rome($data->data_invio_p)));}
        if ($data->data_invio_gu != null){ $delibere->setDataInvioGU(new \DateTime($this->zulu_to_rome($data->data_invio_gu)));}
        $delibere->setNumeroInvioGU($data->numero_invio_gu);
        $delibere->setTipoGU($data->tipo_gu);
        if ($data->data_gu != null){ $delibere->setDataGU(new \DateTime($this->zulu_to_rome($data->data_gu)));}
        $delibere->setNumeroGU($data->numero_gu);
        if ($data->data_ec_gu != null){ $delibere->setDataEcGU(new \DateTime($this->zulu_to_rome($data->data_ec_gu)));}
        $delibere->setNumeroEcGU($data->numero_ec_gu);
        if ($data->data_co_gu != null){ $delibere->setDataCoGU(new \DateTime($this->zulu_to_rome($data->data_co_gu)));}
        $delibere->setNumeroCoGU($data->numero_co_gu);
        $delibere->setPubblicazioneGU($data->pubblicazione_gu);
        $delibere->setNoteGU($data->note_gu);

        $delibere->setNoteMef($data->note_mef);
        $delibere->setNoteFirma($data->note_firma);
        $delibere->setNoteP($data->note_p);

        $delibere->setSituazione($this->setFaseProceduraleDelibere($data));

        $em->persist($delibere);
        $em->flush(); //esegue query

        $id_delibere_creato = $delibere->getId();


        //        // FASCICOLI

//        foreach ($data->id_fascicoli as $item) {
//            $relDelibereFascicoli = new RelDelibereFascicoli();
//            $relDelibereFascicoli->setIdDelibere($data->id);
//            $relDelibereFascicoli->setIdFascicoli((int)$item);
//
//            $em->persist($relDelibereFascicoli); //create
//        }

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
		
		$repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("monitor");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente
		
		$repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
        $lastUpdates = $repositoryLastUpdates->findOneByTabella("monitor_group");
        $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

        $em->flush(); //esegue query


        $response = new Response($this->serialize($delibere), Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


    /**
     * @SWG\Delete(
     *     path="/api/delibere/{id}",
     *     summary="Eliminazione delibera",
     *     tags={"Delibere"},
     *     operationId="idDelibera",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id della delibera",
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
     * @Route("/delibere/{id}", name="delibere_item_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_DELETE_DELIBERE')")
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
     * @SWG\Post(
     *     path="/delibere/{id}/{tipo}/upload",
     *     summary="Upload files di una Delibera",
     *     tags={"Delibere"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id della Delibera",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Parameter(
     *         name="tipo",
     *         in="path",
     *         description="tipo di allegato [GU, CC, DEL, ALL, MEF]",
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
            $path_file = Costanti::URL_ALLEGATI_DELIBERE . "/per-anno/" . $dataDelibere . "/";
            $file = $request->files->get('file');
            $nome_file = $file->getClientOriginalName();

            if (strpos($nome_file, "E". substr($dataDelibere, 2,4) . str_pad($numeroDelibere, 4, '0', STR_PAD_LEFT)) !== false) {
                $nome_file = $this->sostituisciAccenti($nome_file);
            } else {
                $nome_file = "E" . substr($dataDelibere, 2, 4) . str_pad($numeroDelibere, 4, '0', STR_PAD_LEFT) . "-" . $tipo . "-" . $this->sostituisciAccenti($nome_file);
            }


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


        } elseif ($tipo == "MEF" || $tipo == "CC") {
            $path_file = Costanti::URL_ALLEGATI_DELIBERE . "/" . $tipo . "/E" . substr($dataDelibere, 2, 3) . sprintf("%04d", $numeroDelibere) . "/";
            $file = $request->files->get('file');
            $nome_file = $file->getClientOriginalName();
            $nome_file = $this->sostituisciAccenti($nome_file);
        } else {
            $path_file = Costanti::URL_ALLEGATI_DELIBERE . "/" . $tipo . "/" . $dataDelibere . "-" . $numeroDelibere . "/";
            $file = $request->files->get('file');
            $nome_file = $file->getClientOriginalName();
            $nome_file = $this->sostituisciAccenti($nome_file);
        }



        //
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
     * @SWG\Post(
     *     path="/delibere/{id}/{tipo}/{idodg}/upload",
     *     summary="Upload files di un rilievo della corte dei conti della delibera",
     *     tags={"Delibere"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id della Delibera",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
     *     @SWG\Parameter(
     *         name="tipo",
     *         in="path",
     *         description="tipo di allegato [CC]",
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
     * @Route("/delibere/{id}/{tipo}/{idrilievo}/upload", name="uploadDelibereRilieviCC")
     * @Method("POST")
     */

    public function uploadDelibereRilieviCCAction(Request $request, $id, $tipo, $idrilievo)
    {

        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:Delibere');
        $delibere = $repository->findOneBy(array("id" => $id));

        $dataDelibere = $delibere->getData()->format('Y');
        $numeroDelibere = $delibere->getNumero();

        $path_file = Costanti::URL_ALLEGATI_DELIBERE . "/" . $tipo . "/E" . substr($dataDelibere, 2, 3) . sprintf("%04d", $numeroDelibere) . "/" . $idrilievo . "/" ;
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

        $allegatoRel = new RelAllegatiDelibereCCR();
        $allegatoRel->setIdAllegati($id_allegato_creato);
        $allegatoRel->setIdDelibereCCR($idrilievo);



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
     *     path="/api/delibere/{id}/{tipo}/upload/{idallegato}",
     *     summary="Eliminazione file di una Delibera",
     *     tags={"Delibere"},
     *     operationId="idDelibera",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="id della Delibera",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="integer"),
     *     ),
	 *     @SWG\Parameter(
     *         name="tipo",
     *         in="path",
     *         description="tipo di allegato [GU, CC, DEL, ALL, MEF]",
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
     * @Route("/delibere/{id}/{tipo}/{idrilievo}/upload/{idfile}", name="uploadDelibereRilievi2")
     * @Method("DELETE")
     */
    public function delibereCCAllegatiItemDeleteAction(Request $request, $id, $tipo, $idrilievo, $idfile )
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:RelAllegatiDelibereCCR');
        $relazione_allegato = $repository->findOneBy(array('idAllegati' => $idfile, 'idDelibereCCR' => $idrilievo));
        //$relazione_allegato = $repository->findOneById($idallegato);

        $repository_file = $em->getRepository('UserBundle:Allegati');
        $file = $repository_file->findOneById($idfile);

        //$idRelAllegatiRegistri = $relazione_allegato[0]->getId();


        if (!$relazione_allegato) {
            $response_array = array("error" => ["code" => 409, "message" => "Questo file non e' allegato a questa delibera."]);
            $response = new Response(json_encode($response_array), 409);
            return $this->setBaseHeaders($response);
        } else {

            //aggiorna la date della modifica nella tabella msc_last_updates
            $repositoryLastUpdates = $em->getRepository('UserBundle:LastUpdates');
            $lastUpdates = $repositoryLastUpdates->findOneByTabella("delibere");
            $lastUpdates->setLastUpdate(new \DateTime()); //datetime corrente

            $response = new Response($this->serialize($relazione_allegato), Response::HTTP_OK);

            try {

                $em->remove($relazione_allegato); //delete
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
     * @Route("/rilievicc/{id}/upload", name="uploadDelibereCorteConti")
     * @Method("POST")
     */
    public function uploadDelibereCorteContiAction(Request $request, $id, $tipo)
    {

        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:DelibereCC');
        $delibereCC = $repository->findOneBy(array("id" => $id));

        $idDelibera = $delibereCC->getIdDelibere();
        $path_file = Costanti::URL_ALLEGATI_DELIBERE . "CCR/" . $idDelibera . "/";

        $file = $request->files->get('file');
        $nome_file = $file->getClientOriginalName();
        $nome_file = $id . "-" . $this->sostituisciAccenti($nome_file);

        //memorizzo il file nel database
        $allegato = new Allegati();
        $allegato->setData(new \DateTime());
        $allegato->setFile($path_file . $nome_file);

        $em->persist($allegato);
        $em->flush(); //esegue query

        $id_allegato_creato = $allegato->getId();


        $allegatoRel = new RelAllegatiDelibereCCR();
        $allegatoRel->setIdAllegati($id_allegato_creato);
        $allegatoRel->setIdDelibereCCR($id);


        $array = array(
            'id' => $id_allegato_creato,
            'id_delibere_ccr' => $id,
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
            $file->move($path_file, $nome_file);

        } catch (\Doctrine\ORM\EntityNotFoundException $ex) {
            echo "Exception Found - " . $ex->getMessage() . "<br/>";
        }


        $response = new Response(json_encode($array), Response::HTTP_OK);
        return $this->setBaseHeaders($response, "upload");
    }



    /**
     * @Route("/rielievicc/{id}/upload/{idallegato}", name="uploadDeleteDelibereCCR")
     * @Method("DELETE")
     */
    public function delibereCCRAllegatiItemDeleteAction(Request $request, $id, $tipo, $idallegato)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UserBundle:RelAllegatiDelibereCCR');
        $relazione_allegato = $repository->findBy(array('idAllegati' => $idallegato, 'idDelibereCCR' => $id));
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
     * @Route("/delibere/{id}/{tipo}/{idrilievo}/upload", name="DelibereRilieviCCUpload_item_options")
     * @Method("OPTIONS")
     */
    public function delibereRilieviCCUploadItemOptions(Request $request, $id, $tipo, $idrilievo)
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

    /**
     * @Route("/rilievicc/{id}/upload", name="DelibereCCRUpload_item_options")
     * @Method("OPTIONS")
     */
    public function delibereCCRUploadItemOptions(Request $request, $id)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/rilievicc/{id}/upload/{idallegato}", name="DelibereCCRDeleteUpload_item_options")
     * @Method("OPTIONS")
     */
    public function delibereCCRDeleteUploadItemOptions(Request $request, $id, $idallegato)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }

    /**
     * @Route("/delibere/{id}/{tipo}/{idrilievo}/upload/{idfile}", name="uploadDelibereRilievi2_item_option")
     * @Method("OPTIONS")
     */
    public function delibereCCR2DeleteUploadItemOptions(Request $request, $id, $idfile, $idrilievo)
    {

        $response = new Response(Response::HTTP_OK);
        return $this->setBaseHeaders($response);
    }


}
