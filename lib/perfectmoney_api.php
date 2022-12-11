<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class perfectmoney_api{

	public function __construct() {

		if (!defined('PATH_TO_LOG')) {
		    define("PATH_TO_LOG", PATH);
		}
	}

	public function check_v2_hash($perfectmoney_alternate_passphrase = ""){
		$alternate_passphrase = strtoupper(md5($perfectmoney_alternate_passphrase));;
		$string= $_POST['PAYMENT_ID'].':'.$_POST['PAYEE_ACCOUNT'].':'. $_POST['PAYMENT_AMOUNT'].':'.$_POST['PAYMENT_UNITS'].':'. $_POST['PAYMENT_BATCH_NUM'].':'. $_POST['PAYER_ACCOUNT'].':'.$alternate_passphrase.':'. $_POST['TIMESTAMPGMT'];
		$hash = strtoupper(md5($string));
		if ($hash == $_POST['V2_HASH']) {
			return true;
		}else{
			return false;
		}

	}

	public function verify_transaction_using_api($perfectmoney_member_id = "", $perfectmoney_password = ""){
		$f = fopen('https://perfectmoney.com/acct/historycsv.asp?AccountID='.$perfectmoney_member_id.'&PassPhrase='.$perfectmoney_password.'&startmonth='.date("m", $_POST['TIMESTAMPGMT']).'&startday='.date("d", $_POST['TIMESTAMPGMT']).'&startyear='.date("Y", $_POST['TIMESTAMPGMT']).'&endmonth='.date("m", $_POST['TIMESTAMPGMT']).'&endday='.date("d", $_POST['TIMESTAMPGMT']).'&endyear='.date("Y", $_POST['TIMESTAMPGMT']).'&paymentsreceived=1&batchfilter='.$_POST['PAYMENT_BATCH_NUM'], 'rb');
		if($f === false) return false;

		$lines = array();
		while(!feof($f)) array_push($lines, trim(fgets($f)));
		fclose($f);
		if($lines[0] != 'Time,Type,Batch,Currency,Amount,Fee,Payer Account,Payee Account,Payment ID,Memo'){
			return false;
		}else{
		 	$ar = array();
		 	$n = count($lines);
		 	if($n != 2) return false;
		 	$item = explode(",", $lines[1], 10);
		 	if(count($item) != 10) return 'invalid API output';
		 	$item_named['Time']				=	$item[0];
		 	$item_named['Type']				=	$item[1];
		 	$item_named['Batch']			=	$item[2];
		 	$item_named['Currency']			=	$item[3];
		 	$item_named['Amount']			=	$item[4];
		 	$item_named['Fee']				=   $item[5];
		 	$item_named['Payer Account']	=	$item[6];
		 	$item_named['Payee Account']	=	$item[7];
		 	$item_named['Payment ID']		=	$item[8];
		 	$item_named['Memo']				=	$item[9];

		 	if($item_named['Batch'] == $_POST['PAYMENT_BATCH_NUM'] && $_POST['PAYMENT_ID'] == $item_named['Payment ID'] && $item_named['Type'] == 'Income' && $_POST['PAYEE_ACCOUNT'] == $item_named['Payee Account'] && $_POST['PAYMENT_AMOUNT'] == $item_named['Amount'] && $_POST['PAYMENT_UNITS'] == $item_named['Currency'] && $_POST['PAYER_ACCOUNT'] == $item_named['Payer Account']){
		 		return true;
		 	}else{
				return false;
		 	}
		}

	}

    public function create_payment($data_payment = ""){
        $amount = $data_payment['amount'];

        if (!$data_payment['pm_merchant_id'] || !$data_payment['pm_secret_key']) {
            $result = (object)array(
                'status'  => 'error',
                'message' => "Invalid Gateway Credentials",
            );
            return $result;
        }

        $m_shop    = $data_payment['pm_merchant_id'];
        $m_orderid = "ORDS" . $data_payment['order_id'];
        $m_amount  = number_format($amount, 2, '.', '');
        $m_curr    = $data_payment['currency'];
        $m_desc    = base64_encode($data_payment['description']);


        $m_key     = $data_payment['pm_secret_key'];
        $arHash = array(
            $m_shop,
            $m_orderid,
            $m_amount,
            $m_curr,
            $m_desc
        );

        $arHash[] = $m_key;
        $sign = strtoupper(hash('sha256', implode(':', $arHash)));

        $paramList = [
            "m_shop" 		         => $m_shop,
            "m_orderid" 			 => $m_orderid,
            "m_amount" 	             => $m_amount,
            "m_curr" 	             => $m_curr,
            'm_desc'                 => $m_desc,
            'm_sign'                 => $sign,
        ];

        $data_redirect = [
            "action_url" 	         => 'https://payeer.com/merchant/',
            "paramList" 	         => $paramList,
        ];
        /*----------  Insert Transaction logs  ----------*/
        $data_tnx_log = array(
            "status" => 'success',
            "type" 				=> 'payeer',
            "transaction_id" 	=> $m_orderid,
            "amount" 	        => $amount ,
            'txn_fee'           => round($amount * ($data_payment['payment_fee'] / 100), 4),
            "created" 			=> now(),
            "redirect_url"      => $data_payment['redirectUrls'],
            "action_url" 	         => 'https://payeer.com/merchant/',
            "paramList" 	         => $paramList,
        );
        return (object) $data_tnx_log;
    }

//    public function complete(){
//        if (!isset($_REQUEST['m_orderid']) || !isset($_REQUEST['m_shop']) || !isset($_REQUEST['m_amount']) || !isset($_REQUEST['m_status']) ) {
//            redirect(cn("add_funds"));
//        }
//        $m_key = $this->pm_secret_key;
//        $arHash = array(
//            $_REQUEST['m_operation_id'],
//            $_REQUEST['m_operation_ps'],
//            $_REQUEST['m_operation_date'],
//            $_REQUEST['m_operation_pay_date'],
//            $_REQUEST['m_shop'],
//            $_REQUEST['m_orderid'],
//            $_REQUEST['m_amount'],
//            $_REQUEST['m_curr'],
//            $_REQUEST['m_desc'],
//            $_REQUEST['m_status']
//        );
//
//        if (isset($_REQUEST['m_params'])) {
//            $arHash[] = $_REQUEST['m_params'];
//        }
//
//        $arHash[] = $m_key;
//        $sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));
//        $tx_order_id = strip_tags($_REQUEST['m_orderid']);
//        $transaction = $this->model->get('*', $this->tb_transaction_logs, ['transaction_id' => $tx_order_id, 'status' => 0, 'type' => $this->payment_type]);
//        if (!$transaction) {
//            redirect(cn("add_funds"));
//        }
//
//        if( $sign_hash == $_REQUEST['m_sign'] && $_REQUEST['m_status'] == 'success' && $transaction && $_REQUEST['m_shop'] == $this->pm_merchant_id ) {
//            $this->db->update($this->tb_transaction_logs, ['status' => 1],  ['id' => $transaction->id]);
//
//            // Update Balance
//            require_once 'add_funds.php';
//            $add_funds = new add_funds();
//            $add_funds->add_funds_bonus_email($transaction, $this->payment_id);
//
//            set_session("transaction_id", $transaction->id);
//            redirect(cn("add_funds/success"));
//
//        } else {
//            $this->db->update($this->tb_transaction_logs, ['status' => -1],  ['id' => $transaction->id]);
//            redirect(cn("add_funds/unsuccess"));
//        }
//
//
//    }

}
