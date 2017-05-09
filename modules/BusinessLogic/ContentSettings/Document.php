<?php
/**
 * Created by PhpStorm.
 * User: Krisz
 * Date: 2017.01.08.
 * Time: 19:30
 */

namespace Modules\BusinessLogic\ContentSettings;


use Modules\BusinessLogic\Models\Documents;

class Document extends Base
{
    public $id;

    public $sourceImage;

    public $croppedImage;

    public $bounds;

    public $name;

    public $type;

    public $size;

    public $langs;


    /**
     * @param Documents $obj
     * @param $lang
     * @return Document
     */
    public function generate(Documents $obj,$lang){
        $document = new Document();
        $document->id = $obj->id;
        $document->sourceImage = $obj->sourceImage;
        $document->croppedImage = $obj->croppedImage;
        $document->bounds = $obj->bounds;
        $langs = (object)$obj->langs;
        $document->name = isset($langs->{$lang}['name'])?$langs->{$lang}['name']:$this->urlMakeup($obj->name,true);
        $document->type = $obj->type;
        $document->size = $obj->size;
        $document->langs = $obj->langs;
        return $document;
    }

    /**
     * Törlés
     * @return bool
     */
    public function delete(){

        $this->deleteCache($this);
        
        $model = new Documents();
        $document = $model->create($this->id);
        if($document->delete()){
            unset($this);
            return true;
        }else{
            return false;
        }
    }

    /**
     * Mentés
     * @return bool
     */
    public function save(){

        $this->deleteCache($this);
        
        $model = new Documents();
        $document = $model->create($this->id);
        $document->id = $this->id;
        $document->sourceImage = $this->sourceImage;
        $document->croppedImage = $this->croppedImage;
        $document->bounds = $this->bounds;
        $document->name = $this->name;
        $document->type = $this->type;
        $document->size = $this->size;
        $document->langs = $this->langs;
        if($document->save()){
            return true;
        }else{
            return false;
        }
    }

    public function getUrl($aspectRatio = false){
        /** 
         * lekérjük a méreteket a configtól
         * @var  $resolutions */
        $resolutions = $this->getResolutions();
        
        if(isset($resolutions[$aspectRatio])){
            $width = $resolutions[$aspectRatio]['width'];
            $height = $resolutions[$aspectRatio]['height'];
            $dir = './public/images/'.$aspectRatio.'/';
            $path = $dir.$this->urlMakeup($this->name,true);
            if(!file_exists($path)){
                if(!file_exists($dir)){
                    mkdir('./public/images/'.$aspectRatio,0777);
                }

                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', !empty($this->croppedImage)?$this->croppedImage:$this->sourceImage));

                list($width_orig, $height_orig) = getimagesize(!empty($this->croppedImage)?$this->croppedImage:$this->sourceImage);

                $thumb_w = 0;
                $thumb_h = 0;
                /*--------------------------------------------------------------*/

                if($width_orig > $height_orig)
                {
                    $thumb_w    =   $width;
                    $thumb_h    =   $height_orig*($height/$width_orig);
                }

                if($width_orig < $height_orig)
                {
                    $thumb_w    =   $width_orig*($width/$height_orig);
                    $thumb_h    =   $width;
                }

                if($width_orig == $height_orig)
                {
                    $thumb_w    =   $width;
                    $thumb_h    =   $height;
                }
                /*--------------------------------------------------------------*/


                $image_p = imagecreatetruecolor($thumb_w, $thumb_h);
                $image = imagecreatefromstring($data);
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $thumb_w, $thumb_h, $width_orig, $height_orig);
                ob_start();
                imagepng($image_p, $path, 8);
                ob_clean();
            }
        }else{
            $path = './public/images/originals/'.$this->urlMakeup($this->name,true);
            if(!file_exists('./public/images/originals/')){
                mkdir('./public/images/originals',0777);
            }
            if(!file_exists($path)){
                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', !empty($this->croppedImage)?$this->croppedImage:$this->sourceImage));
                file_put_contents($path,$data);
            }
        }

        return substr($path, 1);

    }
}