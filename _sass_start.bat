cd /d %~dp0
:: ���݂̃f�B���N�g���Ɉړ�

sass --style compact --watch _sass:css --cache-location .sass-cache --compass

:: --style      CSS�̃t�H�[�}�b�g
:: :expanded    {} �ŉ��s����`�B�悭�݂� CSS �̋L�q�`���͂���ł��B�ǐ��������B
:: :nested      Sass �t�@�C���̃l�X�g�����̂܂܈����p�����`�B
:: :compact     �Z���N�^�Ƒ����� 1 �s�ɂ܂Ƃ߂ďo�́B�ǐ���߁B
:: :compressed  ���k���ďo�́i�S�Ẳ��s�E�R�����g���g���c���j�B�ǐ��͓����̂āB
:: --watch scss�t�@�C���̎����Ď�
:: sass:html/css sass�t�@�C���̏ꏊ:css�t�@�C���̏����o����
:: --cache-location �L���b�V���t�@�C���̕ۑ���

:: sudo gem install sass	Sass�C���X�g�[��
:: sudo gem install sass --pre	Sass�v�����[�X�C���X�g�[��
:: sass --version	Sass�o�[�W�����m�F
:: sass --watch style.scss:style.css	�Ď�
:: sass --debug-info --watch style.scss:style.css	debug-info�����o��
:: sass --sourcemap --watch style.scss:style.css	Source maps�����o��
:: sass --style expanded --sourcemap --watch style.scss:style.css	�A�E�g�v�b�g�X�^�C���w���Source maps�����o��
:: sass_options = {:debug_info => true}	[config.rb] Compass��debug-info�����o��
:: sass --compass --sourcemap --watch style.scss:style.css	Compass��Source maps�����o��