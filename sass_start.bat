cd /d %~dp0
:: ���݂̃f�B���N�g���Ɉړ�

sass --style compact --watch _sass:css --cache-location .sass-cache --compass
:: �y--style�zCSS�̃t�H�[�}�b�g
:: :expanded	{} �ŉ��s����`�B�悭�݂� CSS �̋L�q�`���͂���ł��B�ǐ��������B
:: :nested		Sass �t�@�C���̃l�X�g�����̂܂܈����p�����`�B
:: :compact		�Z���N�^�Ƒ����� 1 �s�ɂ܂Ƃ߂ďo�́B�ǐ���߁B
:: :compressed	���k���ďo�́i�S�Ẳ��s�E�R�����g���g���c���j�B�ǐ��͓����̂āB
:: �y--watch�zscss�t�@�C���̎����Ď�
:: �ysass:html/css�zsass�t�@�C���̏ꏊ:css�t�@�C���̏����o����
:: �y--cache-location�z�L���b�V���t�@�C���̕ۑ���

