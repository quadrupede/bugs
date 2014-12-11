<?php
/**
 * Created by PhpStorm.
 * User: jd
 * Date: 02/12/2014
 * Time: 21:13
 */

namespace Project\Issue;


class Status extends  \Eloquent {

	const STATUS_OPENED = 1;
	const STATUS_CLOSED = 2;
	const STATUS_TESTING = 3;
	const STATUS_RESOLVING = 4;
	const STATUS_MUSTFIX = 5;

	public static $table = 'projects_issues_status';
	public static $timestamps = true;


	/**
	 * Returns all issues related to project
	 *
	 * @return mixed
	 */
	public function issues()
	{
		return $this->has_many('Project\Issue');
	}
}