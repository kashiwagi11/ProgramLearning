# 1 章：ASP.NET MVC の概要

この章では、ASP.NET MVC についての概要を学んだ。  
ASP.NET：.NET Framework を使用し、IIS で動作する（Windows 環境のみ）  
ASP.NETCore：.NET Core を使用し、Windows 以外の環境でも動作する（クロスプラットフォーム）

VisualStudio では ASP.NETCore アプリケーション作成時にプロジェクトテンプレートとして、  
MVC パターンを用いた Web アプリケーションのテンプレートを使用することができる。

## MVC パターンの概要

**Model**：ビジネスロジック(データの検証、データの処理、データベースとのやりとりなど)やデータを管理する。アプリケーションが扱うデータや、そのデータに対する操作を担当する。  
**View**：画面表示を担当する。Model からデータを取得し、それを画面表示に適用する。  
**Controller**：ユーザーからの入力を管理し、その入力に基づいて Model 操作を実行し、結果を View に反映させる  
MVC パターンの利点は、アプリケーションの構造が明確になり、機能やロジックの修正・更新が容易になること。  
View と Model が分離されているため、UI を変更しても Model に影響を与えずに済む。  
このパターンを使用してテンプレートを使用した開発方法について解説が進む。

## 環境作成

プロジェクトを実行する環境の作成を実施した

- VisualStudio 最新版のインストール
- SQLServer のインストール
- SSMS(SQLServerManagementStudio)のインストール
- dotnet tool のインストール

基本的にツールをダウンロードしてインストールするのみだが、  
SQLServer の設定でつまづいた。以下で解決した。

- TCP/IP 接続の許可がない  
  SQLServer 構成マネージャから SQLEXPRESS の TCP/IP を有効化  
  IPALL オプションに TCP ポート「1433」を設定し動的ポートの値を空に設定
- SQLServerBrowser の有効化  
  初期値で Windows のサービス設定で無効にされているので有効にして起動する

# 2 章：スキャフォールディングの利用

この章では、スキャフォールディング機能について学んだ。  
ASP.NET Core MVC では DB 操作の CRUD 機能を自動生成するスキャフォールディング機能がある。

テーブルを元にした Model クラスの作成、基本的な CRUD 操作（作成、読み取り、更新、削除）を処理するメソッドを含む Controller クラスと
各 CRUD 操作に対応する View クラスを自動生成できる。

## 新規スキャフォールディングアイテムの追加から controller を自動作成する箇所でつまづいた

SSMS でテーブルに主キーを設定していなかったため、自動生成がエラーになった。  
Program.cs（旧バージョンでは Startup.cs にあるらしい）の AddDbContext メソッドで DB 接続用の Models（生成した Context）が登録できていないことが原因だった。

`builder.Services.AddDbContext<ApplicationDbContext>`
ApplicationDbContext の部分を生成した Context 名で置き換えてあげる必要があった  
※このコードでは AddDbContext メソッドを使って TestdbContext をサービスとして登録しています。UseSqlServer メソッドは、データベースプロバイダとして SQL Server を使用し、接続文字列は appsettings.json ファイルから取得します。

プロジェクト作成時に認証設定を入れた場合は、AddEntityFrameworkStores<ApplicationDbContext>();も DBContext 指定する必要がある

## Razer 構文

拡張子 cshtml の HTML 拡張したファイル　 HTML と混ぜて C#の文法が使える  
ASP.NET Core では Razor と呼ばれる HTML を拡張した構文を使用して画面を作成する。  
Razor は C#と HTML を組み合わせて作成することができる。  
View（HTML）と Controller（C#）の加工データを共存できるため、データの表示と加工を分離して作成することができる。

# 3 章：Model の活用

この章では ASP.NETCoreMVC の Model について学んだ。

Model の作成は EntityFramework（データベースと c#の OR マッピングを行う）を使用して、

- データベースから Model を作成する
- ソースコードからデータベースを作成する  
  2 つの方法がある。

## データベースから Model を作成する

スキャフォールディング機能であらかじめ作成したテーブルを指定し、自動生成する  
この時、テーブル結合などの情報は外部キー設定を行うことで自動生成に反映することができる。

## ソースコードからデータベースを作成する

ソースコードベースで Model を作成することをコードファーストと呼ぶ。  
コードファーストでは Model クラスのデータ構造部分を自分で作成し、それを元に DB を作成する。

## アノテーションによる制御

Model クラスにはアノテーションを設定できる。  
入力の制限や表示時のフォーマット指定をするプロパティに対する属性指定をアノテーションと呼ぶ。  
[Display(Name="名前")]のような形式でクラスのプロパティに対して指定でき、クライアント側に反映させることができる。

- 項目名の設定
- 文字数制限
- 入力フォーマットの指定  
  など様々な設定オプションがある

# 4 章：View の活用

この章では Razor 構文を使用した View の利用について学んだ。

ASP.NET Core では Razor と呼ばれる HTML を拡張した構文を使用して画面を作成する。  
Razor は C#と HTML を組み合わせて作成することができる。  
View（HTML）と Controller（C#）の加工データを共存できるため、データの表示と加工を分離して作成することができる。

- Controller（C#）：データ加工
- View（HTML）：加工データを使用した画面表示

