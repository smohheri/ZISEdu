<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('zisedu_build_paging')) {
	/**
	 * Build and initialize CI3 pagination with Bootstrap-friendly markup.
	 *
	 * @param array $options
	 * @return array
	 */
	function zisedu_build_paging($options = array())
	{
		$ci = get_instance();
		$ci->load->library('pagination');

		$base_url = isset($options['base_url']) ? $options['base_url'] : site_url();
		$total_rows = isset($options['total_rows']) ? (int) $options['total_rows'] : 0;
		$per_page = isset($options['per_page']) ? max(1, (int) $options['per_page']) : 10;
		$uri_segment = isset($options['uri_segment']) ? (int) $options['uri_segment'] : 3;
		$num_links = isset($options['num_links']) ? (int) $options['num_links'] : 2;
		$use_page_numbers = isset($options['use_page_numbers']) ? (bool) $options['use_page_numbers'] : TRUE;
		$page_query_string = isset($options['page_query_string']) ? (bool) $options['page_query_string'] : FALSE;
		$query_string_segment = isset($options['query_string_segment']) ? $options['query_string_segment'] : 'page';
		$reuse_query_string = isset($options['reuse_query_string']) ? (bool) $options['reuse_query_string'] : TRUE;

		$current_page = 1;
		if ($page_query_string) {
			$current_page = (int) $ci->input->get($query_string_segment, TRUE);
		} else {
			$current_page = (int) $ci->uri->segment($uri_segment);
		}
		if ($current_page < 1) {
			$current_page = 1;
		}

		$total_pages = (int) ceil($total_rows / $per_page);
		if ($total_pages > 0 && $current_page > $total_pages) {
			$current_page = $total_pages;
		}

		$offset = $use_page_numbers ? max(0, ($current_page - 1) * $per_page) : max(0, $current_page);

		$config = array(
			'base_url' => $base_url,
			'total_rows' => $total_rows,
			'per_page' => $per_page,
			'uri_segment' => $uri_segment,
			'num_links' => $num_links,
			'use_page_numbers' => $use_page_numbers,
			'page_query_string' => $page_query_string,
			'query_string_segment' => $query_string_segment,
			'reuse_query_string' => $reuse_query_string,
			'full_tag_open' => '<nav aria-label="Pagination"><ul class="pagination pagination-sm mb-0">',
			'full_tag_close' => '</ul></nav>',
			'first_link' => '&laquo;',
			'last_link' => '&raquo;',
			'next_link' => '&rsaquo;',
			'prev_link' => '&lsaquo;',
			'first_tag_open' => '<li class="page-item">',
			'first_tag_close' => '</li>',
			'last_tag_open' => '<li class="page-item">',
			'last_tag_close' => '</li>',
			'next_tag_open' => '<li class="page-item">',
			'next_tag_close' => '</li>',
			'prev_tag_open' => '<li class="page-item">',
			'prev_tag_close' => '</li>',
			'cur_tag_open' => '<li class="page-item active"><span class="page-link">',
			'cur_tag_close' => '</span></li>',
			'num_tag_open' => '<li class="page-item">',
			'num_tag_close' => '</li>',
			'attributes' => array('class' => 'page-link')
		);

		if (!empty($options['config']) && is_array($options['config'])) {
			$config = array_merge($config, $options['config']);
		}

		$ci->pagination->initialize($config);

		return array(
			'links' => $ci->pagination->create_links(),
			'offset' => $offset,
			'limit' => $per_page,
			'current_page' => $current_page,
			'total_pages' => $total_pages,
			'total_rows' => $total_rows
		);
	}
}

