<?php 

//* Get portfolio client
function ejo_portfolio_get_client( $post_id = null )
{
	//* If no post_id, get current post_id
	if ( empty($post_id) )
		$post_id = get_the_ID();

	//* Get portfolio client
	$client = get_post_meta( $post_id, 'client', true );

	//* return portfolio client
	return (isset($client)) ? $client : '';
}

//* Get portfolio url
function ejo_portfolio_get_url( $post_id = null )
{
	//* If no post_id, get current post_id
	if ( empty($post_id) )
		$post_id = get_the_ID();

	//* Get portfolio url
	$url = get_post_meta( $post_id, 'url', true );

	//* return portfolio url
	return (isset($url)) ? $url : '';
}

//* Get portfolio start_date
function ejo_portfolio_get_start_date( $post_id = null )
{
	//* If no post_id, get current post_id
	if ( empty($post_id) )
		$post_id = get_the_ID();

	//* Get portfolio start_date
	$start_date = get_post_meta( $post_id, 'start_date', true );

	//* return portfolio start_date
	return (isset($start_date)) ? $start_date : '';
}

//* Get portfolio end_date
function ejo_portfolio_get_end_date( $post_id = null )
{
	//* If no post_id, get current post_id
	if ( empty($post_id) )
		$post_id = get_the_ID();

	//* Get portfolio end_date
	$end_date = get_post_meta( $post_id, 'end_date', true );

	//* return portfolio end_date
	return (isset($end_date)) ? $end_date : '';
}

//* Get portfolio location
function ejo_portfolio_get_location( $post_id = null )
{
	//* If no post_id, get current post_id
	if ( empty($post_id) )
		$post_id = get_the_ID();

	//* Get portfolio location
	$location = get_post_meta( $post_id, 'location', true );

	//* return portfolio location
	return (isset($location)) ? $location : '';
}