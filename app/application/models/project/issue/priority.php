<?php
/**
 * Created by PhpStorm.
 * User: jd
 * Date: 02/12/2014
 * Time: 22:01
 */

namespace Project\Issue;


class Priority extends  \Eloquent {

	public static $table = 'projects_issues_priority';

	/**
	 * Returns all issues related to project
	 *
	 * @return mixed
	 */
	public function issues()
	{
		return $this->has_many('Project\Issue', 'priority_id');
	}


	/**
	 * Build a dropdown of all priorities
	 *
	 * @param  object  $priorities
	 * @return array
	 */
	public static function dropdown($priorities)
	{
		$return = array();

		foreach($priorities as $row)
		{
			$return[$row->id] = __('tinyissue.priority_'.$row->name);
		}

		return $return;
	}

} 