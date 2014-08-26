<?php
class shangdb_model extends  CI_Model {
    var $data;
    public function __construct()
    {
        $this->load->database();
    }

    public function addRecord() 
    {    
    // //insert  
    
                                  
    //     *$userdata = array(
    //         'id'=>array(
    //             '10',
    //             '20',
    //             '30',
    //             '40'
    //             ),
    //         'username' => array(
    //             'shang',
    //             'xu',
    //             'hou',
    //             'qi'
    //          ),            
    //         'city' => array(
    //             'nanjing',
    //             'shanghai',
    //             'beijing',
    //             'hangzhou'
    //         )
    //     );
    //     $blogdata=array(
    //         'id'=>array(
    //             '10' ,
    //             '20',
    //             '30',
    //             '40'
    //         ),
    //         'bloginfo'=> array(
    //             'he',
    //             'llo' ,
    //             'wor',
    //             'ld'
    //          )
            
    //     );*
    //     $userdata = array(
    //         'id'=>
    //             '10',
               
                
    //         'username' => 
    //             'shang',
                
                      
    //         'city' => 
    //             'nanjing'
                
    //     );
    //     $blogdata=array(
    //         'bloginfo'=> 
    //             'he',
              
            
    //         'id'=>
    //             '10' 
               
    //         );
    //     $this->db->insert('userinfo', $userdata); 
    //     $str1 = $this->db->last_query();
    //     $this->db->insert('blog', $blogdata); 
    //     $str2 = $this->db->last_query();
    //     $new_id_number = $this->db->insert_id();       
    //     //join
        // $this->db->select('*');
        // $this->db->from('userinfo');
        // $this->db->join('blog', 'userinfo.id = blog.id'); 
        
        $query = $this->db->query('SELECT *  FROM `userinfo` WHERE `id` = 2');
        // var_dump($query);
        $result = $query->row();
        var_dump($result);
        
        return $result;
    }
        
        
         

}
?>
