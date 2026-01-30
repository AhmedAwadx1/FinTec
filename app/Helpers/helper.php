<?php
use Illuminate\Support\Facades\App;

use App\Models\Seo;
use App\Models\SiteSetting;

function seo($key){
    return Seo::where('key' , $key)->first() ;
}

function appInformations(){
    $result = SiteSetting::pluck('value', 'key');
    return $result;
}


function convert2english( $string )
{
    $newNumbers = range( 0, 9 );
    $arabic     = array( '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' );
    $string     = str_replace( $arabic, $newNumbers, $string );
    return $string;
}

function fixPhone( $string = null )
{
    if(!$string){
      return null;
    }

    $result = convert2english($string);
    $result = ltrim($result, '00');
    $result = ltrim($result, '0');
    $result = ltrim($result, '+');
    return $result;
}

function getYoutubeVideoId( $youtubeUrl )
{
    preg_match( "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/",
        $youtubeUrl, $videoId );
    return $youtubeVideoId = isset( $videoId[ 1 ] ) ? $videoId[ 1 ] : "";
}

function lang(){
    return App() -> getLocale();
}

function generateRandomCode(){
    return '1234';
    return rand(1111,4444);
}

if (!function_exists('languages')) {
  function languages() {
    return ['ar', 'en'];
  }
}

if (!function_exists('defaultLang')) {
  function defaultLang() {
    return 'ar';
  }
}



