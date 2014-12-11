<?php

class Project_Controller extends Base_Controller
{
	public $layout = 'layouts.project';

	public function __construct()
	{
		parent::__construct();

		$this->filter('before', 'project');
		$this->filter('before', 'permission:project-modify')->only('edit');
	}

	/**
	 * Display activity for a project
	 * /project/(:num)
	 *
	 * @return View
	 */
	public function get_index()
	{
		return $this->layout->nest(
			'content', 'project.index', array(
				         'page'            => View::make(
						         'project/index/activity', array(
							                                 'project'  => Project::current(),
							                                 'activity' => Project::current()->activity(10)
						                                 )
					         ),
				         'active'          => 'activity',
				         'open_count'      => Project::current()->count_open_issues(),
				         'closed_count'    => Project::current()->count_closed_issues(),
				         'resolving_count' => Project::current()->count_issues(\Project\Issue\Status::STATUS_RESOLVING),
				         'testing_count'   => Project::current()->count_issues(\Project\Issue\Status::STATUS_TESTING),
				         'mustfix_count'   => Project::current()->count_issues(\Project\Issue\Status::STATUS_MUSTFIX),
				         'assigned_count'  => Project::current()->count_assigned_issues(),
			         )
		);
	}

	/**
	 * Display issues for a project
	 * /project/(:num)
	 *
	 * @return View
	 */
	public function get_issues()
	{
		Asset::add('tag-it-js', '/app/assets/js/tag-it.min.js', array('jquery', 'jquery-ui'));
		Asset::add('tag-it-css-base', '/app/assets/css/jquery.tagit.css');
		Asset::add('tag-it-css-zendesk', '/app/assets/css/tagit.ui-zendesk.css');

		/* Get what to sort by */
		$sort_by = Input::get('sort_by', '');

		/* Get what order to use for sorting */
		$default_sort_order = ($sort_by == 'updated' ? 'desc' : 'asc');
		$sort_order         = Input::get('sort_order', $default_sort_order);
		$sort_order         = (in_array($sort_order, array('asc', 'desc')) ? $sort_order : $default_sort_order);

		/* Get which user's issues to show */
		$assigned_to = Input::get('assigned_to', '');

		/* Get which tags to show */
		$tags = Input::get('tags', '');

		/* Get which status to show */
		$status = Input::get('status', '');

		/* Build query for issues */
		$issues = \Project\Issue::with('tags');

		if ($tags || $sort_by != 'updated')
		{
			$issues = $issues
				->join('projects_issues_tags', 'projects_issues_tags.issue_id', '=', 'projects_issues.id', 'LEFT')
				->join('tags', 'tags.id', '=', 'projects_issues_tags.tag_id', 'LEFT')
				->join('projects_issues_priority', 'projects_issues_priority.id', '=', 'projects_issues.priority_id', 'LEFT')
				->join('projects_issues_status', 'projects_issues_status.id', '=', 'projects_issues.status_id', 'LEFT')
				->join('users', 'users.id', '=', 'projects_issues.assigned_to', 'LEFT');
		}
		switch ($sort_by)
		{
			case "priority":
				$sort_by_clause = 'projects_issues.priority_id';
				break;
			case "status":
				$sort_by_clause = 'projects_issues_status.workflow_order';
				break;
			case "assigned":
				$sort_by_clause = 'user.lastname';
				break;
			case "weight":
				$sort_by_clause = 'projects_issues.weight';
				break;
			case "title":
				$sort_by_clause = 'projects_issues.title';
				break;
			default :
				$sort_by_clause = 'projects_issues.updated_at';
				break;
		}

		$issues = $issues->where('project_id', '=', Project::current()->id);
		if ($assigned_to)
		{
			$issues = $issues->where('assigned_to', '=', $assigned_to);
		}

		if ($tags)
		{
			$tags_collection = explode(',', $tags);
			$tags_amount     = count($tags_collection);
			$issues          = $issues->where_in('tags.tag', $tags_collection); //->get();
		}

		if ($status)
		{
			if($status == "open")
			{
				$issues = $issues->where('projects_issues_status.is_open', "=", 1); //->get();
			}
			else
			{
				$issues = $issues->where('projects_issues_status.name', "=", $status); //->get();
			}
		}

		$issues = $issues
			->group_by('projects_issues.id')
			->order_by($sort_by_clause, $sort_order);

		if ($tags && $tags_amount > 1)
		{
			// L3
			$issues = $issues->having(DB::raw('COUNT(DISTINCT `tags`.`tag`)'), '=', $tags_amount);
			// L4 $issues = $issues->havingRaw("COUNT(DISTINCT `tags`.`tag`) = ".$tags_amount);
		}

		$issues = $issues->get(array('projects_issues.*'));

		/* Get which tab to highlight */
		if ($assigned_to == Auth::user()->id)
		{
			$active = 'assigned';
		}
		else
		{
			$active = $status;
		}

		/* Get assigned users */
		$assigned_users = array('' => '');
		foreach (Project::current()->users as $user)
		{
			$assigned_users[$user->id] = $user->firstname . ' ' . $user->lastname;
		}

		$status_sort_options = array(
			"updated"  => __("Date de modification"),
			"weight"   => __("Ordre manuel"),
			"status"   => __("Statut"),
			"priority" => __("Priorité"),
			"assigned" => __("Assigné"),
			"title"    => __("Titre"),
		);

		/* Build layout */
		return $this->layout->nest(
			'content', 'project.index', array(
				         'page'            => View::make(
						         'project/index/issues', array(
							                               'status_sort_options' => $status_sort_options,
							                               'sort_order'          => $sort_order,
							                               'assigned_users'      => $assigned_users,
							                               'issues'              => $issues,
						                               )
					         ),
				         'active'          => $active,
				         'open_count'      => Project::current()->count_open_issues(),
				         'closed_count'    => Project::current()->count_closed_issues(),
				         'resolving_count' => Project::current()->count_issues(\Project\Issue\Status::STATUS_RESOLVING),
				         'testing_count'   => Project::current()->count_issues(\Project\Issue\Status::STATUS_TESTING),
				         'mustfix_count'   => Project::current()->count_issues(\Project\Issue\Status::STATUS_MUSTFIX),
				         'assigned_count'  => Project::current()->count_assigned_issues(),
			         )
		);
	}

	/**
	 * Edit the project
	 * /project/(:num)/edit
	 *
	 * @return View
	 */
	public function get_edit()
	{
		return $this->layout->nest(
			'content', 'project.edit', array(
				         'project' => Project::current()
			         )
		);
	}

	public function post_edit()
	{
		/* Delete the project */
		if (Input::get('delete'))
		{
			Project::delete_project(Project::current());

			return Redirect::to('projects')
				->with('notice', __('tinyissue.project_has_been_deleted'));
		}

		/* Update the project */
		$update = Project::update_project(Input::all(), Project::current());

		if ($update['success'])
		{
			return Redirect::to(Project::current()->to('edit'))
				->with('notice', __('tinyissue.project_has_been_updated'));
		}

		return Redirect::to(Project::current()->to('edit'))
			->with_errors($update['errors'])
			->with('notice-error', __('tinyissue.we_have_some_errors'));
	}
}