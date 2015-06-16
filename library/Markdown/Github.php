<?php
/**
 * @author Patsura Dmitry <talk@dmtry.me>
 */

namespace App\Markdown;

use stdClass;

class Github
{
    protected $username;

    protected $password;

    protected $token;

    public function __construct($username, $password, $token)
    {
        $this->username = $username;
        $this->password = $password;
        $this->token = $token;
    }

    public function getRenderedHTML($value)
    {
        //Build object to send
        $sendObj = new stdClass();
        $sendObj->text = $value;
        $sendObj->mode = 'markdown';
        $content = json_encode($sendObj);

        //Build headers
        $headers = array("Content-type: application/json", "User-Agent: dmtry.me blog 1.0");
        $encoded = base64_encode($this->username . ':' . $this->password);
        $headers[] = "Authorization: token {$this->token}";

        //Build curl request to github's api
        $curl = curl_init('https://api.github.com/markdown');
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);


        //Send request and verify response
        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($status != 200) {
            user_error("Error: Call to api.github.com failed with status $status, response $response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl), E_USER_WARNING);
        }

        //Close curl connection
        curl_close($curl);

        return $response;
    }
}
