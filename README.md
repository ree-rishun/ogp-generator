# OGP GENERATOR
Qi○ta風のOGPを生成できるPHPコード

## 使い方
```php
ogp_generator("タイトル", 'ユーザーID');
```

### 仕様
- 第一引数にタイトルを渡してください。
- 第二引数にユーザIDを渡してください（@は不要です）
- 半角28文字、全角14文字が1行になり、5行目以降が存在する場合は省略されます。

### 背景画像を変更したい場合
1. 画像ファイルを上書
    src/ogback.jpgを使用したい画像に変更する
2. ソースを書き換える
    ogp_generator.phpの15行目の画像パスを書き換える

## 詳細
各関数はそれぞれブログにて紹介しています。
- 【PHP】Qiita風のOGPを生成してみた。
    - https://reerishun.com/makerblog/?p=775
- 【PHP】画像に文字を配置を指定して書き込みをする自作関数
    - https://reerishun.com/makerblog/?p=784
- 【PHP】全角と半角を区別して文字列を指定文字数分切り出す
    - https://reerishun.com/makerblog/?p=779
