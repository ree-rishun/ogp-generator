<?php

// 挿入文字サイズ取得時の添え字定義
define('TEXTPOSITION_BOTTOMLEFT_X'  , 0); // 左下X
define('TEXTPOSITION_BOTTOMLEFT_Y'  , 1); // 左下Y
define('TEXTPOSITION_BOTTOMRIGHT_X' , 2); // 右下X
define('TEXTPOSITION_BOTTOMRIGHT_Y' , 3); // 右下Y
define('TEXTPOSITION_TOPRIGHT_X'    , 4); // 右上X
define('TEXTPOSITION_TOPRIGHT_Y'    , 5); // 右上Y
define('TEXTPOSITION_TOPLEFT_X'     , 6); // 左上X
define('TEXTPOSITION_TOPLEFT_Y'     , 7); // 左上Y

// Qi○ta風OGP生成関数
function ogp_generator($title,$user){
    $jpg = 'src/ogback.jpg';
    $afjpg = 'out/to.jpg';

    // フォントファイルのフルパスを指定
    $font = 'C:\Windows\Fonts\meiryob.ttc';

    $image = imagecreatefromjpeg($jpg);
    $color = imagecolorallocate($image, 50, 50, 50);

    // Qi○taは1行につき半角28文字（全角14文字）
    $title_cut = mb_strimwidth ($title, 0, 112, '...', 'UTF8');
    $title_length = mb_strwidth($title_cut,'UTF8');
    $title_lines = "";

    for($i=0;$i < $title_length / 14;$i++){
        if($i == 0)
            $title_lines = mb_substr_size( $title_cut,0,14,'UTF8');
        else
            $title_lines = $title_lines . "\n" . mb_substr_size( $title_cut,14 * $i,14,'UTF8');
    }

    // title
    imagettftextposition($image,40,0,'center','center',$color,$font,$title_lines,'center');
    // user
    imagettftextposition($image,30,0,180,540,$color,$font,$user,'right','right');

    imagejpeg($image, $afjpg, 100);
}

// 半角を0.5文字　全角を1文字としての文字数による切り出し
function mb_substr_size($text,$offset,$size,$encoding){
    $return_text = "";
    $offset_cursor = 0;

    // オフセットまで移動
    for($i = 0;$i < $offset * 2;$i++){
        $char = mb_substr($text, $offset_cursor,1, $encoding);
        if (strlen($char) != mb_strlen($char))
            $i++;
        $offset_cursor++;
    }

    // 文字の切り出し
    for($i = 0;$i < $size * 2;$i++){
        $char = mb_substr($text, $offset_cursor++,1, $encoding);
        $return_text = $return_text . $char;
        if (strlen($char) != mb_strlen($char))
            $i++;
    }

    return $return_text;
}

// 画像に文字を挿入
function imagettftextposition($image, $size, $angle, $x, $y, $color, $fontfile, $text, $align = 'left', $offset = 'left'){

    $jpg = 'src/ogback.jpg';
    // 画像サイズの取得
    $image_size = getimagesize($jpg);

    if($image_size){
        $image_width = $image_size[0];
        $image_height = $image_size[1];
    }else{
        echo "ERROR: not found image\n";
        return 1;
    }

    // 文字列の取得
    $text_position = imagettfbbox($size, $angle, $fontfile, $text);
    $text_position_width = $text_position[TEXTPOSITION_BOTTOMRIGHT_X] - $text_position[TEXTPOSITION_BOTTOMLEFT_X];
    $text_position_height = $text_position[TEXTPOSITION_BOTTOMRIGHT_Y] - $text_position[TEXTPOSITION_TOPRIGHT_Y];

    // x軸の位置指定
    if(gettype($x) == 'string'){
        switch ($x){
            case 'left':
                $x_position = 0;
                break;

            case 'right':
                $x_position = $image_width - $text_position_width;
                break;

            case 'center':
                $x_position = ($image_width - $text_position_width) / 2;
                break;

            default:
                return 1;
        }
    }else{
        if($offset == 'right')
            $x_position = $image_width - $text_position_width - $x;
        else
            $x_position = $x;
    }

    // y軸の位置指定
    if(gettype($y) == 'string'){
        switch ($y){
            case 'top':
                $y_position = 0;
                break;

            case 'bottom':
                $y_position = $image_height;
                break;

            case 'center':
                $y_position = ($image_height - $text_position_height) / 2 + $text_position_height / (substr_count($text,"\n") + 1);
                break;

            default:
                return 1;
        }
    }else{
        $y_position = $y;
    }

    // 文字列を合成
    switch ($align) {
        case 'left':
            imagettftext($image, $size, $angle, $x_position, $y_position, $color, $fontfile, $text);
            break;
        case 'right':
            // 行ごとに文字列を分解
            $line = explode("\n",$text);
            for ($i = 0; $i < substr_count($text, "\n") + 1; $i++) {
                $line_position = imagettfbbox($size, $angle, $fontfile, $line[$i]);
                $line_position_width = $line_position[TEXTPOSITION_BOTTOMRIGHT_X] - $line_position[TEXTPOSITION_BOTTOMLEFT_X];
                imagettftext($image, $size, $angle, $x_position + $text_position_width - $line_position_width, $y_position + ($text_position_height / (substr_count($text, "\n") + 1) + $size) * $i, $color, $fontfile, $line[$i]);
            }
            break;
        case 'center':
            // 行ごとに文字列を分解
            $line = explode("\n",$text);
            for ($i = 0; $i < substr_count($text, "\n") + 1; $i++) {
                $line_position = imagettfbbox($size, $angle, $fontfile, $line[$i]);
                $line_position_width = $line_position[TEXTPOSITION_BOTTOMRIGHT_X] - $line_position[TEXTPOSITION_BOTTOMLEFT_X];
                imagettftext($image, $size, $angle, $x_position + ($text_position_width - $line_position_width) / 2, $y_position + ($text_position_height / (substr_count($text, "\n") + 1) + $size) * $i, $color, $fontfile, $line[$i]);
            }
            break;
    }

    return 0;
}

// 関数を実行
ogp_generator("【PHP】OGP生成プログラムを作ってみた！", '@reerishun');