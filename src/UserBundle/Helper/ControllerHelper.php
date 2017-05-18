<?php

namespace UserBundle\Helper;

use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializationContext;

trait ControllerHelper
{
    /**
     * Set base HTTP headers.
     *
     * @param Response $response
     *
     * @return Response
     */
    private function setBaseHeaders(Response $response, $upload = 0)
    {

        if ($upload == "upload") {
            $response->headers->set('Access-Control-Allow-Origin', 'http://mosic.hantarex.org');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        } else {
            $response->headers->set('Access-Control-Allow-Origin', '*');
        }

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, HEAD');
        $response->headers->set('Access-Control-Allow-Headers', 'Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers, Authorization');
        $response->headers->set("pragma", "no-store,no-cache"); // //HTTP 1.0
        $response->headers->set("cache-control", "private, no-cache, no-store, must-revalidate, max-age=0"); // HTTP 1.1
        $response->headers->set("expires", "Mon, 14 Jul 1789 12:30:00 GMT"); // Date in the past

        return $response;
    }



    /**
     * Data serializing via JMS serializer.
     *
     * @param mixed $data
     *
     * @return string JSON string
     */
    public function serialize($data)
    {
        $context = new SerializationContext();
        $context->setSerializeNull(true);

        return $this->get('jms_serializer')->serialize($data, 'json', $context);
    }
		
		
    /**
     * Per convertire le date.
     *
     * @param mixed $data
     *
     * @return string JSON string
     */
    public function formatDateJsonArrayCustom($data, $array_date) {        
        foreach ($data as $item) {
                foreach ($array_date as $item_date) {

                        if (substr($item->$item_date, 0, 1) == "-") {
                            $item->$item_date = '';
                        } else {
                            $item->$item_date = substr($item->$item_date, 0, 10);
                            //$item->$item_date = strtotime($item->$item_date  . " +1 days") * 1000;
                            $item->$item_date = strtotime($item->$item_date) * 1000;
                        }
                }
        }
        return $data;
    }

    public function formatDateJsonArrayCustom2($data, $array_date) {
        foreach ($data as $key => $item) {
            foreach ($array_date as $item_date) {

                if (substr($item[$item_date], 0, 1) == "-" || $item[$item_date] == '0000-00-00') {
                    $data[$key][$item_date] = '';
                } else {
                    $data[$key][$item_date] = substr($data[$key][$item_date], 0, 10);
                    $data[$key][$item_date] = strtotime($data[$key][$item_date]) * 1000;
                }
            }
        }

        return $data;
    }



    public function formatDateJsonCustom($data, $array_date) {
                foreach ($array_date as $item_date) {

                        if (substr($data[$item_date], 0, 1) == "-" || $data[$item_date] == '0000-00-00' || $data[$item_date] == null || $data[$item_date] == '' ) {
                            $data[$item_date] = '';
                        } else {
                            $data[$item_date] = substr($data[$item_date], 0, 10);
                            $data[$item_date] = strtotime($data[$item_date] . " +1 days") * 1000;
                        }
                }
        return $data;
    }
    public function formatDateJsonArrayCustomLastUpdates($data, $array_date) {        
        foreach ($data as $item) {
                foreach ($array_date as $item_date) {
                    
                        if (substr($item->$item_date, 0, 1) == "-") {
                            $item->$item_date = '';
                        } else {
                            $array_temp = explode("T", $item->$item_date);
							$item->$item_date = substr($array_temp[0], 0, 10) . " " . substr($array_temp[1], 0, 8);
                            $item->$item_date = strtotime($item->$item_date) * 1000;
                        }
                }
        }
        return $data;
    }
    
    /**
     * Per convertire le datetime.
     *
     * @param mixed $data
     *
     * @return string JSON string
     */
    public function formatDateTimeJsonCustom($data, $array_date) {
				$data = json_decode($data);
				foreach ($data as $item) {
						foreach ($array_date as $item_date) {
							
								if (substr($item->$item_date, 0, 1) == "-") {
									$item->$item_date = '';
								} else {
                                    $array_temp = explode("T", $item->$item_date);
									$item->$item_date = substr($array_temp[0], 0, 10) . " " . substr($array_temp[1], 0, 8);
                                    $item->$item_date = strtotime($item->$item_date  . " +1 days") * 1000;
								}
						}
				}
				return $data;
    }
    
    
    /**
     * Per convertire le datetime.
     *
     * @param mixed $data
     *
     * @return string JSON string
     */
    public function formatDateStringCustom($data) {
							
        if (substr($data, 0, 1) == "-" || $data == '0000-00-00' || $data == '' || $data == null) {
            $data = '';
        } else {
            $data = substr($data, 0, 10);
        }
        

        return $data;
    }


