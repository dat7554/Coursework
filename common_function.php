<?php

function func_sum($param1, $param2){
	return $param1 + $param2;
}
//GET request
function fetch_from_url($url){
	// create curl resource

        $ch = curl_init();

        // set url

        curl_setopt($ch, CURLOPT_URL, $url);


        //return the transfer as a string

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);



        // $output contains the output string

        $output = curl_exec($ch);


        // close curl resource to free up system resources

        curl_close($ch);  

        return $output;
}

//POST request
function post_url($url, $headers, $data){
	//open connection
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, true);	//POST method


	curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);


	//So that curl_exec returns the contents of the cURL; rather than echoing it
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

var_dump($data);
	//execute post
	$result = curl_exec($ch);
	return $result;
}



?>