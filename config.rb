
http_path = "/"
css_dir = "css"
sass_dir = "_sass"
images_dir = "images"
javascripts_dir = "js"

sass_options = {:sourcemap => true,}
enable_sourcemaps = true

# :expanded		{} で改行する形。よくみる CSS の記述形式はこれです。可読性たかし。
# :nested		Sass ファイルのネストがそのまま引き継がれる形。
# :compact		セレクタと属性を 1 行にまとめて出力。可読性低め。
# :compressed	圧縮して出力（全ての改行・コメントをトルツメ）。可読性は投げ捨て。
output_style = :compact

# line_comments：CSS に SCSS での行番号を出力するかどうか。true or false
line_comments = false

# To enable relative paths to assets via compass helper functions. Uncomment:
relative_assets = true

# sass_options = { :debug_info => true }


# min.css make setting

# output_style = :expanded

# on_stylesheet_saved do |filename|
# 	if File.exists?(filename)
# 		minifyFile = filename.gsub('.css', '.min.css')
# 		FileUtils.cp filename, minifyFile
# 		file = File.read minifyFile
# 		colon = ':'
# 		semicolon = ';'
# 		comma = ','
# 		open_brace = ' {'
# 		close_brace = '}'
# 		file.gsub!(/\n/,' ')
# 		file.gsub!(/\/\*.*?\*\//m,'')
# 		file.gsub!(/\s*:\s*/,colon)
# 		file.gsub!(/\s*;\s*/,semicolon)
# 		file.gsub!(/\s*,\s*/,comma)
# 		file.gsub!(/\s*\{\s*/,open_brace)
# 		file.gsub!(/\s*\}\s*/,close_brace)
# 		file.gsub!(/\s\s+/,' ')
# 		File.open(minifyFile, 'w+') do |f|
# 			f << file
# 		end
# 	end
# end