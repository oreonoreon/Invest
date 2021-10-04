<?php

namespace application\models;

use \application\core\Model;

class Main extends Model{
    public function getNews() {
        //$result= $this->db->row('SELECT uid, sumin FROM tariffs');
        $result= $this->db->row('SELECT login, sumin, sumout
      FROM tariffs INNER JOIN accounts  
	     ON tariffs.uid = accounts.id ORDER BY tariffs.unixTimeStart DESC');
        return $result;
    }  
    public function getNews1() {
        //$result= $this->db->row('SELECT uid, sumin FROM tariffs');
        $result= $this->db->row('SELECT login, sumin, percent FROM tariffs INNER JOIN accounts ON tariffs.uid = accounts.id WHERE sumout=0 ORDER BY tariffs.unixTimeFinish DESC');
        return $result;
    }
    
    public function getNews2() {
        $result= $this->db->row('SELECT id FROM accounts ORDER BY ID DESC LIMIT 1');
        
        return $result;
    } 
    
    public function getNews3() {
        //$result= $this->db->row('SELECT descriptionfordull FROM history');
        $result= $this->db->row('SELECT login, descriptionfordull FROM history INNER JOIN accounts ON history.uid = accounts.id WHERE descriptionfordull LIKE "%реферального%"');
        
        return $result;
    } 
}


