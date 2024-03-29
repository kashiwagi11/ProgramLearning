# １章：コンテナの仕組みと利点

この章ではコンテナの仕組みと Docker の基本を学んだ。

コンテナはシステムの実行環境を隔離した空間のこと。
コンテナ同士は独自の実行環境となり互いに影響を及ぼさない。

コンテナにはポータビリティ性があり、コンテナ内の環境で完結しているので、他のサーバーなどにコピーすることも簡単にできる。

コンテナを実現するソフトの代表として Docker がある。
Docker は Linux 上で動作するソフト。Linux に DockerEngine をインストールすることで Docker コンテナが実行できるようになる。
DockerEngine をインストールしたコンピューターを Docker ホストと呼ぶ。
Windows や Mac でも DockerDesktop をインストールすることで Docker を使用することができる。（Linux 環境で動作するため、WSL や HyperV などの機能と合わせることで Docker を動作させている。）

Docker コンテナは Docker イメージから作成する。
Docker イメージは DockerHub などの共有レジストリに登録して公開されている。

Docker イメージには Linux ディストリビューションのみとアプリケーション入りのものがある。
自身で細かくカスタマイズした環境を作成したい場合、Linux のみのものを使用する。
アプリケーション入りの方は最小限の設定の場合がある為。
自身で作成した Docker イメージを共有することもできる。

Docker は完全な分離ではない。
仮想サーバーと違い、ハードウェアをエミュレートしているわけではないので、
CPU やメモリの制限は DockerEngine 任せになる。
仮想サーバーと違い、サーバーは１台でその中にアプリケーション実行環境が複数立てられるという認識が必要。

# ２章：Docker を利用できるサーバーを作る

この章では Docker が利用できる環境の作成について学んだ。

Docker は Linux 環境で動作するソフトの為、ここでは AWS の EC2 インスタンスに Ubuntu をインストールした環境を作成した。
Docker が利用できればいいので、Windows に DockerDesktop を入れたりしても良いと思う。

Docker はディストリビューションに含まれているパッケージと Docker 公式が公開しているパッケージがある。
ディストリビューションに含まれている方は yum や apt コマンドで簡単に管理できるが、ディストリビューションによってバージョンがまちまちだったりする。

EC2 を作成して、セキュリティグループの設定を行った。
SSH 接続してコマンド実行するためのポートと Web サーバを使用するためのポートを開けている。

# ３章：５分で Web サーバーを起動する

この章では、作成した EC2 環境で Apache の Docker イメージからコンテナ起動までを行った。
Docker イメージからコンテナ起動までの流れを学んだ。

- **DockerHub でイメージを探す**：公式が出しているイメージや個人がカスタマイズしたイメージなど様々公開されている。基本的にイメージのドキュメントに起動方法が書かれているのでその通りコマンドを実行する。
- **docker run で実行する**：コンテナ起動のコマンド。オプションなどを含んだ起動方法としてドキュメントに公開される。
- **停止と再開**：docker stop で停止し、docker start で再開する。コンテナの状態は docker ps で確認する。
- **コンテナの破棄**：コンテナは停止しても削除されずにディスク容量を使用したままになる。docker rm で使用しなくなったコンテナは削除できる。
- **イメージの破棄**：コンテナの元となるイメージはコンテナを削除しても残り続ける。コンテナ起動時に再ダウンロードしなくていいようにイメージが保存されている。イメージも必要なくなったら docker image rm コマンドで削除する。

# ４章：Docker の基本操作

この章では Docker の基本コマンドについて学んだ。

- **docker run は pull,create,start の複合コマンドである**：コンテナを起動したい時に、イメージの取得、コンテナの作成、コンテナの起動とコマンドを実行することになる。この一連の流れを順番に実行してくれている。
- **コマンドのオプションについて**
  - 「--name」：コンテナ名を付与する。これが無いとランダムなコンテナ名がつけられる。
  - 「-p」：コンテナにポートマッピングする。Docker ホストと Docker コンテナを通信するために必要。
  - 「-v」：コンテナの指定したディレクトリにホストのディレクトリをマウントする。マウントすることで、ホストのファイルをコンテナ内で使用でき、コンテナ内でホストのファイルに変更を加えられる。
