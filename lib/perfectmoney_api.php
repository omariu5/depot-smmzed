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
        if (!$amount) {
            return _redirect('back', __('There was an error processing your request please try again later'));
        }

        $amount = number_format($amount, 2, '.', ',');
        $order_id = $data_payment['order_id'];
        $perfectmoney = array(
            'PAYEE_ACCOUNT' 	=> $data_payment['payee_account'],
            'PAYEE_NAME' 		=> $data_payment['payee_name'],
            'PAYMENT_UNITS' 	=> $data_payment['currency'],
            'STATUS_URL' 		=> cn("add_funds/perfectmoney/complete"),
            'PAYMENT_URL' 		=> cn("add_funds/perfectmoney/complete"),
            'NOPAYMENT_URL' 	=> cn("add_funds/perfectmoney/unsuccess"),
            'BAGGAGE_FIELDS' 	=> 'IDENT',
            'ORDER_NUM' 		=> $order_id,
            'PAYMENT_ID' 		=> strtotime(now()),
            'CUST_NUM' 		    => "USERID" . rand(10000,99999999),
            'memo' 		        => $data_payment['description'],

        );
        $tnx_id = $perfectmoney['PAYMENT_ID'].':'.$perfectmoney['PAYEE_ACCOUNT'].':'. $amount.':'.$perfectmoney['PAYMENT_UNITS'];
        $tnx_id = sha1($tnx_id);
        $data_tnx_log = array(
            "ids" 				=> ids(),
            "uid" 				=> csrf_token(),
            "type" 				=> 'perfectmoney',
            "transaction_id" 	=> 'ORD-'.$data_payment['order_id'],
            "amount" 	        => $amount,
            'txn_fee'           => $amount * ($data_payment['payment_fee'] / 100),
            "status" 	        => 0,
            "created" 			=> now(),
            "perfectmoney"  => (object)$perfectmoney,
        );
//        $this->load->view("perfectmoney/redirect", $data_tnx_log);

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
