<?php
namespace Mocal\Bundle\ExperianBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * NewsLetter controller.
 *
 */
class ExperianController extends Controller
{

    /**
     * @Route("/test", name="ctct")
     */
    public function ctct()
    {
        $experian = $this->get('mocal.experian.manager');

        //$news = $experian->getNewsletter("7897");
        $res = $experian->audit("7897");
        /*$res = $experian->create(
            "Alerta Urgente",
            "<html>
            <head>
                <title></title>
            </head>
            <body>
            Esto es un newsletter urgente.
            </body>
            </html>{[opt-out-tablaAPI|13502]}"
        );*/
        /*$res = $experian->createNewsletter(
            "Alerta Urgente",
            "<html><head><title></title></head><body>Esto es una alerta urgente</br></body></html>{[opt-out-tablaAPI|13502]}"
        );*/

        print_r($res);
        die;




        $clientId = "17539";
        $consumerKey = "MTc1Mzk6MTAwMjQ5";
        $consumerSecret = "cd91f919a5fd40b0b1c08d8f8eaac72c";

        // Request Header
        $headers = array(
            "grant_type" => "password",
            "client_id" => $clientId,
            "username" => $consumerKey,
            "password" => $consumerSecret,
            "content_type" => "application/x-www-form-urlencoded"
        );
        $postText = http_build_query($headers);

        // URL to call
        $url = "https://api.ccmp.eu/services/authorization/oauth2/token";
        try {
            // Initialize a Curl session
            $curl = curl_init();
            // Set up Curl sending options
            // The URL to call
            curl_setopt($curl, CURLOPT_URL, $url);

            // Set the HTTP method. POST here.
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postText);

            // Get the result by string
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            // Curl session execution
            $result = curl_exec($curl);
            // Get the request return code
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            // Close the Curl session
            curl_close($curl);

            // Set the returned array.
            $res = array_merge(array('code' => $code), array('result' => json_decode($result, true)));

            $token = $res['result']['access_token']; // este debería ser el token de acceso

        } catch ( Exception $e ) {
            throw new Exception("Error during the call. Here the message: " . $e -> Message);
        }

        //***********GET*****************

        /*$campId = "7897";

        // Your access token (please refer to the Get Started documentation
        //$token = $token;
        // URL to request your record with your params
        $url = "https://api.ccmp.eu/services2/api/EmailCampaign?id=" . $campId;
        try {// Set header : a table containing the header params
            $headers = array('Accept: application/json', 'Content-Type: application/json', 'Authorization: Bearer ' . $token);
            // Initialize a Curl session
            $curl = curl_init();
            // Set up Curl sending options
            // The URL to call
            curl_setopt($curl, CURLOPT_URL, $url);
            // Set up the curl header
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            // Get the result by string
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // Set the HTTP method. PUT here.
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
            // Curl session execution
            $result = curl_exec($curl);
            // Get the request return code
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            // Close the Curl session
            curl_close($curl);
            // Set the returned array.
            $res = array_merge(array('code' => $code), array('result' => json_decode($result, true)));

            print_r($res);

        } catch ( Exception $e ) {
            throw new Exception("Error during the call. Here the message: " . $e -> Message);
        }

        die;*/

        //**************FIN GET************************

        //***********CREATE*****************

