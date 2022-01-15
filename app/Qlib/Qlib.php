<?php
namespace App\Qlib;

class Qlib
{
  public function lib_print($data){
      if(is_array($data) || is_object($data)){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
      }else{
        echo $data;
      }
  }
  public function dtBanco($data) {
			$data = trim($data);
			if (strlen($data) != 10)
			{
				$rs = false;
			}
			else
			{
				$arr_data = explode("/",$data);
				$data_banco = $arr_data[2]."-".$arr_data[1]."-".$arr_data[0];
				$rs = $data_banco;
			}
			return $rs;
	}
  public function dataExibe($data) {
			$val = trim(strlen($data));
			$data = trim($data);$rs = false;
			if($val == 10){
					$arr_data = explode("-",$data);
					$data_banco = $arr_data[2]."/".$arr_data[1]."/".$arr_data[0];
					$rs = $data_banco;
			}
			if($val == 19){
					$arr_inic = explode(" ",$data);
					$arr_data = explode("-",$arr_inic[0]);
					$data_banco = $arr_data[2]."/".$arr_data[1]."/".$arr_data[0];
					$rs = $data_banco."-".$arr_inic[1] ;
			}

			return $rs;
	}
  public function lib_json_array($json=''){
		$ret = false;
		if(is_array($json)){
			$ret = $json;
		}elseif(!empty($json) && Qlib::isJson($json)&&!is_array($json)){
			$ret = json_decode($json,true);
		}
		return $ret;
	}
	public function lib_array_json($json=''){
		$ret = false;
		if(is_array($json)){
			$ret = json_encode($json,JSON_UNESCAPED_UNICODE);
		}
		return $ret;
	}
  public function isJson($string) {
		$ret=false;
		if (is_object(json_decode($string)) || is_array(json_decode($string)))
		{
			$ret=true;
		}
		return $ret;
	}
}