    /**
     * Differenza tra due date.
     *
     * @param mixed $data
     *
     * @return string JSON string
     */
    public function differenceDate($start, $end) {
        if ($end == null || $end == "") {
            return 0;
        }

        $end_temp = explode("T", $end);
        $start_temp = explode("T", $start);

        $diffInSeconds = strtotime($end) - strtotime($start);
        $diffInDays = $diffInSeconds / 86400;



//        $then = '2005-09-01 09:02:23';
//        $then = new DateTime($then);
//        $now = new DateTime();
//        $sinceThen = $then->diff($now);
//        $diffInDays = $sinceThen->d; //.' days have passed.<br>';

        return $diffInDays;
    }
    
		
//    /**
//     * Per inserire le relazioni nella risposta .
//     *
//     * @param mixed $data
//     *
//     * @return string JSON string
//     */
//    public function insertRel($data, $relazioni, $campo) {
//							
//				$data = json_decode($data, true); //trasformo in array
//				$relazioni = json_decode($relazioni, true); //trasformo in array
//				
//				$data[$campo] = "";
//				foreach ($relazioni as $item => $value) {
//						$data[$campo] = $data[$campo] . "," . $value['id_amministrazioni'];
//				}
//				$data[$campo] = substr($data[$campo], 1); // rimuove il primo carattere (cioè la prima virgola che aggiungiamo)
//        return $data;
//    }
    


    /**
     * (sostituisce la funzione insertRel)
     *
     * @param mixed $data
     *
     * @return string JSON string
     */
    public function mergeIdAmministrazioni($data) {

        $data = json_decode($data, true); //trasformo in array
        $array = array();
        $array_amministrazioni = array();
        $array_tags = array();
        foreach ($data as $item => $value) {
            if (array_key_exists($value['id'],$array)) {
                if (!in_array($value['id_amministrazioni'],$array_amministrazioni)) {
                    $array[$value['id']]['id_amministrazioni'] = $array[$value['id']]['id_amministrazioni'] . "," . $value['id_amministrazioni'];
                    $array_amministrazioni[] = $value['id_amministrazioni'];
                }
                if (!in_array($value['id_tags'],$array_tags)) {
                    $array[$value['id']]['id_tags'] = $array[$value['id']]['id_tags'] . "," . $value['id_tags'];
                    $array_tags[] = $value['id_tags'];
                }

            } else {
                $array[$value['id']] = $value;
                $array_amministrazioni[] = $value['id_amministrazioni'];
                $array_tags[] = $value['id_tags'];
            }
        }

        if (count($array) > 1) {
            return array_values($array);
        } else {
            $array = array_values($array);
            return $array[0];
        }
    }

    /**
 * (sostituisce la funzione insertRel)
 *
 * @param mixed $data
 *
 * @return string JSON string
 */
    public function mergeRelDelibereAll($data) {

        $data = json_decode($data, true); //trasformo in array
        $array = array();
        $array_amministrazioni = array();
        foreach ($data as $item => $value) {
            if (array_key_exists($value['id'],$array)) {
                if (!in_array($value['id_uffici'],$array_amministrazioni)) {
                    $array[$value['id']]['id_uffici'] = $array[$value['id']]['id_uffici'] . "," . $value['id_uffici'];
                    $array_amministrazioni[] = $value['id_uffici'];
                }

            } else {
                $array[$value['id']] = $value;
                $array_amministrazioni[] = $value['id_uffici'];
            }
            $array[$value['id']]['anno'] = substr($array[$value['id']]['data'], 0, 4);
        }

        if (count($array) > 1) {
            return array_values($array);
        } else {
            $array = array_values($array);
            return $array[0];
        }
    }

    /**
     * (sostituisce la funzione insertRel)
     *
     * @param mixed $data
     *
     * @return string JSON string
     */
    public function mergeRelDelibereItem($data) {

        $data = json_decode($data, true); //trasformo in array
        $array = array();
        $array_amministrazioni = array();
        $array_firmatari = array();

        foreach ($data as $item => $value) {
            if (array_key_exists($value['id'],$array)) {
                if (!in_array($value['id_uffici'],$array_amministrazioni)) {
                    $array[$value['id']]['id_uffici'] = $array[$value['id']]['id_uffici'] . "," . $value['id_uffici'];
                    $array_amministrazioni[] = $value['id_uffici'];
                }
                if (!in_array($value['id_segretariato'],$array_firmatari)) {
                    $array[$value['id']]['id_segretariato'] = $array[$value['id']]['id_segretariato'] . "," . $value['id_segretariato'];
                    $array_firmatari[] = $value['id_segretariato'];
                }
            } else {
                $array[$value['id']] = $value;
                $array_amministrazioni[] = $value['id_uffici'];
                $array_firmatari[] = $value['id_segretariato'];
            }
        }


        if (count($array) > 1) {
            return array_values($array);
        } else {
            $array = array_values($array);
            return $array[0];
        }
    }