- **コンテナをバックグラウンドで実行するには「-d」、キーボード操作をするなら「-it」**：実行中のコンテナ内を操作したい時は、docker exec で「-it」オプションを付け、「/bin/bash」などのシェルを起動する。
- **コンテナは終了しても削除されないので、たまっていく**：不要なコンテナは削除する。終了と同時に削除したい場合「--rm」オプションを指定する。
- **１回限り動かすコンテナの使い方**：コンパイラや画像変換など、ホストのファイルを処理したい場合。コンパイラを動作させ、コンパイルが完了したらもうコンテナ環境は必要なくなるといった場合に１回だけ動かす。docker run で「--rm」オプションをしていすることで、コンパイル完了後コンテナ削除まで実行される。

# ５章：コンテナ内のファイルと永続化

この章では、コンテナで永続的にデータを扱う方法について学んだ。

- **コンテナが削除されるとデータは失われる**：コンテナで扱うデータを永続化したい場合はマウントを使用する。
- **マウント方法には 2 種類あり**
  - **バインドマウント**：Docker ホストのディレクトリをマウントする。
  - **ボリュームマウント**：DockerEngine で管理されている領域にマウントする。
- **バインドマウントしたデータとボリュームマウントしたデータのバックアップ**：バインドマウントはホスト上にコンテナで保存したファイルが存在するので、コピーするだけでバックアップできる。一方、ボリュームマウントしたデータはそのままではホスト上で操作できないので、tar.gz などでアーカイブする必要がある。対象をマウントするコンテナを作り、そのコンテナ内で tar コマンドを実行してバックアップしたものをホストに取り出す。
- **マウント先を指定するには**：一般的には「-v」オプションを使用するが、現在では「--mount」オプションを指定することが推奨されている。「--mount」では、バインドかボリュームかを指定するため、どちらのマウントかがわかりやすい。また、「-v」ではボリュームが存在しない場合新規に作成されてしまうが、「--mount」では新規作成されない。このように、タイプミスによる意図しないボリュームの作成を防ぐことができる。

# ６章：コンテナのネットワーク

この章では、コンテナ間で通信を行うためのネットワーク設定について学んだ。

- **Docker が管理するネットワーク**：以下の 3 種類が存在する。
  - **bridge**：ホスト、コンテナが独自の IP アドレスを持ち、仮想ネットワークで接続される。
  - **host**：コンテナがホストの IP アドレスを共有し、全てのポートがコンテナ側に流れる。
  - **none**：コンテナをネットワークに接続しない。
    基本的には brigde を使用する。
- **コンテナ作成時のネットワーク**：コンテナ作成時に既定のネットワークとして自動で bridge のネットワークが割り当てられる。「-p」オプションでホストのネットワークをコンテナに割り当てる。
- **docker container inspect コマンド**：コマンドでコンテナ詳細を確認できる。その中の NetworkSettings 部分がネットワーク設定であり、ここでコンテナの IP アドレスを確認できる。この IP アドレスで、コンテナ間で通信することができる。
- **コンテナ名で通信するためのネットワーク設定**：既定のネットワーク設定の状態では、コンテナ同士で IP アドレスによる接続しかできず、コンテナ名での通信はできない。コンテナ名で通信するにはネットワーク設定を新規作成しコンテナに割り当てる必要がある。docker network create コマンドで作成でき、コンテナ作成時に「--net」オプションで割り当てる。作成済みのコンテナに対しても、docker network connect[disconnect]コマンドで接続切断ができる。作成したネットワークに存在するコンテナ同士はコンテナ名で通信することができる。

# ７章：Docker Compose

この章では、DockerCompose について学んだ

DockerCompose はコンテナの起動方法やボリューム、ネットワーク構成などを書いた定義ファイルを用意しておき、その通りまとめて実行する方法。

