<?php

ini_set("soap.wsdl_cache_enabled", 1);

/**
* Descripci�n: Clase Gen�rica para realizar el timbrado y cancelaci�n de un CFDI con Facturaci�n Moderna.
*
* Facturaci�n Moderna :  (http://www.facturacionmoderna.com)
* @author Edgar Dur�n <edgar.duran@facturacionmoderna.com>
* @package FacturacionModerna
* @version 1.3
* @see http://developers.facturacionmoderna.com
**/

class FacturacionModerna {


	public $opciones = array();
  public $xml;
  public $pdf;
  public $UUID;

  public $ultimoError;
  public $ultimoCodigoError;

  public $log = 'FacturacionModerna-log.txt';
  public $debug;
  public $url;


  /**
  * Crea el objeto de conexi�n con la API de Facturaci�n Moderna, para
  * acceder a los m�todos de timbrado y cancelaci�n de CFDI.
  *
  * @param string $url
  * @param array $opciones
  * @param boolean $debug
  * @return boolean
  */
	public function __construct($url, $opciones = array(), $debug = 0) {
    $this->debug = (int) $debug;
		$this->url = $url;
		foreach ($opciones as $k => $v) {
			if(isset($v) && in_array($k, array("emisorRFC", 'UserID', 'UserPass'))){
        $this->opciones[$k] = $v;
      }
		}
	}

  /**
  * Ejecuta el m�todo SOAP requestTimbrarCFDI() del Servicio Web de
  * Facturacion Moderna, p�blicado en FacturacionModerna::url
  * http://developers.facturacionmoderna.com/#requestTimbrarCFDI
  *
  * Recibe como par�metro principal $str que contiene la ruta o contenido del
  * archivo a certificar, el archivo debe ser algunos de los soportados por
  * la API. http://developers.facturacionmoderna.com/#layout
  *
  * En caso de una petici�n exitosa, el m�todo establece los [Valores] a las
  * propiedades de la clase FacturacionModerna
  *
  * En caso de error establece las propiedades ultimoError y
  * ultimoCodigoError, con el mensaje y c�digo de error correspondientes.
  * El listado de mensajes de error se encuentra en: http://developers.facturacionmoderna.com/#errores
  *
  *
  * [Valores]
  *
  * FacturacionModerna::UUID, contiene el UUID del �ltimo comprobante certificado.
  *
  * FacturacionModerna::xml, contiene el comprobante certificado en formato XML.
  *
  * FacturacionModerna::txt, contiene en formato de texto simple el contenido del nodo TimbreFiscalDigital del CFDI.
  * El valor se establece siempre y cuando $opciones['generarTXT'] = true
  *
  * FacturacionModerna::pdf, contiene la representaci�n impresa del CFDI en formato PDF.
  * se debe utilizar cu�ndo se requiera que FacturacionModerna gener� un formato PDF gen�rico para un CFDI,
  * para ser generado $opciones['generarPDF'] = true
  *
  * FacturacionModerna::png, contiene el C�digo de Barras Bidimensional o CBB
  * (QR-Code) el cu�l debe estar presente en la representaci�n impresa del
  * CFDI.
  *
  * Nota: EL CBB se genera siempre y cuando $opciones['generarCBB'] sea igual a
  * true, utilizar est� opci�n dehabilita 'generarPDF'
  *
  *
  * @param string $str Contenido o Rut� del del comprobante a certificar.
  * @return void
  */
	public function timbrar($str, $opciones = array('generarCBB' => false, 'generarTXT' => false, 'generarPDF' => false)){

    try {

      //Si $str es la ruta a un archivo leerlo.
      if(file_exists($str)){
        $str = file_get_contents($str);
      }
      //C�dificar el comprobante a certificar en Base64
      $opciones['text2CFDI'] = base64_encode($str);
      $opciones = array_merge($opciones, $this->opciones);


      $cliente = new SoapClient($this->url, array('trace' => 1));
			$respuesta = $cliente->requestTimbrarCFDI((object) $opciones);

      //Establecer las propiedades con el objeto de respuesta SOAP.
      foreach(array('xml', 'pdf', 'png', 'txt') as $propiedad){
        if(isset($respuesta->$propiedad)){
          $this->$propiedad = base64_decode($respuesta->$propiedad);
        }
      }

      if(isset($respuesta->xml)){
        $xml_cfdi = simplexml_load_string($this->xml);
        $xml_cfdi->registerXPathNamespace("tfd", "http://www.sat.gob.mx/TimbreFiscalDigital");
        $tfd = $xml_cfdi->xpath('//tfd:TimbreFiscalDigital');
        $this->UUID = (string) $tfd[0]['UUID'];
      }

      if($this->debug == 1){
        $this->log("SOAP request:\t".$cliente->__getLastRequest());
        $this->log("SOAP response:\t".$cliente->__getLastResponse());
      }

      return true;

		}catch (SoapFault $e){

      if($this->debug == 1){
        $this->log("SOAP request:\t".$cliente->__getLastRequest());
        $this->log("SOAP response:\t".$cliente->__getLastResponse());
      }
      $this->ultimoError = $e->faultstring;
      $this->ultimoCodigoError = $e->faultcode;

		}catch (Exception $e){
      $this->ultimoError = $e->getMessage();
      $this->ultimoCodigoError = "Unknown";
		}
    return false;
	}