        $emailCampaign = array(
            "CampName" => "Envio Urgente 2",
            "CustId" => "100249",
            "EntityId" => "167",
            "TypeId" => "REGULAR",
            "ToFilterId" => "52686",
            "EmailMsgTemplate" => array(
                "FromName" => "El Mundo",
                "ToName" => "{(nombre)}",
                "ToAddressPropId" => "11542",
                "FromAddressId" => "8",
                "Subject" => "Urgente: Newsleeter de alerta",
                "CodePageId" => "65001",
                "VmtaPoolId" => "100359",
                "ProofSubjectPrefix" => "PROOF"
            ),
            "CampParam" => array(
                "shortenLinksFlag" => "0",
                "carryOverToNextDayFlag" => "1",
                "stopNightDeliveryFlag" => "0",
                "ignoreConfirmationFlag" => "0",
                "sendSchedule" => array(
                    "timeZone" => "W_Europe_Standard_Time",
                    "dayFrequency" => array(
                        "frequencyType" => "Daily",
                        "daysInterval" => "1"
                    )
                ),
                "queueSchedule" => array("timeZone" => "W_Europe_Standard_Time")
            ),
            "linkTrackingDomainId" => "5359",
            "linkTrackingUsageMask" => "HTML_AND_TEXT",
            "CampReviewFlags" => array(
                "ContCalculationFlag" => "0",
                "PersonalizationFlag" => "0",
                "SendingFlag" => "1"
            ),
            "Obj" => array(
                "display_name" => "Envio_Urgente_2",
                "type_id" => "CampaignEmail",
                "parent_obj_id" => "20174",
                "eligibility_status_id" => "READY"
            ),
            "contBodies" => array(
                array(
                    "type" => "HTML",
                    "usageMask" => "ALL_EMAIL_STYLE_USAGE_MASK",
                    "body" => "<html><head><title></title></head><body>Esto es una alerta urgente</br></body></html>{[opt-out-tablaAPI|13502]}"
                )
            ),
            "CampMetaParams" => array(
                array("optionId" => "4"),
                array("optionId" => "5"),
                array("optionId" => "8"),
                array("optionId" => "9"),
                array("optionId" => "10"),
            )
        );
        //You campaig content
        $json = json_encode($emailCampaign);
        // Your access token (please refer to the Get Started documentation
        //$token = $token;
        // URL to request your record with your params
        $url = "https://api.ccmp.eu/services2/api/EmailCampaign";
        try {// Set header : a table containing the header params
            $headers = array('Accept: application/json', 'Content-Type: application/json', 'Authorization: Bearer ' . $token);
            // Initialize a Curl session
            $curl = curl_init();
            // Set up Curl sending options
            // The URL to call
            curl_setopt($curl, CURLOPT_URL, $url);
            // Set up the curl header
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            // Get the result by string
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // Set the HTTP method. PUT here.
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
            // Curl session execution
            $result = curl_exec($curl);
            // Get the request return code
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            // Close the Curl session
            curl_close($curl);
            // Set the returned array.
            $res = array_merge(array('code' => $code), array('result' => json_decode($result, true)));

            print_r($res);

            $campId = $res['result']['campId'];

        } catch ( Exception $e ) {
            throw new Exception("Error during the call. Here the message: " . $e -> Message);
        }

        $emailCampaign = array(
            "CampId" => $campId,
            "CustId" => "100249",
            "EntityId" => "167",
            "CampAction" => "LAUNCH"
        );
        //You campaig content
        $json = json_encode($emailCampaign);
        // Your access token (please refer to the Get Started documentation
        //$token = $token;
        // URL to request your record with your params
        $url = "https://api.ccmp.eu/services2/api/EmailCampaign?id=" . $campId;
        try {// Set header : a table containing the header params
            $headers = array('Accept: application/json', 'Content-Type: application/json', 'Authorization: Bearer ' . $token);
            // Initialize a Curl session
            $curl = curl_init();
            // Set up Curl sending options
            // The URL to call
            curl_setopt($curl, CURLOPT_URL, $url);
            // Set up the curl header
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            // Get the result by string
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // Set the HTTP method. PUT here.
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
            // Curl session execution
            $result = curl_exec($curl);
            // Get the request return code
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            // Close the Curl session
            curl_close($curl);
            // Set the returned array.
            $res = array_merge(array('code' => $code), array('result' => json_decode($result, true)));

            print_r($res);

        } catch ( Exception $e ) {
            throw new Exception("Error during the call. Here the message: " . $e -> Message);
        }

        $emailCampaign = array(
            "CampId" => $campId,
            "CustId" => "100249",
            "EntityId" => "167",
            "CampAction" => "APPROVE"
        );
        //You campaig content
        $json = json_encode($emailCampaign);
        // Your access token (please refer to the Get Started documentation
        //$token = $token;
        // URL to request your record with your params
        $url = "https://api.ccmp.eu/services2/api/EmailCampaign?id=" . $campId;
        try {// Set header : a table containing the header params
            $headers = array('Accept: application/json', 'Content-Type: application/json', 'Authorization: Bearer ' . $token);
            // Initialize a Curl session
            $curl = curl_init();
            // Set up Curl sending options
            // The URL to call
            curl_setopt($curl, CURLOPT_URL, $url);
            // Set up the curl header
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            // Get the result by string
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // Set the HTTP method. PUT here.
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
            // Curl session execution
            $result = curl_exec($curl);
            // Get the request return code
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            // Close the Curl session
            curl_close($curl);
            // Set the returned array.
            $res = array_merge(array('code' => $code), array('result' => json_decode($result, true)));

            print_r($res);

        } catch ( Exception $e ) {
            throw new Exception("Error during the call. Here the message: " . $e -> Message);
        }

        die;

        //**************FIN CREATE************************

