Encoding.default_external = "utf-8"

http_path = "/"
css_dir = "css"
sass_dir = "_scss"
images_dir = "img"
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

cache = true
asset_cache_buster :none
sass_options = { :debug_info => false }

# キャッシュバスターをタイムスタンプからMD5ハッシュ(10文字)に変更する
asset_cache_buster do |path, file|
  if File.file?(file.path)
    Digest::MD5.hexdigest(File.read(file.path))[0, 8]
  else
    $stderr.puts "WARNING: '#{File.basename(path)}' was not found (or cannot be read) in #{File.dirname(file.path)}"
  end
end

on_stylesheet_saved do |filename|
    puts "    ----------------------- " + File.mtime(filename).strftime("%Y-%m-%d %H:%M:%S") + " --------"
  # Growl.notify {
  #    self.message = "#{File.basename(filename)} updated!"
  #    self.icon = '/path/to/success.jpg'
  #  }
end

# autoprefixer csso
# gem install autoprefixer-rails
# gem install csso-rails
require 'autoprefixer-rails'
require 'csso'
on_stylesheet_saved do |file|
  css = File.read(file)
  File.open(file, 'w') do |io|
    # io << AutoprefixerRails.compile(css, ['last 3 versions', 'ie 8', 'ios 6', 'android 2.3'])
    io << AutoprefixerRails.process(css, browsers:['last 3 versions', 'ie 10', 'ios 6', 'android 4'])
    # io << AutoprefixerRails.process(css)
    # io << Csso.optimize( AutoprefixerRails.process(css, browsers:['last 1 version', 'ie 8', 'ios 6', 'android 2.3']) )
  end
end


# キャッシュバスター文字列をトリミング
# on_sprite_saved do |filename|
# 	if File.exists?(filename)
# 		FileUtils.cp filename, filename.gsub(%r{-s[a-z0-9]{10}\.png$}, '.png')
# 	end
# end
# on_stylesheet_saved do |filename|
# 	if File.exists?(filename)
# 		css = File.read filename
# 		File.open(filename, 'w+') do |f|
# 			f << css.gsub(%r{-s[a-z0-9]{10}\.png}, '.png')
# 		end
# 	end
# end

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