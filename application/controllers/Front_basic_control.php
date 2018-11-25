<?php defined('BASEPATH') OR exit('No direct script access allowed');

///////////////////////////////////
//базовые методы
///////////////////////////////////

class Front_basic_control extends CI_Controller{
 protected $conf=array();//массив, куда будут записана конфигурация сайта и другие данные
 function __construct(){
  parent::__construct();
  $this->load->model('front_basic_model');
  $this->conf=$this->front_basic_model->get_config();
  $this->_is_site_access();
 }

 function _is_site_access(){//доступ к пользовательской части ресурса
  if($this->conf['conf_site_access']==='off'){//если в админке увтановлена опция "Доступ к сайту закрыт"
   $ip=$this->input->server('REMOTE_ADDR');//получаю текущий ip
   $q=$this->db->select('ip')->where('ip !=','')->get($this->_prefix().'back_users')->result_array();//выборка ip админа и модераторов
   !in_array($ip,array_unique(array_column($q,'ip')))?redirect('plug.html'):TRUE;//если текущий ip не админа или модератора - не пущать
  }
 }

 function _prefix(){//получение префикса таблиц базы данных из конфигурационного файла
  return $this->config->item('db_tabl_prefix');
 }

 function _viewer($url,$data){
  $data['front_menu_list']=$this->front_basic_model->get_menu();
  $data['data']=$data;
  $this->load->view('front/blocks/header_view',$data);
  $this->load->view($url,$data);
  $this->load->view('front/blocks/footer_view',$data);
 }

}