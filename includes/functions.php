<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 11.10.2018
	 * Time: 19:37
	 */

	if( !function_exists( 'the_form' ) ){

		/**
		 * @param null $form_post_id
		 * @return \hiweb_theme\widgets\forms\form
		 */
		function get_form( $form_post_id = null ){
			return \hiweb_theme\widgets\forms::get( $form_post_id );
		}
	}

	if( !function_exists( 'get_the_form' ) ){

		/**
		 * @return \hiweb_theme\widgets\forms\form
		 */
		function get_the_form(){
			return \hiweb_theme\widgets\forms::get_the_form();
		}
	}

	if( !function_exists( 'get_the_form_id' ) ){
		/**
		 * @return int|null
		 */
		function get_the_form_id(){
			return \hiweb_theme\widgets\forms::get_the_ID();
		}
	}

	if( !function_exists( 'have_form_inputs' ) ){
		/**
		 * @return bool
		 */
		function have_form_inputs(){
			return \hiweb_theme\widgets\forms::get_the_form()->have_inputs();
		}
	}

	if( !function_exists( 'the_form_input' ) ){
		/**
		 * @return \hiweb_theme\widgets\forms\inputs\input
		 */
		function the_form_input(){
			return \hiweb_theme\widgets\forms::get_the_form()->the_input();
		}
	}

	if( !function_exists( 'the_form_input_html' ) ){
		/**
		 * Print the form input HTML
		 */
		function the_form_input_html(){
			\hiweb_theme\widgets\forms::get_the_form()->get_the_input()->the();
		}
	}

	///Languages Tools
	if( !function_exists( 'get_languages' ) ){
		/**
		 * @return \hiweb_theme\tools\languages\language[]
		 */
		function get_languages(){
			return \hiweb_theme\tools\languages::get_languages();
		}
	}

	if( !function_exists( 'get_current_language' ) ){
		/**
		 * @return \hiweb_theme\tools\languages\language
		 */
		function get_current_language(){
			return \hiweb_theme\tools\languages::get_current_language();
		}
	}

	if( !function_exists( 'get_language_date' ) ){
		/**
		 * @param $timestamp
		 * @return string
		 */
		function get_language_date( $timestamp ){
			return \hiweb_theme\tools\languages::get_date( $timestamp );
		}
	}