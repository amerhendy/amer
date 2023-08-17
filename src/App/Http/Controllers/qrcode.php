<?php
namespace Amerhendy\Amer\App\Http\Controllers;
use App\Http\Controllers\Controller;
use AmerHelper;
use \Milon\Barcode\DNS2D;
class qrcode extends Controller{
    public function index(){
        if(isset($_GET['url'])){
            $data='';
        }else{
            if(!is_numeric(array_keys($_GET)[0])){$data=array_keys($_GET)[0];}
        }
        $d = new DNS2D();
        echo $d->getBarcodeHTML($data, 'QRCODE');
        
    }
}
?>