        //***********PAUSE*****************

        /*$campId = "7897";
        //The campaign ID (Integer)
        $emailCampaign = array(
            "CampId" => "7897",
            "CustId" => "100249",
            "EntityId" => "167",
            "CampAction" => "PAUSE"
        );
        //You campaig content
        $json = json_encode($emailCampaign);
        // Your access token (please refer to the Get Started documentation
        //$token = $token;
        // URL to request your record with your params
        $url = "https://api.ccmp.eu/services2/api/EmailCampaign?id=" . $campId;
        try {// Set header : a table containing the header params
            $headers = array('Accept: application/json', 'Content-Type: application/json', 'Authorization: Bearer ' . $token);
            // Initialize a Curl session
            $curl = curl_init();
            // Set up Curl sending options
            // The URL to call
            curl_setopt($curl, CURLOPT_URL, $url);
            // Set up the curl header
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            // Get the result by string
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // Set the HTTP method. PUT here.
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
            // Curl session execution
            $result = curl_exec($curl);
            // Get the request return code
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            // Close the Curl session
            curl_close($curl);
            // Set the returned array.
            $res = array_merge(array('code' => $code), array('result' => json_decode($result, true)));

            print_r($res);

        } catch ( Exception $e ) {
            throw new Exception("Error during the call. Here the message: " . $e -> Message);
        }*/

        //**************FIN PAUSE************************

        //***********RESUME*****************

        /*$campId = "7897";
        //The campaign ID (Integer)
        $emailCampaign = array(
            "CampId" => "7897",
            "CustId" => "100249",
            "EntityId" => "167",
            "CampAction" => "RESUME"
        );
        //You campaig content
        $json = json_encode($emailCampaign);
        // Your access token (please refer to the Get Started documentation
        //$token = $token;
        // URL to request your record with your params
        $url = "https://api.ccmp.eu/services2/api/EmailCampaign?id=" . $campId;
        try {// Set header : a table containing the header params
            $headers = array('Accept: application/json', 'Content-Type: application/json', 'Authorization: Bearer ' . $token);
            // Initialize a Curl session
            $curl = curl_init();
            // Set up Curl sending options
            // The URL to call
            curl_setopt($curl, CURLOPT_URL, $url);
            // Set up the curl header
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            // Get the result by string
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // Set the HTTP method. PUT here.
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
            // Curl session execution
            $result = curl_exec($curl);
            // Get the request return code
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            // Close the Curl session
            curl_close($curl);
            // Set the returned array.
            $res = array_merge(array('code' => $code), array('result' => json_decode($result, true)));

            print_r($res);

        } catch ( Exception $e ) {
            throw new Exception("Error during the call. Here the message: " . $e -> Message);
        }

        die;*/

        //**************FIN RESUME************************

        //**************SAVE******************************
        $campId = "7897";
        //The campaign ID (Integer)
        $emailCampaign = array(
            "CampId" => "7897",
            "CustId" => "100249",
            "EntityId" => "167",
            "emailMsgTemplate" => array(
                "campId" => "7897",
                "subject" => "Modificación del jueves tarde"
             ),
            "CampAction" => "SAVE",
            "CampParam" => array(
                "queueSchedule" => array(
                    "timeZone" => "W_Europe_Standard_Time",
                    "dayFrequency" => array(
                        "frequencyType" => "Daily",
                        "daysInterval" => "1"
                    ),
                    "timeFrequency" => array(
                        "timeIntervalType" => "MutipleTimesADay",
                        "multipleTimesInterval" => array(
                            "runInterval" => "10",
                            "excludeTimeBefore" => "2000-01-01T00:00:01",
                            "excludeTimeAfter" => "2000-01-01T23:59:59"
                        )
                    )
                )
            ),
            "contBodies" => array (array (
                            "body"=> '<html><head><title></title></head><body>Email modificado otra vez</body></html>{[opt-out-tablaAPI|13502]}',
                            "campId"=> "7897",
                            "usageMask"=> "ALL_EMAIL_STYLE_USAGE_MASK",
                            "type"=> "HTML"
                ))
        );
        //You campaig content
        $json = json_encode($emailCampaign);
        // Your access token (please refer to the Get Started documentation
        //$token = $token;
        // URL to request your record with your params
        $url = "https://api.ccmp.eu/services2/api/EmailCampaign?id=" . $campId;
        try {// Set header : a table containing the header params
            $headers = array('Accept: application/json', 'Content-Type: application/json', 'Authorization: Bearer ' . $token);
            // Initialize a Curl session
            $curl = curl_init();
            // Set up Curl sending options
            // The URL to call
            curl_setopt($curl, CURLOPT_URL, $url);
            // Set up the curl header
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            // Get the result by string
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // Set the HTTP method. PUT here.
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
            // Curl session execution
            $result = curl_exec($curl);
            // Get the request return code
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            // Close the Curl session
            curl_close($curl);
            // Set the returned array.
            $res = array_merge(array('code' => $code), array('result' => json_decode($result, true)));

            print_r($res);

        } catch ( Exception $e ) {
            throw new Exception("Error during the call. Here the message: " . $e -> Message);
        }

