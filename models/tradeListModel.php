<?php
class commodityVisitTrade_model extends  CI_Model {
    public function __construct()
    {
        $this->load->database();
        $this->load->library('PaiPaiOpenApiOauth');
    }

    public function addRecord() {
        try {
            $response = $this->invoke();
            $commodity = simplexml_load_string($response);
            } catch(Exception $e) {
            printf("Request Failed. code:%d, msg:%s\n",$e->getCode(), $e->getMessage());
        }
        if (empty($commodity)) return;

        $arr_tradeList = $commodity->commodityVisitTradeList->xpath('commodityVisitTradePo');       
        $sql = "create table xt_tradeList(test text);";
        mysqli_query($db, $sql);
        $column  = array();
        $results = array();
        $incre = 0;

        $this->db->insert('xt_tradeList', $arr_tradeList);
        
        $insertTime = date('Y-m-d H:i:s');
        $column[$incre] = 'insertTime';
        $Values[$incre] = "'".$insertTime."'";
        $dbColumn = implode($column, ',');
        $dbValue  = implode($Values, ',');
        $sql = "insert into xt_tradeList ($dbColumn) values ($dbValue);";
        $result[$i] = mysqli_query($db, $sql);
    }
    print_r($result);
    return $result;
}
?>