  /**
  * Ejecuta el m�todo SOAP requestCancelarCFDI() del Servicio Web de
  * Facturacion Moderna p�blicado en FacturacionModerna::url
  * http://developers.facturacionmoderna.com/#requestCancelarCFDI
  *
  * Recibe el UUID de un CFDI para reportar la cancelaci�n del mismo ante los servicios del SAT.
  *
  * @param string $uuid
  * @return boolean
  *
  */

  public function cancelar($uuid){
    try{
      $cliente = new SoapClient($this->url, array('trace' => 1));
      $opciones['uuid'] = (string) $uuid;
      $opciones = array_merge($opciones, $this->opciones);
      $respuesta = $cliente->requestCancelarCFDI((object) $opciones);
      return true;
    }catch (SoapFault $e){

      if($this->debug == 1){
        $this->log("SOAP request:\t".$cliente->__getLastRequest());
        $this->log("SOAP response:\t".$cliente->__getLastResponse());
      }
      $this->ultimoError = $e->faultstring;
      $this->ultimoCodigoError = $e->faultcode;

		}catch (Exception $e){
      $this->ultimoError = $e->getMessage();
      $this->ultimoCodigoError = "Unknown";
		}
    return false;
  }

/**
  * Ejecuta el m�todo SOAP activarCancelacion() del Servicio Web de
  * Facturacion Moderna p�blicado en FacturacionModerna::url
  * http://developers.facturacionmoderna.com/#activarCancelacion
  *
  * Recibe los archivos Cer, Key y Contrase�a para activar el servicio de cancelaci�n de CFDIS por medio de FM hacia servicios del SAT.
  *
  * @param string $archCer Contenido o Rut� del archivo Cer del CSD a activar.
  * @param string $archKey Contenido o Rut� del archivo Key del CSD a activar.
  * @param string $passKey Contrase�a del archivo Key del CSD a activar.
  * @return boolean
  */
  public function activarCancelacion($archCer=null,$archKey=null,$passKey){
    try {
      //Si $archCer y/o $archKey son rutas de archivos, cargarlos
      if(file_exists($archCer)){
        $archCer = file_get_contents($archCer);
      }
      if(file_exists($archKey)){
        $archKey = file_get_contents($archKey);
      }
      $opciones=array();
      //C�dificar los archivos a utilizar en Base64
      $opciones['archivoKey'] = base64_encode($archKey);
      $opciones['archivoCer'] = base64_encode($archCer);
      $opciones['clave'] = $passKey;
      $opciones = array_merge($opciones, $this->opciones);

      $cliente = new SoapClient($this->url, array('trace' => 1));
      $respuesta = $cliente->activarCancelacion((object) $opciones);
      return true;
    }catch (SoapFault $e){
      if($this->debug == 1){
        $this->log("SOAP request:\t".$cliente->__getLastRequest());
        $this->log("SOAP response:\t".$cliente->__getLastResponse());
      }
      $this->ultimoError = $e->faultstring;
      $this->ultimoCodigoError = $e->faultcode;
    }catch (Exception $e){
      $this->ultimoError = $e->getMessage();
      $this->ultimoCodigoError = "Unknown";
    }
    return false;
  }

  /**
  * Registra los mensajes SOAP en el archivo FacturacionModerna::log, si el
  * archivo no existe lo crea.
  *
  * S�lo se ejecuta si FacturacionModerna::debug tiene los valores 1 � 2
  * @param $str
  * @return void
  */
  private function log($str){
    $f = fopen($this->log, 'a');
    fwrite($f, date('c')."\t".$str."\n\n");
    fclose($f);
  }


}


?>
