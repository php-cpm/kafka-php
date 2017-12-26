<?php
require '../vendor/autoload.php';
date_default_timezone_set('PRC');
use Monolog\Logger;
use Monolog\Handler\StdoutHandler;
use Kafka\ProducerConfig;
use Kafka\Config;
use Kafka\Producer;

// Create the logger
$logger = new Logger('my_logger');
// Now add some handlers
$logger->pushHandler(new StdoutHandler());

$config = ProducerConfig::getInstance();
$config->setMetadataRefreshIntervalMs(10000);
$config->setMetadataBrokerList('127.0.0.1:9093');
$config->setBrokerVersion('1.0.0');
$config->setRequiredAck(1);
$config->setIsAsyn(false);
$config->setProduceInterval(500);
$config->setSecurityProtocol(Config::SECURITY_PROTOCOL_SASL_SSL);
$config->setSaslMechanism(\Kafka\Config::SASL_MECHANISMS_SCRAM_SHA_256);
$config->setSaslUsername('nmred');
$config->setSaslPassword('123456');
$config->setSaslUsername('alice');
$config->setSaslPassword('alice-secret');
$config->setSaslKeytab('/etc/security/keytabs/kafkaclient.keytab');
$config->setSaslPrincipal('kafka/node1@NMREDKAFKA.COM');

// if use ssl connect
$config->setSslLocalCert('/home/vagrant/code/kafka-php/ca-cert');
$config->setSslLocalPk('/home/vagrant/code/kafka-php/ca-key');
$config->setSslPassphrase('123456');
$config->setSslPeerName('nmred');

$producer = new Producer(function () {
    return [
        [
            'topic' => 'test',
            'value' => 'test....message.',
            'key' => '',
        ],
    ];
});
$producer->setLogger($logger);
$producer->success(function ($result) {
    var_dump($result);
});
$producer->error(function ($errorCode) {
    var_dump($errorCode);
});
$producer->send(true);
