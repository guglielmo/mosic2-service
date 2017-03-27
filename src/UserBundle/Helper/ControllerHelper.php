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
    public function formatDateJsonCustom($data, $array_date) {        
                foreach ($array_date as $item_date) {
                    
                        if (substr($data[$item_date], 0, 1) == "-") {
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
							
        if (substr($data, 0, 1) == "-") {
            $data = '';
        } else {
            $data = substr($data, 0, 10);
        }
        

        return $data;
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
    public function sostituisciAccenti($str) {
      $search = explode("\\","/",".."," ",",","á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,ø,Ø,Å,Á,À,Â,Ä,È,É,Ê,Ë,Í,Î,Ï,Ì,Ò,Ó,Ô,Ö,Ú,Ù,Û,Ü,Ÿ,Ç");
      $replace = explode("-","-","-","-",",","a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,o,O,A,A,A,A,A,E,E,E,E,I,I,I,I,O,O,O,O,U,U,U,U,Y,C");
      return str_replace($search, $replace, $str);
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





}
