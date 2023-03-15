<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class payeer {
	public $tb_transaction_logs;
	public $tb_payments;
	public $paypal;
	public $payment_type;
	public $payment_id;
	public $currency_code;
	public $payment_lib;
	public $mode;
	
	public $pm_merchant_id;
	public $pm_secret_key;
	public $currency_rate_to_usd;

	public function __construct($payment = ""){

		$this->payment_type		   = get_class($this);
		$this->currency_code       = "USD";
		if (!$payment) {
//			$payment = $this->model->get('id, type, name, params', $this->tb_payments, ['type' => $this->payment_type]);
		}
		$this->payment_id 	      = $payment->id;
		$params  			      = $payment->params;


	}


	/**
	 *
	 * Create payment
	 *
	 */
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

//	public function complete(){
//		if (!isset($_REQUEST['m_orderid']) || !isset($_REQUEST['m_shop']) || !isset($_REQUEST['m_amount']) || !isset($_REQUEST['m_status']) ) {
//			redirect(cn("add_funds"));
//		}
//		$m_key = $this->pm_secret_key;
//		$arHash = array(
//			$_REQUEST['m_operation_id'],
//			$_REQUEST['m_operation_ps'],
//			$_REQUEST['m_operation_date'],
//			$_REQUEST['m_operation_pay_date'],
//			$_REQUEST['m_shop'],
//			$_REQUEST['m_orderid'],
//			$_REQUEST['m_amount'],
//			$_REQUEST['m_curr'],
//			$_REQUEST['m_desc'],
//			$_REQUEST['m_status']
//		);
//
//		if (isset($_REQUEST['m_params'])) {
//			$arHash[] = $_REQUEST['m_params'];
//		}
//
//		$arHash[] = $m_key;
//		$sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));
//		$tx_order_id = strip_tags($_REQUEST['m_orderid']);
//		$transaction = $this->model->get('*', $this->tb_transaction_logs, ['transaction_id' => $tx_order_id, 'status' => 0, 'type' => $this->payment_type]);
//		if (!$transaction) {
//			redirect(cn("add_funds"));
//		}
//
//		if( $sign_hash == $_REQUEST['m_sign'] && $_REQUEST['m_status'] == 'success' && $transaction && $_REQUEST['m_shop'] == $this->pm_merchant_id ) {
//			$this->db->update($this->tb_transaction_logs, ['status' => 1],  ['id' => $transaction->id]);
//
//			// Update Balance
//    		require_once 'add_funds.php';
//    		$add_funds = new add_funds();
//    		$add_funds->add_funds_bonus_email($transaction, $this->payment_id);
//
//			set_session("transaction_id", $transaction->id);
//			redirect(cn("add_funds/success"));
//
//		} else {
//			$this->db->update($this->tb_transaction_logs, ['status' => -1],  ['id' => $transaction->id]);
//			redirect(cn("add_funds/unsuccess"));
//		}
//
//
//	}

}