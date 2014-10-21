<?php
/**
* 重构Model实现更全的操作 
* @author bzhao
*/

class MY_Model extends CI_Model
{
    /**
     * 表名称
     * 
     * @var string
     */
    var $table_name = "prefix_user";
 
    /**
     * 主键名称
     *
     * @var string
     */
    protected $primary_key = 'uid';
 
    /**
     * 软删除标记字段名称，当为空时，不启用软删除功能
     *
     * @var string
     */
    protected $disabled = NULL;
 
    /**
     * 更新时间字段名称
     *
     * @var string
     */
    protected $updated = 'updated';
 
    /**
     * 创建时间字段名称
     *
     * @var string
     */
    protected $created = 'created';
 
    /**
     * 是否允许自动维护更新时间字段的值
     *
     * @var bool
     */
    protected $allow_updated = TRUE;
 
    /**
     * 是否允许自动维护创建时间字段的值
     *
     * @var bool
     */
    protected $allow_created = TRUE;
 
    /**
     * 表字段信息
     *
     * @var array
     */
    protected $fields = NULL;
 
    /**
     * LIMIT 默认值
     *
     * @var int
     */
    const DEFAULT_LIMIT = 20;
        
    public function __construct()
    {
        
         /*表名为空或不存在异常*/
        // if (empty($this->table_name) 
        //      OR ! in_array($this->table_name ,$this->db->list_tables()))
        // {
        // show_error((sprintf('未找到名称为 "%s" 的 表名', 
        //             $this->table_name)));        
        // } 

        // /*判断主键是否有效*/
        // if (empty($this->primary_key)
        //     OR ! $this->is_field($this->primary_key)
        //     OR ! $this->is_pk())
        // {
        //     show_error((sprintf('表名"%s"的主键设置错误 "%s" 无效, 请查实', 
        //                  $this->table_name,
        //                  $this->primary_key)));               
        // }  
    
        // /*自动写入update,created时间*/
        // $this->allow_updated = TRUE;
        // $this->allow_created = TRUE;
            
    }
        
    /**
     * 判断字段是否存在
     *
     * @param string $field  字段名
     * @return boolean
     */
    protected function is_field($field)
    {
        return $this->db->field_exists($field ,$this->table_name);
    }
 
 
    /**
     * 判断是否为主键
     * @return boolen
     */
    protected function is_pk()
    {
        $fielddata = $this->db->field_data($this->table_name);
        
        foreach ($fielddata as $field) 
        {
                    if ($field->name == $this->primary_key)
                    {
                        return $field->primary_key;
                    }
        }
        
        return FALSE;
    }
 
    /**
     * 创建记录
     * @param array $record  记录数组
     * @return int 成功返回insert_id
     *         false 失败
     * @example
     * <code>
     * $record = array
     * ('title'        =>        'new');
     * $this->test_model->add($record);
     * </code><br/>
     */
    function add($record,$table,$key_name,$key_value)
    {

        $datetime = date('Y-m-d H:i:s');

        /*是否存在软删除字段*/
        if ( ! empty($this->disabled) && ! isset($record[$this->disabled]))
        {
            $record[$this->disabled] = 0;
        }

       
        //判断：执行更新操作或插入操作
        $query = $this->db->get_where($table,
                                      array($key_name=>$key_value));

        //插入操作
        if($query->num_rows == 0)
        {
            //创建时间
            if ($this->allow_created 
                                && ! empty($this->created) 
                                && empty($record[$this->created]))
            {
                $record[$this->created] = $datetime;                       
            }
             /*set数据*/
            foreach ($record as $key => $value)
            {
                if ($this->is_field($key))                       
                    $this->db->set($key,$value);
            }
                    
            if ( ! $this->db->insert($table))
            {
                return FALSE;
            }
            /*返回执行数据插入时的ID*/
            $key_value = $this->db->insert_id();
            return $key_value;

        }

        //更新操作
        elseif ($query->num_rows == 1) 
        {
            /*删除主键*/
            unset($record[$key_name]);

            if ($this->allow_updated 
                                && ! empty($this->updated) 
                                && empty($record[$this->updated]))
            {
                $record[$this->updated] = $datetime;
            }
            

             /*set数据*/
            foreach ($record as $key => $value)
            {
                if ($this->is_field($key))
                    $this->db->set($key,$value);
            }
            $this->db->where($key_name,$key_value);
            if ( ! $this->db->update($table))
            {
                return FALSE;
            }

        }                
    }    
 
    /**
     * 开启或关闭更新时间字段的自动维护功能
     *
     * @param bool $allow
     * @return bool
     */
    public function allow_updated($allow)
    {
        return $this->allow_updated = (boolean)$allow;
    }
 
    /**
     * 开启或关闭创建时间字段的自动维护功能
     *
     * @param bool $allow
     * @return bool
     */
    public function allow_created($allow)
    {
        return $this->allow_created = (boolean)$allow;
    }