    /**
     * (sostituisce la funzione insertRel)
     *
     * @param mixed $data
     *
     * @return string JSON string
     */
    public function mergeRegistriOdg($data) {

        $data = json_decode($data, true); //trasformo in array
        $array = array();

        foreach ($data as $item => $value) {
            $array[] = $value['id_registri'];
        }

        if (count($array) > 1) {
            return array_values($array);
        } else {
            $array = array_values($array);
            return $array[0];
        }
    }

    /**
     * (sostituisce la funzione insertRel)
     *
     * @param mixed $data
     *
     * @return string JSON string
     */
    public function mergeUfficiOdg($data) {

        $data = json_decode($data, true); //trasformo in array
        $array = array();

        foreach ($data as $item => $value) {
            $array[] = $value['id_uffici'];
        }

        if (count($array) > 1) {
            return array_values($array);
        } else {
            $array = array_values($array);
            return $array[0];
        }
    }



    /**
     * (ricavo l'id del codice dei ruoli cipe)
     *
     * @param int $data
     *
     * @return int
     */
    public function convertiIdRuoliCipe($codice) {
        $id = 0;
        switch ($codice) {
            case 0:
                $id = 1;
                break;
            case 1:
                $id = 6;
                break;
            case 2:
                $id = 5;
                break;
            case 3:
                $id = 4;
                break;
            case 4:
                $id = 3;
                break;
            case 5:
                $id = 2;
                break;
            case 6:
                $id = 7;
                break;
            default:
                $id = 0;
        }

        return $id;
    }





    /**
     * (sostituisce accenti)
     *
     * @param mixed $data
     *
     * @return string JSON string
     */
    public function sostituisciAccenti($f) {
//      $search = explode("á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,ø,Ø,Å,Á,À,Â,Ä,È,É,Ê,Ë,Í,Î,Ï,Ì,Ò,Ó,Ô,Ö,Ú,Ù,Û,Ü,Ÿ,Ç");
//      $replace = explode("a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,o,O,A,A,A,A,A,E,E,E,E,I,I,I,I,O,O,O,O,U,U,U,U,Y,C");
//      return str_replace($search, $replace, $str);

        // a combination of various methods
        // we don't want to convert html entities, or do any url encoding
        // we want to retain the "essence" of the original file name, if possible
        // char replace table found at:
        // http://www.php.net/manual/en/function.strtr.php#98669
        $replace_chars = array(
            "'"=>'', ' ' => '-', '°' => '',
            'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
            'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
            'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
            'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
            'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
            'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
            'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f'
        );
        $f = strtr($f, $replace_chars);
        // convert & to "and", @ to "at", and # to "number"
        $f = preg_replace(array('/[\&]/', '/[\@]/', '/[\#]/'), array('-and-', '-at-', '-number-'), $f);
        $f = preg_replace('/[^(\x20-\x7F)]*/','', $f); // removes any special chars we missed
        $f = str_replace(' ', '-', $f); // convert space to hyphen
        $f = str_replace('\'', '', $f); // removes apostrophes
        $f = preg_replace('/[^\w\-\.]+/', '', $f); // remove non-word chars (leaving hyphens and periods)
        $f = preg_replace('/[\-]+/', '-', $f); // converts groups of hyphens into one
        return $f;

    }

    public function getExtension ($mime_type){

        $extensions = array(
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'application/pdf' => 'pdf',
            'application/msword' => 'doc'
        );

        // Add as many other Mime Types / File Extensions as you like

        return $extensions[$mime_type];
    }