        //************************FIN SAVE***********************************

        //************************CHANGE*************************************
        $campId = "7897";
        //The campaign ID (Integer)
        $emailCampaign = array(
            "CampId" => "7897",
            "CustId" => "100249",
            "EntityId" => "167",
            /*"emailMsgTemplate" => array(
                "campId" => "7897",
                "subject" => "Modificación del jueves tarde"
             ),*/
            "CampAction" => "CHANGE",
            /*"CampParam" => array(
                "queueSchedule" => array(
                    "timeZone" => "W_Europe_Standard_Time",
                    "dayFrequency" => array(
                        "frequencyType" => "Daily",
                        "daysInterval" => "1"
                    ),
                    "timeFrequency" => array(
                        "timeIntervalType" => "MutipleTimesADay",
                        "multipleTimesInterval" => array(
                            "runInterval" => "10",
                            "excludeTimeBefore" => "2000-01-01T00:00:01",
                            "excludeTimeAfter" => "2000-01-01T23:59:59"
                        )
                    )
                )
            ),*/
            "contBodies" => array (array(
                            "body"=> '<html><head><title></title></head><body>Email modificado otra vez el jueves por la tarde</body></html>{[opt-out-tablaAPI|13502]}',
                            "campId"=> "7897",
                            "usageMask"=> "ALL_EMAIL_STYLE_USAGE_MASK",
                            "type"=> "HTML"
                ))
        );
        //You campaig content
        $json = json_encode($emailCampaign);
        // Your access token (please refer to the Get Started documentation
        //$token = $token;
        // URL to request your record with your params
        $url = "https://api.ccmp.eu/services2/api/EmailCampaign?id=" . $campId;
        try {// Set header : a table containing the header params
            $headers = array('Accept: application/json', 'Content-Type: application/json', 'Authorization: Bearer ' . $token);
            // Initialize a Curl session
            $curl = curl_init();
            // Set up Curl sending options
            // The URL to call
            curl_setopt($curl, CURLOPT_URL, $url);
            // Set up the curl header
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            // Get the result by string
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // Set the HTTP method. PUT here.
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
            // Curl session execution
            $result = curl_exec($curl);
            // Get the request return code
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            // Close the Curl session
            curl_close($curl);
            // Set the returned array.
            $res = array_merge(array('code' => $code), array('result' => json_decode($result, true)));

            print_r($res);

        } catch ( Exception $e ) {
            throw new Exception("Error during the call. Here the message: " . $e -> Message);
        }
        //******************************FIN CHANGE******************************

        //***********APPROVE*****************

        /*$campId = "7897";
        //The campaign ID (Integer)
        $emailCampaign = array(
            "CampId" => "7897",
            "CustId" => "100249",
            "EntityId" => "167",
            "CampAction" => "APPROVE"
        );
        //You campaig content
        $json = json_encode($emailCampaign);
        // Your access token (please refer to the Get Started documentation
        //$token = $token;
        // URL to request your record with your params
        $url = "https://api.ccmp.eu/services2/api/EmailCampaign?id=" . $campId;
        try {// Set header : a table containing the header params
            $headers = array('Accept: application/json', 'Content-Type: application/json', 'Authorization: Bearer ' . $token);
            // Initialize a Curl session
            $curl = curl_init();
            // Set up Curl sending options
            // The URL to call
            curl_setopt($curl, CURLOPT_URL, $url);
            // Set up the curl header
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            // Get the result by string
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // Set the HTTP method. PUT here.
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
            // Curl session execution
            $result = curl_exec($curl);
            // Get the request return code
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            // Close the Curl session
            curl_close($curl);
            // Set the returned array.
            $res = array_merge(array('code' => $code), array('result' => json_decode($result, true)));

            print_r($res);

        } catch ( Exception $e ) {
            throw new Exception("Error during the call. Here the message: " . $e -> Message);
        }

        die;*/

        //**************FIN APPROVE************************
    }
}