    function insert_update($table,$id_name,$id_value,$data = NULL)
    {
        $query = $this->db->get_where($table,array($id_name=>$id_value));
        
        if($query->num_rows == 1)
        {
            $this->db->where($id_name,$id_value);
            $this->db->update($table,$data);
        }
        elseif ($query->num_rows == 0) 
        {
            $this->db->insert($table,$data);
        }  
    }
 
   /**
    * 封装$this->db的查询
    * @param object $this->db
    * @return object $this->db
    * @example
    * <code>
    * array
    * (
    * 'where' => 
    *  array(
    * 'name like' => 'Your',
    * 'and id >=' => 1,
    * 'or name notlike' => 'Your',
    * ),
    * 'orderby' => array(),
    * );
    * </code>
    */

    final protected function confine($orm, $query_struct = array())
    {
            /*处理查询结构体*/
        if (is_array($query_struct))
        {
            if ( ! empty($this->disabled))
            {
                if ( ! isset($query_struct['where']))
                {
                    $query_struct['where'] = array();
                }
                if ( ! isset($query_struct['where'][$this->disabled]))
                {
                    $query_struct['where'][$this->disabled] = 0;
                }
            }
 
 
 
             /*Where条件的逻辑明儿搞*/           
            if ( ! empty($query_struct['where']) AND is_array($query_struct['where']))
            {
                    return $orm;
                     
                foreach ($query_struct['where'] as $key => $value)
                {
                    $key = trim($key);
 
                    /**/
                    if (is_numeric($key) OR empty($key))
                    {
                        $orm->where($this->primary_key,$value);
                    } 
                    else 
                    {
                        // 过滤连续空格
                        $key = preg_replace('/s+/', ' ', $key);
                        $key = explode(' ', $key);
 
                        $relation = '';
                        switch (strtoupper($key[0]))
                        {
                            case 'AND':
                                break;
                            case 'OR':
                                $relation = 'or_';
                                break;
                            default:
                                array_unshift($key, '');
                        }
 
                        if (is_array($value))
                        {    
                                if (strtoupper($key[2]) !== 'NOTIN')
                                        $key[2] = 'in';
                                else         
                                {
                                        $key[2] = 'not in';
                                }
                        }
 
                        $type = 'where';
 
                        $key[2] = (isset($key[2]) AND ! empty($key[2])) ? $key[2] :'=';
 
                        /*构建我们的where*/
                        $orm->{$relation.$type}($key[1],$key[2],$value);
 
 
                    }
                }
            }
 
            /*orderby 处理*/
            if ( ! empty($query_struct['orderby']))
            {
                    if ( ! is_array($query_struct['orderby']))
                            $orm->order_by($query_struct['orderby']);
                    else
                    {
                            $query_struct['orderby'][1] = isset($query_struct['orderby'][1]) 
                                                            ? $query_struct['orderby'][1] : 'ASC';
                            $orm->order_by($query_struct['orderby'][0],$query_struct['orderby'][1]);
                    }
            }
 
            if ( ! empty($query_struct['limit']))
            {
                $limit = ! is_array($query_struct['limit'])
                       ? explode(',', $query_struct['limit'])
                       : $query_struct['limit'];
                        
                /*加载分页的设计*/
                if (isset($limit['per_page']))
                    $limit[0] = $limit['per_page'];
                if (isset($limit['offset']))
                    $limit[1] = $limit['offset'];
                if (isset($limit[0]) AND isset($limit[1]))
                {
                    $orm->limit(intval($limit[0]),intval($limit[1]));
                }
                elseif (isset($limit[0]))
                    $orm->limit(intval($limit[0]));
                else
                    $orm->limit(self::DEFAULT_LIMIT);
            }
        }
 
        return $orm;
    }
 
    /**
     * 执行查询
     * @param $this->db->select $dbselect  
     * @param array $query_struct 查询结构体
     * @param string $key  结果集主键
     */
    function db_find_all($select = array(),
                         $query_struct = array() , 
                         $key = NULL)
    {
        if ( ! empty($key) && ! $this->is_field($key))
        show_error((sprintf('表 "%s" 中未找到字段 "%s"', $this->table_name, $key)));
 
            
        /*加载select条件*/    
                self::_dbselect($select);       
 
        $records = array();        
            
        /*执行结构体返回数据*/
            $query = $this->confine($this->db ,$query_struct)->get($this->table_name);
            
            $result = $query->result_array();
                
            $query->free_result();
            
        /*根据主键返回*/
        if( ! empty($key))
        {
            foreach($result as $record)
            {
                $records[$record[$key]] = $record;
            }
        }
        /*根据直接返回*/        
        else
        {
                   foreach($result as $record)
            {
                $records[] = $record;
            }
        }
 
        return $records;
    }    
 
    /**
     * 改造select
     * @param array $columns
     */
    protected function _dbselect($columns = array())
    {
            if (count($columns))
            {
                    foreach ($columns as $key) 
                        {
                                if ( ! empty($key) && ! $this->is_field($key))
                            show_error(sprintf('表 "%s" 中未找到字段 "%s"', $this->table_name,$key));
                                
                    $this->db->select($key);                            
                        }
            }
            /*默认全表*/
    }    
 
}
 
/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php*/