Razor 構文による View の作成では、C#で複雑なロジックを書くこともできるが、View のソースが大きくなり修正が大変になってしまう。  
そのため、Controller で処理した結果を ViewData コレクションを使用して View に渡すことで View 構造を簡潔にまとめることが望ましい。  
ViewData は View と Controller 間でデータをやり取りするためのインスタンスでキーと値のペアでデータ格納できる。

スキャフォールディング機能で自動生成される View も Razor 構文を使用している。

View(cshtml)内で、@{}で囲むことで、その範囲は c#のコードを書くことができる。

## ViewData

ViewData は ASP.NET MVC と ASP.NET Core MVC で使用される特殊なプロパティで、コントローラとビュー間でデータを渡すためのものです。ViewData はディクショナリ型（キーと値のペアのコレクション）で、オブジェクトを動的に扱うことができます。  
コントローラ内で ViewData に値を設定すると、その値は対応するビューからアクセス可能になります。これは、ビューが必要とするデータをコントローラからビューに渡すための一つの方法です。  
`@{
ViewData["Title"] = "Details";
}
`のように扱うことができる。
ViewData は型安全ではないため、誤った型で値を取り出そうとすると実行時エラーが発生します。また、存在しないキーで値を取り出そうとすると null が返されます。

## @Html.DisplayFor や@Html.EditorFor などのヘルパーメソッドの引数

modelItem は、Razor ビューエンジンの@Html.DisplayFor や@Html.EditorFor などのヘルパーメソッドの引数として使用されるラムダ式の一部  
このラムダ式は、ビューモデルの特定のプロパティを指定するために使用されます。  
modelItem は単にラムダ式の一部で、ビューモデルの特定のプロパティを指定するための一時的な変数です。  
つまり、ただの一時変数だった

## ラムダ式のおさらい

ラムダ式は、無名関数またはインライン関数とも呼ばれ、関数の定義を簡潔に書くための表現方法です。  
`(parameters) => expression`  
ここで、parameters は関数のパラメーターを、expression は関数の本体（実行されるコード）を表します。  
`(x, y) => x + y`  
このラムダ式は、x と y という 2 つのパラメーターを取り、それらを加算するという操作を表しています。

# 5 章：Controller の活用

この章では Controller クラスの Action メソッドについて学んだ。

ASP.NET Core の Action メソッドは、HTTP リクエストを受け取るエンドポイントとして呼び出される。  
クライアント（ブラウザなど）からの HTTP リクエストが特定の URL パスに送信されると、それに対応するアクションメソッドが実行される。

- ルートパラメータ：/users/{id}
- クエリパラメータ：/users?id=
- リクエストボディのデータ

これらの値がアクションメソッドへの引数として渡される。  
Action メソッドは IActionResult インターフェースのオブジェクトである View、Redirect、JsonData などを返し、クライアントへのレスポンスに反映する。

Action メソッドには非同期型と同期型がある。

- 同期型（Synchronous）: メソッドの実行が完了するまで、そのメソッドを呼び出したスレッドは他のタスクを実行することができない。つまり、そのメソッドが終了するまで待機する必要がある。
- 非同期型（Asynchronous）: メソッドの実行が開始された後、そのメソッドを呼び出したスレッドは他のタスクを同時に実行することができる。つまり、そのメソッドが終了するのを待たずに他の作業を進めることができる。

非同期メソッドは、特にデータベースへのクエリや外部 API へのリクエストなど、待機時間が発生しうる処理に対して有効  
非同期メソッドは、"async"キーワードが使用され、Task<T>型を返す。

# 6 章：List-Detail の関係

この章では一覧ページ(Index)と詳細ページ(Details)を組み合わせた Web アプリケーションを学んだ。  
今まで学んできたスキャフォールディング機能で 4 つのテーブルの連携を行う。

Model クラスからテーブルを作成するため、コードファーストでテーブル作成を行う  
本、著者、出版社、著者の県テーブルでそれぞれデータを持たせている。

View を修正し、表示順序の修正  
Model にアノテーションを設定し、表示名や入力制限の設定  
タグヘルパーを使用して、View から Controller の Action メソッドへのリンクを設定し、本の一覧ページから詳細ページへ、詳細ページから著者や出版社の詳細ページへなどのリンクを作成した

本の一覧ページではページングと検索機能を作成した

## ページング

大量のデータを一度に表示するのではなく、小さな「ページ」に分割して表示する手法。  
ページングを使用すると、記事は例えば一ページあたり 10 記事ずつに分割され、ユーザーはそれぞれのページを順に閲覧することができます。  
各ページの下部には通常、「前へ」「次へ」のようなリンクがあり、これをクリックすることでユーザーは前後のページに移動できます。

表示最大数を決め、Skip（任意件数を飛ばしてデータ取得）、Take（指定件数を取得）メソッドを使用してページごとに表示するリストを制限することでページングを実装した

## LINQ を使用した Filter 機能

LINQ は.NET の機能の一つで、データの検索・フィルタリング・変換などの操作を行うためのライブラリ。  
DB やコレクション(配列や List）、XML に対して SQL のような記述でデータ操作ができる。  
クエリ構文：from x in list select x  
メソッド構文：list.Select(x => x)  
のように記法が 2 種類あり Select、from、where などのキーワードでデータ操作を行う。  
LINQ クエリはコンパイル時に型チェックが行われるので、要素の型に型安全にアクセスできる。
