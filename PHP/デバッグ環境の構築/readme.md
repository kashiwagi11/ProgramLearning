# PHP デバッグ環境構築メモ

## 概要

VScode で PHP のデバッグ環境を構築する。

## 前提条件

- xampp で apache 起動 & php が実行できる状態であること
- VScode が使用できる状態であること

## 環境構築の流れ

1. VScode に[PHP Debug](https://marketplace.visualstudio.com/items?itemName=felixfbecker.php-debug)をインストールする
2. PHPDebug を実行するための DLL 取得と php.ini の設定変更を行う
3. VScode でデバッグ構成ファイルを作成する

## 1. VScode に PHP Debug をインストールする

[PHP Debug](https://marketplace.visualstudio.com/items?itemName=felixfbecker.php-debug)を VScode の拡張機能検索から検索し、インストールする。  
VScode の設定ファイル(JSON)に下記を追記する。(PHP のインストールパスは各環境により異なる)

```
"php.debug.executablePath": "C:\\xampp\\php\\php.exe",
```

## 2. PHPDebug を実行するための DLL 取得と php.ini の設定変更を行う

PHPDebug を使用するためには Xdebug の DLL が必要になる。

コマンドプロンプトで php.exe のディレクトリを開いて『php -i | clip』を実行する。
実行しても何も表示されないが、自動的に必要な情報がクリップボードにコピーされた状態になる。

[DLL 取得用リンク](https://xdebug.org/wizard)にアクセスする。  
Installation Wizard の入力欄にコピーした情報の貼り付けを行う。  
「Analyse my phpinfo() output」ボタンを押す。

画面が切り替わるので、赤枠の DLL ファイルをダウンロードする。
![](https://my-web-note.com/wp-content/uploads/2022/01/vscode-php-develop-debug_07-1024x885.png)

ダウンロードした DLL ファイルを下記の場所に置く。

```
C:\xampp\php\ext
```

php.ini ファイルの最後に下記を追記し、DLL が使用できる状態にする。  
**※zend_extension は各自の DLL ファイルのパスを指定する。**

```
■ iniファイルの場所
C:\xampp\php\php.ini

■ 追記する内容
[XDebug]
xdebug.mode = debug
xdebug.start_with_request = yes
xdebug.remote_enable = 1
xdebug.remote_autostart = 1
xdebug.remote_host = localhost
xdebug.remote_port = 9003
zend_extension = "C:\xampp\php\ext\php_xdebug-3.1.5-8.1-vs16-x86_64.dll"
```

## 3. VScode でデバッグ構成ファイルを作成する

VScode 左側の「実行とデバッグ」を選択し、下記のように選択していく。
![](https://my-web-note.com/wp-content/uploads/2022/01/vscode-php-develop-debug_16.png)

launch.json ファイルの編集画面になるので、下記内容になるようにする。

```
{
  // IntelliSense を使用して利用可能な属性を学べます。
  // 既存の属性の説明をホバーして表示します。
  // 詳細情報は次を確認してください: https://go.microsoft.com/fwlink/?linkid=830387
  "version": "0.2.0",
  "configurations": [
    {
      "name": "Listen for Xdebug",
      "type": "php",
      "request": "launch",
      "port": 9003
    },
    {
      "name": "Launch currently open script",
      "type": "php",
      "request": "launch",
      "program": "${file}",
      "cwd": "${fileDirname}",
      "port": 0,
      "runtimeArgs": ["-dxdebug.start_with_request=yes"],
      "env": {
        "XDEBUG_MODE": "debug,develop",
        "XDEBUG_CONFIG": "client_port=${port}"
      }
    },
    {
      "name": "Launch Built-in web server",
      "type": "php",
      "request": "launch",
      "runtimeArgs": [
        "-dxdebug.mode=debug",
        "-dxdebug.start_with_request=yes",
        "-S",
        "localhost:0"
      ],
      "program": "",
      "cwd": "${workspaceRoot}",
      "port": 9003,
      "serverReadyAction": {
        "pattern": "Development Server \\(http://localhost:([0-9]+)\\) started",
        "uriFormat": "http://localhost:%s",
        "action": "openExternally"
      }
    }
  ]
}

```

## 最後に

以上で環境構築は完了。  
実行とデバッグの選択から「Listen for Xdebug」を選択し、実行するとデバッグできる。
![](https://my-web-note.com/wp-content/uploads/2022/01/vscode-php-develop-debug_18.png)