アプリケーションを管理するうえで、1 つのコンテナに必要なリソースを全てを詰め込むのではなく、複数のコンテナに分けて AP サーバー、Web サーバー、DB サーバーなどを管理するほうが効率的。各リソースを個別のコンテナに分けることで、それぞれを独立してスケーリングできる（例えば、Web サーバーが高負荷になった場合でも、Web サーバーのコンテナだけを複製できる）。また、それぞれのコンテナが 1 つの役割を持つ状態では、障害の影響範囲も限定されメンテナンス性が向上する。複数コンテナの管理に加えて、ボリューム、ネットワークなど関連のある複数の設定をまとめて管理するために DockerCompose を使用する。

1 つのコンテナに全てを詰め込むのではなく、複数のコンテナに分けてコンテナをまとめて管理する理由は、アプリケーションの構造や機能を明確にし、より疎結合なアーキテクチャを促進するためである。

1 つのコンテナ内で全てを実行するというアプローチは、以下のような問題を引き起こす：

- 単一責任の原則（Single Responsibility Principle）の違反
- スケーリングの難しさ
- 再利用性と移植性の低下

DockerCompose は python 製のツールであり、DockerEngine とは別なので python と pip をインストールし、pip コマンドを使って DockerCompose のインストールが必要だったが、現在では docker コマンドに統合されたためインストールは不要。

docker-compose.yml ファイルには YAML 形式で記述する。docker コマンドとは別で docker-compose コマンドがあり、作成したファイルを元にコンテナの起動や停止を行う。docker-compose は docker コマンドを手入力する作業をまとめたツールなので、コンテナの起動の仕組み自体が代わるものではない。docker-compose コマンドは実行時点の yml ファイルを元に動作するので、起動後に yml を編集して起動コンテナ部分の記述を削除などすると停止を実行しても停止されないといったことが起こるので注意が必要。docker-compose コマンドでもコンテナを指定することで個別のコンテナ操作ができる。

yml ファイルは以下のような基本構成で作成する：

- サービス：コンテナの設定
- ネットワーク：サービスが参加するネットワーク定義
- ボリューム：サービスが使用するボリューム定義

# ８章：イメージを自作する

この章では、Docker イメージの自作について学んだ

ここまでは DockerHub で公開されている汎用的なイメージを取得し、
それに対してパッケージのインストールなどして自身の環境にあったイメージを作成していた

自作のイメージを作れば、毎回汎用イメージから作り上げる必要が無く、自身が必要な環境を使いまわすことができる。
チームの場合は、イメージをチームで共有することで同じ環境を共有できる。

自作イメージの作成はコンテナからの作成と Dockerfile からの作成方法がある。
コンテナからの作成は、起動しているコンテナに対して docker commit コマンドを実行することで、
その時点のコンテナの状態をイメージとして作成することができる。
この方法では、どのような構成でイメージが作成されたのかわからないというデメリットがある。
Dockerfile からの作成はベースとなるイメージとそのイメージに対してどのような操作をするのかを記述し、このファイルを元にイメージを作成する。
ファイルに構成が記述されるので、構成が明確になり、配布もしやすい。基本的にこちらでイメージの作成を行う。

Dockerfile からのイメージ作成は、docker build コマンドを実行して Dockerfile の内容を元にイメージを作成する。
作成したイメージを配布するために、

- docker save：イメージを tar 形式のファイルに変換してバックアップする。
- docker load：save で保存した tar 形式のファイルをイメージとして取り込む。
  コマンドを使用する。

コンテナイメージの共有の為、プライベートリポジトリとして DockerHub や AWS ECR を使用する。
イメージファイルのやり取りで受け渡しすることもできるが、プライベートリポジトリとして利用できるサービスを使用することで、
docker pull コマンドでイメージをダウンロードすることができる。
細かい操作はサービスごとに異なるが、それぞれアカウントを作成してローカルで作成したイメージを docker push コマンドでアップロードする流れ。
