<?php

//Payu integration
PayU::$apiKey = "jmMjAs2tZVZxe263801Mc6m73V"; //Ingrese aquí su propio apiKey.
PayU::$apiLogin = "mgdsFoMyN66BfM2"; //Ingrese aquí su propio apiLogin.
PayU::$merchantId = "675624"; //Ingrese aquí su Id de Comercio.
PayU::$language = SupportedLanguages::ES; //Seleccione el idioma.
PayU::$isTest = true; //Dejarlo True cuando sean pruebas.
// URL de Pagos
Environment::setPaymentsCustomUrl("https://sandbox.api.payulatam.com/payments-api/4.0/service.cgi");
// URL de Consultas
Environment::setReportsCustomUrl("https://sandbox.api.payulatam.com/reports-api/4.0/service.cgi");
// URL de Suscripciones para Pagos Recurrentes
Environment::setSubscriptionsCustomUrl("https://sandbox.api.payulatam.com/payments-api/rest/v4.3/");
?>