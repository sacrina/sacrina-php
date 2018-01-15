<?php

/**
 * Class sacrinasdk
 */
class sacrinasdk
{
    public $key = null;
    public $dataset = [];
    public $headers = [];
    public $dataset_id = null;
    public $model_id = null;
    public $project_id = null;
    public $results = [];

    /**
     * method add_key
     * @param $key
     */
    public function add_key($key)
    {
        $this->key = $key;
        $this->headers = [
            "Accept:application/json",
            "Api-Key:$this->key",
        ];
    }

    /**
     * method add_data
     * @param $input_string
     */
    public function add_data($input_string)
    {
        if (!is_string($input_string)) {
            var_dump("input_string paramter is not string, please check your input");
        } else {
            array_push($this->dataset, $input_string);
        }
    }

    /**
     * method add_dataset
     * @param $input_array
     */
    public function add_dataset($input_array)
    {
        if (!is_string($input_array)) {
            var_dump("input_array paramter is not an array of strings, please check your input");
        } else {
           array_push($this->dataset,$input_string);
        }
    }

    /**
     * method upload_dataset
     * @return string
     */
    public function upload_dataset(){
        $url = 'https://sacrina.com/REST/learning/datasets/';

        $data = [
            'title'=>'test3',
            'description'=>'test4',
        ];

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch,CURLOPT_POST, count($data));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, False);

        //execute post
        $response = curl_exec($ch);


        //close connection
        curl_close($ch);


        if($response->status_code != 201){

            return "error creating dataset";
        }

        $response_json = json_decode($response['text']);
        $this->dataset_id = $response_json['id'];

        #upload data to dataset
        $url_2 = 'https://sacrina.com/REST/learning/datas/';

        if(count($this->dataset)){
            foreach ($this->dataset as $value){
                $data_2_ = [
                    'title'=>'data1',
                    'description'=> 'https://sacrina.com/REST/learning/datasets/' . (string)$this->dataset_id . '/',
                    'content'=> (string)$value,
                ];

                //open connection
                $ch = curl_init();

                //set the url, number of POST vars, POST data
                curl_setopt($ch,CURLOPT_URL, $url_2);
                curl_setopt($ch,CURLOPT_POST, count($data_2_));
                curl_setopt($ch,CURLOPT_POSTFIELDS, $data_2_);
                curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, False);

                //execute post
                $resposne_2 = curl_exec($ch);

                //close connection
                curl_close($ch);
                $resposne_2 = json_decode($resposne_2);

                if($resposne_2->status_code != 201){
                    return "error uploading data to dataset";
                }

                return "Dataset upload completed";
            }
        }
        return "error uploading data to dataset";
    }

    /**
     * method select_dataset
     * @param $id
     * @return mixed|string
     */
    public function select_dataset($id)
    {
        $this->dataset_id = $id;

        $opts = [
            "http" => [
                "method" => "GET",
                "header" => $this->headers
            ]
        ];

        $context = stream_context_create($opts);
        $url = 'https://sacrina.com/REST/learning/datasets/' . (string)$this->dataset_id . '/';

        $response = @file_get_contents($url,false,$context);

        if($response){
            return json_decode($response);
        }else{
            return 'error';
        }

    }

    /**
     * method create_model
     * @return mixed|string
     */
    public function create_model()
    {
        $url = 'https://sacrina.com/REST/learning/models/';

        $data = [
            'title'=>'mymodel',
            'datasets'=> 'https://sacrina.com/REST/learning/datasets/' .(string)$this->dataset_id. '/',
        ];

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, count($data));
        curl_setopt($ch,CURLOPT_HTTPHEADER, $this->headers );
        curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, False);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $response = curl_exec($ch);

        //close connection
        curl_close($ch);
        $response = json_decode($response);

        if(empty($response)){
            return "error";
        }else{
            $this->model_id = $response->id;

            return json_decode($response);
        }
    }

    /**
     * method select_model
     * @param $model_id
     * @return mixed|string
     */
    public function select_model($model_id)
    {
        $this->model_id = $model_id;

        $opts = [
            "http" => [
                "method" => "GET",
                "header" => $this->headers
            ]
        ];

        $context = stream_context_create($opts);
        $url = 'https://sacrina.com/REST/learning/models/' .(string) $this->model_id . '/';
        $response = @file_get_contents($url,false,$context);

        if($response){
            return json_decode($response);
        }else{
            return 'error';
        }

    }

    /**
     * method train_model
     * @return mixed|string
     */
    public function train_model()
    {
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => $this->headers,
            ]
        ];

        $context = stream_context_create($opts);
        $url = 'https://sacrina.com/REST/learning/models/' . (string)$this->model_id . '/?train';
        $response = @file_get_contents($url,false,$context);

        if($response){
            return json_decode($response);
        }else{
            return 'error';
        }
    }

    /**
     * method check_model_status
     * @return string
     */
    public function check_model_status()
    {
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => $this->headers
            ]
        ];

        $context = stream_context_create($opts);
        $url = 'https://sacrina.com/REST/learning/models/' . (string)$this->model_id . '/';

        $response = file_get_contents($url,false,$context);
        $response = json_decode($response);

        if (empty($response)) {
            return "error";
        } else {
            return $response->status;
        }
    }

    /**
     * method create_project
     * @param $gen
     * @param $sector_min
     * @param $sector_max
     * @return mixed|string
     */
    public function create_project($gen,$sector_min,$sector_max){
        $url = 'https://sacrina.com/REST/production/projects/';

        $data = [
            'name'=>'myproject',
            'model'=>'https://sacrina.com/REST/learning/models/' . (string)$this->model_id .'/',
            'gen'=> $gen,
            'sector_min'=> $sector_min,
            'sector_max'=> $sector_max,
        ];

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch,CURLOPT_POST, count($data));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, False);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //execute post
        $response = curl_exec($ch);

        //close connection
        curl_close($ch);

        $response = json_decode($response);

        if(empty($response)){
            return "error";
        }else{
            $this->project_id = $response->id;
            return $response;
        }
    }

    /**
     * method select_project
     * @param $project_id
     * @return mixed|string
     */
    public function select_project($project_id){

        $this->project_id = $project_id;

        $opts = [
            "http" => [
                "method" => "GET",
                "header" => $this->headers
            ]
        ];

        $context = stream_context_create($opts);
        $url = 'https://sacrina.com/REST/production/projects/' .(string) $this->project_id . '/';
        $response = @file_get_contents($url,false,$context);

        if($response){
            return json_decode($response);
        }else{
            return 'error';
        }
    }

    /**
     * method execute_project
     * @return mixed|string
     */
    public function execute_project(){

        $opts = [
            "http" => [
                "method" => "GET",
                "header" => $this->headers
            ]
        ];

        $context = stream_context_create($opts);
        $url = 'https://sacrina.com/REST/production/projects/'. (string)$this->project_id  . '/?execute';
        $response = @file_get_contents($url,false,$context);

        if($response){
            return json_decode($response);
        }else{
            return 'error';
        }
    }

    /**
     * method check_project_status
     * @return string
     */
    public function check_project_status(){

        $opts = [
            "http" => [
                "method" => "GET",
                "header" => $this->headers
            ]
        ];

        $context = stream_context_create($opts);
        $url = 'https://sacrina.com/REST/production/projects/' . (string)$this->project_id  . '/';
        $response = @file_get_contents($url,false,$context);

        if($response){
            return json_decode($response)->status;
        }else{
            return 'error';
        }
    }

    /**
     * method download_results
     * @return array|string
     */
    public function download_results(){

        $opts = [
            "http" => [
                "method" => "GET",
                "header" => $this->headers
            ]
        ];

        $context = stream_context_create($opts);
        $url = 'https://sacrina.com/REST/production/results/?project_id=' .(string)$this->project_id ;
        $response = @file_get_contents($url,false,$context);

        $response = json_decode($response);
        if(count($response)){

            foreach ($response as $val){

                array_push($this->dataset,$val->content);
            }

            return $this->dataset;
        }else{
            return 'error';
        }
    }
}







