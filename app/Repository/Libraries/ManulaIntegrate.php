<?php

namespace NTI\Repository\Libraries;

class ManulaIntegrate
{
  const VERSION = "2.0.2";
  
  private $_integrateUrl;
  private $_requestPath;
  
  private $_account;
  private $_group = array();
  private $_manual;
  private $_manualUrl;
  
  private $_userIp;
  private $_userAgent;
  
  private $_connection;
  private $_rawResponse;
  private $_transferDetails;
  
  private $_header;
  private $_body;
  
  private $_sid;
  
  public function __construct()
  {
    $this->_requestPath = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : "";
    
    $this->_userIp = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "";
    $this->_userAgent = isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : "";
  }
    
  public function setIntegrateUrl($url)
  {
    $this->_integrateUrl = $url;
  }
  
  public function setAccount($urlName)
  {
    $this->_account = $urlName;
  }
  
  public function setGroup()
  {
    $this->_group = array();
    
    $groups = func_get_args();
    
    foreach ($groups as $group) {
      $this->_group[] = $group;
    }
  }
  
  public function setManual($urlName)
  {
    $this->_manual = $urlName;
  }

  public function setManualUrl($url)
  {
    $this->_manualUrl = $url;
  }
  
  public function execute()
  {
    $this->_setSid();
    $this->_setKey();
    
    $this->_dispatch();
    
    list($this->_header, $this->_body) = explode("\r\n\r\n", $this->_rawResponse, 2);
    
    if ($this->_key != "") {
      $this->_outputHeaders($this->_headersArray($this->_header));
    }
    
    print $this->_body;
  }

  private function _setSid()
  {
    $this->_sid = "";
    
    if (isset($_GET["sid"]) && $_GET["sid"] != "") {
      $this->_sid = $_GET["sid"];
      
      $this->_setCookie("Manula_App", $this->_sid);
    } else if (isset($_COOKIE["Manula_App"]) && $_COOKIE["Manula_App"] != "") {
      $this->_sid = $_COOKIE["Manula_App"];
    }
  }
  
  private function _setKey()
  {
    $this->_key = isset($_GET["key"]) ? $_GET["key"] : "";
  }
  
  private function _dispatch()
  {
    $cookies = array();
    
    if ($this->_sid != "") {
      $cookies[] = "Manula_App=".$this->_sid;
    }
    
    $this->_connection = curl_init();
    curl_setopt($this->_connection, CURLOPT_URL, $this->_buildRequestUrl());
    curl_setopt($this->_connection, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($this->_connection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($this->_connection, CURLOPT_HEADER, true);
    curl_setopt($this->_connection, CURLOPT_USERAGENT, $this->_userAgent);
    curl_setopt($this->_connection, CURLOPT_COOKIE, implode("; ", $cookies));
    
    $this->_rawResponse = curl_exec($this->_connection);
    //$this->_transferDetails = curl_getinfo($this->_connection);
    
    curl_close($this->_connection);
  }
  
  private function _buildRequestUrl()
  {
    $url = $this->_integrateUrl;
    
    $params = array();
    $params["account"] = $this->_account;
    $params["group"] = implode(" ", $this->_group);
    $params["manual_name"] = $this->_manual;
    $params["manual_root"] = $this->_manualUrl;
    $params["request_page"] = $this->_requestPath;
    $params["user_ip"] = $this->_userIp;
    $params["can_use_session"] = "yes";
    
    $url .= "?";
    $url .= http_build_query($params);
    
    return $url;
  }
  
  private function _outputHeaders($headers, $passedKey = "")
  {
    foreach ($headers as $key => $value) {
      if (is_array($value)) {
        $this->_outputHeaders($value, $key);
      } else {
        $usedKey = $passedKey != "" ? $passedKey : $key;
        
        if ($usedKey == "Set-Cookie") {
          $attrs = $this->_headerAttrs($value);
          
          if (array_key_exists("Manula_App", $attrs)) {
            $this->_setCookie("Manula_App", $attrs["Manula_App"]);
          }
        }
      }
    }
  }
  
  private function _headersArray($headersStr)
  {
    $headers = array();
    
    foreach (explode("\r\n", $headersStr) as $i => $header) {
      if ($i === 0) {
        $headers['HTTP-Status'] = $header;
      } else {
        list ($key, $value) = explode(': ', $header);
        
        if (array_key_exists($key, $headers)) {
          if (is_array($headers[$key])) {
            $headers[$key][] = $value;
          } else {
            $prevVal = $headers[$key];
            $headers[$key] = array($prevVal, $value);
          }
        } else {
          $headers[$key] = $value;
        }
      }
    }
    
    return $headers;
  }
  
  private function _headerAttrs($header)
  {
    $pairs = explode(";", $header);
    
    $attrs = array();
    
    foreach ($pairs as $pair) {
      $attr = explode("=", $pair);
      
      $key = trim($attr[0]);
      $value = trim($attr[1]);
      
      $attrs[$key] = $value;
    }
    
    return $attrs;
  }
  
  private function _setCookie($name, $value)
  {
    $cookiePath = parse_url($this->_manualUrl, PHP_URL_PATH);
    
    setcookie($name, $value, 0, $cookiePath);
  }
}

?>