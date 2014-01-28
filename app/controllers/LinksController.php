<?php

class LinksController extends BaseController {
	public function show() {
		$links = Settings::get('links');

		$processed = array();

		$link_lines = explode("\n", $links);

		$pattern = '/\{.*\}/';

		$search_sites = array();

		foreach ($link_lines as $line) {
			$line = trim($line);
			preg_match($pattern, $line, $matches, PREG_OFFSET_CAPTURE);

			if(isset($matches[0]))
			{
				$match_string = $matches[0][0];
				$match_string = str_replace('"', '', $match_string);
				$match_string = str_replace('{', '', $match_string);
				$match_string = str_replace('}', '', $match_string);
				$line_parts = explode('|', $match_string);

				$todo = trim($line_parts[0]);

				if ($todo  == 'search_sites') {
					$search_sites = explode(',', trim($line_parts[1]));
				} else if ($todo == 'search') {
					$search_string = $line_parts[1];
					$build_link = '<p>' . $search_string  ;

					foreach($search_sites as $site) {
						$site_info = explode('::', $site);
						$build_link .= ' <a href="' . $site_info[0] . $search_string . '">' . $site_info[1] . '</a>';
					}

					$processed[] = $build_link;
				}

			}
			else
			{
				$processed[] = $line;
			}
		}

		$processed_text = implode("\n", $processed);

		$this->layout->title = 'Links';
		$this->layout->nest('content','links.show', array('links' => $processed_text ) );
	}

	public function edit() {
		$links = Settings::get('links');
		$this->layout->title = 'Edit Links';
		$this->layout->nest('content','links.edit', array('links' => $links));
	}

	public function save() {
		$links = Input::get('links');
		Settings::put('links', $links);
		return Redirect::to('/links');
	}
}