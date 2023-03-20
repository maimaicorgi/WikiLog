# Wikilogとは
WikilogはWikipediaのログ解析を行うコマンドラインプログラムです。

データベースに登録したログデータについて、以下の2通りの検索機能を提供します。
<br><br>

- 最もビュー数の多い記事を、指定した個数分だけビュー数が多い順にソートし、ドメインコード・ページタイトル・ビュー数を表示する。

    例）コマンドライン上で「2」と指定した場合

        en Main_Page 120
        en Wikipedia:Umnyango_wamgwamanda 112

- 指定したドメインコードについて、ドメインコード名・合計ビュー数を合計ビュー数が多い順に表示する。

    例）コマンドライン上で「en de」と指定した場合

        en 10700
        de 5300
# 導入
## アプリ
WikilogはDockerコンテナで動作するプログラムです。Docker環境を用意した上でプロジェクトを配置してください。
<br><br>

プロジェクトはappとdbの2つのコンテナから構成されています。プロジェクトを配置したディレクトリで以下のコマンドを順に実行することでdocker-composeを利用してコンテナを起動してください。
```
$ docker-compose build
```
```
$ docker-compose up -d
```

## ログデータ
Wikilogを使用するには、まず[こちら](https://dumps.wikimedia.org/other/pageviews/)からダウンロードしたログデータをデータベースにインポートする必要があります。ログデータをインポートするにはappコンテナ内で以下の手順を実行してください。

1. ログデータファイルをlogs.csvというファイル名で以下のディレクトリに配置します。
    ```
    /app/import
    ```

2. 下記のコマンドを実行します。

    ```
    $ mysqlimport -h db -u root -p --local --delete --fields-terminated-by=' ' wikilog app/import/logs.csv
    ```
    パスワードの入力を求められるのでプロジェクト内のdocker/dbディレクトリにあるdb-variables.envに記載されているMYSQL_ROOT_PASSWORDを入力してください。
<br><br>

上記のコマンドでログデータのインポートを行うと、それまでデータベースにあったログデータは全て削除されます。既存のログデータを削除せずに新たなログデータを追加したい場合はコマンドから--deleteオプションを外した上で実行してください。
<br><br>

また、ダウンロードしたログデータの詳細については[こちら](https://wikitech.wikimedia.org/wiki/Analytics/Data_Lake/Traffic/Pageviews)で確認ができます。
<br><br>

# 使い方
以下のファイルがWikilogのエントリーポイントです。このファイルを実行するとプログラムが起動するので、コマンドラインの指示に従って操作を進めてください。
```
/app/index.php
```
