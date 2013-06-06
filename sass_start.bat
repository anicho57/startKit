cd /d %~dp0
:: 現在のディレクトリに移動

sass --style compact --watch _sass:css --cache-location .sass-cache --compass
:: 【--style】CSSのフォーマット
:: :expanded	{} で改行する形。よくみる CSS の記述形式はこれです。可読性たかし。
:: :nested		Sass ファイルのネストがそのまま引き継がれる形。
:: :compact		セレクタと属性を 1 行にまとめて出力。可読性低め。
:: :compressed	圧縮して出力（全ての改行・コメントをトルツメ）。可読性は投げ捨て。
:: 【--watch】scssファイルの自動監視
:: 【sass:html/css】sassファイルの場所:cssファイルの書き出し先
:: 【--cache-location】キャッシュファイルの保存先

