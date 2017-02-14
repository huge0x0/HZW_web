<?php

/**
 * Created by PhpStorm.
 * User: huge
 * Date: 2017/2/11
 * Time: 13:34
 */
class Character_input extends CI_Controller
{
    //用于临时存储部首信息
    var $radical = null;

    public function __construct(){
        parent::__construct();
        $this->load->model('Character_model');
        $this->load->model('Radical_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    public function index(){
        $this->form_validation->set_rules('character', 'character', 'trim|required', array('required' => '请输入汉字字形'));
        $this->form_validation->set_rules('pinyin', 'pinyin', 'trim|required', array('required' => '请输入汉字拼音'));
        $this->form_validation->set_rules('words', 'words', 'trim|required', array('required' => '请输入例词'));
        $this->form_validation->set_rules('sentence', 'sentence', 'trim|required', array('required' => '请输入例句'));
        $this->form_validation->set_rules('explanation', 'explanation', 'trim|required', array('required' => '请输入汉字释义'));
        $this->form_validation->set_rules('radical', 'radical', 'trim|required|callback_radical_check');

        $result=false;
        if($this->form_validation->run()){
            $data=$this->input->post();
            $data['radical_id']=$this->radical['ID'];
            $result=$this->Character_model->insert_new_character($data);
        }
        $this->load->view('character_input',array('result'=>$result));
    }

    /*未完成，也许以后会用到
    public function char_check($character_shape){
        $character=$this->Character_model->get_character_by_shape($character_shape);
        if($character==null){
            $this->form_validation->set_message('character','此汉字已经存在');
        }
    }*/

    public function radical_check($radical_shape){
        if(empty($radical_shape)){
            $this->form_validation->set_message('radical_check', '请输入部首');
            return false;
        }
        $radical=$this->Radical_model->get_radical_by_shape($radical_shape);
        if($radical==null){
            $this->form_validation->set_message('radical_check', '该部首还没有录入库中');
            return false;
        }
        else{
            $this->radical=$radical;
            return true;
        }
    }
}