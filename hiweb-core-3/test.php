<?php

	hiweb\errors\display::enable();

	//POPT TYPE
	$post_type = add_post_type('test');
	$post_type->public_(true);
	$post_type->supports()->title()->thumbnail();

	add_field_content( 'test' )->label('Поле для контента')->LOCATION()->POST_TYPES('test');