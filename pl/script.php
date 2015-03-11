<?php
require_once '..\vendor\autoload.php';
require_once 'matrix.php';

// The service bus is on the Azure cloud platform
use WindowsAzure\Common\ServicesBuilder;
use WindowsAzure\Common\ServiceException;
use WindowsAzure\ServiceBus\models\BrokeredMessage;
use WindowsAzure\ServiceBus\models\ReceiveMessageOptions;

// Analyse sans sections
$ini_array = parse_ini_file("../config.ini");
$node = $ini_array['node'];
$connectionString = $ini_array['connectionString'];

// Create Service Bus REST proxy.
$GLOBALS["serviceBusRestProxy"] = ServicesBuilder::getInstance()->createServiceBusService($connectionString);

function sendMessage($topic, $body) {
	try {
	    // Create message.
	    $message = new BrokeredMessage();
	    $message->setBody("hello");
	    // Send message.
        $GLOBALS["serviceBusRestProxy"]->sendTopicMessage($topic, $message);
        echo "message sent!<br>";
	}
	catch(ServiceException $e){
        echo "here";
	    // Handle exception based on error codes and messages.
	    // Error codes and messages are here: 
	    // http://msdn.microsoft.com/fr-fr/library/windowsazure/hh780775
	    $code = $e->getCode();
	    $error_message = $e->getMessage();
	    echo $code.": ".$error_message."<br />";
	}
}

 function receiveMessage() {
     try {
         // Set the receive mode to PeekLock (default is ReceiveAndDelete).
         $options = new ReceiveMessageOptions();
         $options->setPeekLock();

         // Receive message.
         $message = $GLOBALS["serviceBusRestProxy"]->receiveSubscriptionMessage("morgan", "testsub", $options);
         if (method_exists($message, "getBody")) {
             echo "Body: ".$message->getBody()."<br />";
             echo "MessageID: ".$message->getMessageId()."<br />";

             /*---------------------------
                 Process message here.
             ----------------------------*/

             // Delete message. Not necessary if peek lock is not set.
             echo "Message deleted.<br />";
             $GLOBALS["serviceBusRestProxy"]->deleteMessage($message);
         } else {
             echo "no message found...<br>";
         }
     }
     catch(ServiceException $e){
         // Handle exception based on error codes and messages.
         // Error codes and messages are here:
         // http://msdn.microsoft.com/fr-fr/library/windowsazure/hh780735
         $code = $e->getCode();
         $error_message = $e->getMessage();
         echo $code.": ".$error_message."<br />";
     }
 }

function ProductLiveServiceBusListener() {
    for ($i = 1; $i <= 10; $i++) {
        echo "Try to receive a new message...<br>";
        receiveMessage();
        sleep(5);
    }
}

function sendMatrixFromMyIT() {
    $matrix = createTestMatrix();
	sendMessage("morgan", $matrix);
}

//sendMatrixFromMyIT();
//receiveMessage();
//$li = new ProductLiveServiceBusListener();
//$li->start();
$processes = array();
exec('tasklist', $processes );
print_r($processes);
echo createTestMatrix();