    /**
     * @param mixed $data
     * @return string JSON string
     */
    public function setFaseProceduraleDelibere($data) {
        foreach ($data as $key => $value) {
            //default
            $data[$key]['situazione'] = 0;

            // 1 - Da acquisire
            if ($value['data_consegna'] == "") {
                $data[$key]['situazione'] = 1;
            }
            // 2 - In lavorazione
            if ($value['data_consegna'] != "" && $value['data_segretario_invio'] == "" && $value['data_presidente_invio'] == "") {
                $data[$key]['situazione'] = 2;
            }
            // 5 - In firma Segretario Cipe
            if ($value['data_consegna'] != "" && $value['data_segretario_invio'] != "" && $value['data_segretario_ritorno'] == "") {
                $data[$key]['situazione'] = 5;
            }
            // 6 - In firma Presidente Cipe
            if ($value['data_consegna'] != "" && $value['data_segretario_invio'] != "" && $value['data_segretario_ritorno'] != "" && $value['data_presidente_invio'] != "" && $value['data_presidente_ritorno'] == "") {
                $data[$key]['situazione'] = 6;
            }
            // 7 - Alla Corte dei Conti
            if ($value['data_invio_cc'] != "" && $value['data_registrazione_cc'] == "") {
                $data[$key]['situazione'] = 7;
            }
            // 8 - Alla Gazzetta Ufficiale
            if ($value['data_invio_gu'] != "" && $value['data_gu'] == "") {
                $data[$key]['situazione'] = 8;
            }

            unset($data[$key]['data_consegna']);
            unset($data[$key]['data_segretario_invio']);
            unset($data[$key]['data_segretario_ritorno']);
            unset($data[$key]['data_presidente_invio']);
            unset($data[$key]['data_presidente_ritorno']);
            unset($data[$key]['data_invio_cc']);
            unset($data[$key]['data_registrazione_cc']);
            unset($data[$key]['data_invio_gu']);
            unset($data[$key]['data_gu']);
        }
        return $data;
    }


    public function setCastDelibere($data, $tipo) {
        if ($tipo == "item") {
            $array[0] = $data;
        } else {
            $array = $data;
        }

        foreach ($array as $key => $value) {
            //default
            $array[$key]['id'] = (int) $array[$key]['id'];
            $array[$key]['numero'] = (int) $array[$key]['numero'];
            if (isset($array[$key]['id_stato'])) {
                $array[$key]['id_stato'] = (int)$array[$key]['id_stato'];
            }
            $array[$key]['finanziamento'] = (int) $array[$key]['finanziamento'];
            if (isset($array[$key]['scheda'])) {
                $array[$key]['scheda'] = (int)$array[$key]['scheda'];
            }
            if (isset($array[$key]['invio_mef'])) {
                $array[$key]['invio_mef'] = (int)$array[$key]['invio_mef'];
            }
            if (isset($array[$key]['id_segretario'])) {
                $array[$key]['id_segretario'] = (int)$array[$key]['id_segretario'];
            }
            if (isset($array[$key]['id_presidente'])) {
                $array[$key]['id_presidente'] = (int)$array[$key]['id_presidente'];
            }
            if (isset($array[$key]['numero_cc'])) {
                $array[$key]['numero_cc'] = (int)$array[$key]['numero_cc'];
            }
            if (isset($array[$key]['invio_ragioneria_cc'])) {
                $array[$key]['invio_ragioneria_cc'] = (int)$array[$key]['invio_ragioneria_cc'];
            }

            if (isset($array[$key]['id_registro_cc'])) {
                $array[$key]['id_registro_cc'] = (int)$array[$key]['id_registro_cc'];
            }
            if (isset($array[$key]['foglio_cc'])) {
                $array[$key]['foglio_cc'] = (int)$array[$key]['foglio_cc'];
            }
            if (isset($array[$key]['numero_invio_gu'])) {
                $array[$key]['numero_invio_gu'] = (int)$array[$key]['numero_invio_gu'];
            }
            if (isset($array[$key]['tipo_gu'])) {
                $array[$key]['tipo_gu'] = (int)$array[$key]['tipo_gu'];
            }
            if (isset($array[$key]['numero_gu'])) {
                $array[$key]['numero_gu'] = (int)$array[$key]['numero_gu'];
            }
            if (isset($array[$key]['numero_ec_gu'])) {
                $array[$key]['numero_ec_gu'] = (int)$array[$key]['numero_ec_gu'];
            }
            if (isset($array[$key]['numero_co_gu'])) {
                $array[$key]['numero_co_gu'] = (int)$array[$key]['numero_co_gu'];
            }
            if (isset($array[$key]['pubblicazione_gu'])) {
                $array[$key]['pubblicazione_gu'] = (int)$array[$key]['pubblicazione_gu'];
            }

            if (isset($array[$key]['id_uffici'])) {
                $array[$key]['id_uffici'] = json_decode('[' . $array[$key]['id_uffici'] . ']', true);
            }
            if (isset($array[$key]['id_firmatari'])) {
                $array[$key]['id_segretariato'] = json_decode('[' . $array[$key]['id_firmatari'] . ']', true);
                unset($array[$key]['id_firmatari']);
            }
        }



        return $array;
    }



}
