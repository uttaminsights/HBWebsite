<?php

	// All values come from config.php in the project root.
	require_once(__DIR__.'/../../admin/inc/config_loader.php');

	define('PAYTM_ENVIRONMENT', app_config('paytm_environment','TEST')); // TEST or PROD
	define('PAYTM_MERCHANT_KEY', app_config('paytm_merchant_key',''));
	define('PAYTM_MERCHANT_MID', app_config('paytm_merchant_mid',''));
	define('PAYTM_MERCHANT_WEBSITE', app_config('paytm_merchant_website','WEBSTAGING'));
	define('INDUSTRY_TYPE_ID', app_config('paytm_industry_type_id','Retail'));
	define('CHANNEL_ID', app_config('paytm_channel_id','WEB'));

	define('CALLBACK_URL', app_base_url().'pay_response.php');


	$PAYTM_STATUS_QUERY_NEW_URL='https://securegw-stage.paytm.in/merchant-status/getTxnStatus';
	$PAYTM_TXN_URL='https://securegw-stage.paytm.in/theia/processTransaction';

	if (PAYTM_ENVIRONMENT == 'PROD') {
		$PAYTM_STATUS_QUERY_NEW_URL='https://securegw.paytm.in/merchant-status/getTxnStatus';
		$PAYTM_TXN_URL='https://securegw.paytm.in/theia/processTransaction';
	}

	define('PAYTM_REFUND_URL', '');
	define('PAYTM_STATUS_QUERY_URL', $PAYTM_STATUS_QUERY_NEW_URL);
	define('PAYTM_STATUS_QUERY_NEW_URL', $PAYTM_STATUS_QUERY_NEW_URL);

	define('PAYTM_TXN_URL', $PAYTM_TXN_URL);

?>
