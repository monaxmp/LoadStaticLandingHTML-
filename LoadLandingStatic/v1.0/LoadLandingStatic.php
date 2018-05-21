<?php

class LoadLandingStatic
{
    public $BasePath='source';
    public $fileHTML = 'index.html';
    public $count_all;
    public $errorLoad=0;

    public $params=[
        ['extension'=>'js' ,'path_save'=>'js',         'name_save'=>true],
        ['extension'=>'css','path_save'=>'css',        'name_save'=>true],
        ['extension'=>'jpg','path_save'=>'images/jpg', 'name_save'=>false],
        ['extension'=>'png','path_save'=>'images/png', 'name_save'=>false],
        ['extension'=>'gif','path_save'=>'images/gif', 'name_save'=>false],
    ];

    public function __construct()
    {
        foreach ($this->params as $item_params) {
            $this->{'count_'.$item_params['extension']}=0;
        }
    }

    protected function createDirectory(string $path)
    {
        if (is_dir($path)) {
            return true;
        }
        if (!mkdir($path, 0777, true)) {
            throw new Exception ('Не удалось создать директории...');
        }
        return true;
    }

    public function load(string $str,array $params)
    {
        $this->count_all++;
        $this->{'count_'.$params['extension']}++;

        $path_dir=$this->BasePath.'/'.$params['path_save'].'/';
        $this->createDirectory($path_dir);
        $pattern="#http.+\.".$params['extension']."#u";

        preg_match_all($pattern,$str,$out,PREG_PATTERN_ORDER);
        $url=$out[0][0];

        $path_file=$params['name_save'] ? $path_dir.basename($url) : $path_dir.uniqid().'.'.$params['extension'];

        echo 'Load....'; print_r( $url);

        if ($load_file=file_get_contents($url)){
            echo PHP_EOL.strlen( $load_file).'b'.PHP_EOL;
            file_put_contents($path_file, $load_file) ;
            echo "load.....Ok!";
            return preg_replace($pattern,$path_file,$str);
        }else {
            echo "Error load.....".$url;
            $this->errorLoad++;
            return $str;
        }
    }

    public function run(){
        $array = file($this->fileHTML);
        if($array){
            foreach ($array as $key => &$str) {
                foreach ($this->params as $item_params) {
                    if (preg_match("#^.+http.+\." . $item_params['extension'] . "#u", $str)) {
                        echo '---------------------------------------------------------------------------'.PHP_EOL;
                        echo 'Fidn file to line '.$key." ". $item_params['extension'].' '.$str;
                        $str=$this->load($str,$item_params);
                    }
                }
            }
        }
        file_put_contents( $this->fileHTML , $array );

        foreach ($this->params as $item_params)
           echo PHP_EOL.'download_'.$item_params['extension'].'='.$this->{'count_'.$item_params['extension']};
        echo PHP_EOL.'errorLoad='.$this->errorLoad;
    }
}
