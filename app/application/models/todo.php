<?php

class Todo extends Eloquent
{
	public static $table = 'users_todos';

	/**
	 * Loads a todo if it exists and belongs to the given user.
	 */
	public static function load_todo( $issue_id = 0, $user_id = 0 )
	{
		if (!$user_id)
		{
			$user_id = Auth::user()->id;
		}

		$todo = Todo::where('issue_id', '=', $issue_id)->where('user_id', '=', $user_id)->first();
		if (empty($todo))
		{
			return false;
		}
		else
		{
			return $todo;
		}
	}

	/**
	 * Load all a user's todos, deleting any for issues that have been reassigned.
	 */
	public static function load_user_todos( $user_id = 0 )
	{
		$return = array();
		if (!$user_id)
		{
			$user_id = Auth::user()->id;
		}

		$todos = Todo::where('user_id', '=', $user_id)->order_by('updated_at', 'DESC')->get();
		foreach ($todos as $todo)
		{
			Todo::load_todo_extras($todo);

			// Close the todo if the issue has been closed.
			if ($todo->issue_status != \Project\Issue\Status::STATUS_CLOSED)
			{
				$todo->status_id = \Project\Issue\Status::STATUS_CLOSED;
				Todo::update_todo($todo->issue_id, 0);
			}

			// Remove the todo if the issue has been reassigned.
			if ($todo->attributes['assigned_to'] == $user_id)
			{
				$return[$todo->attributes['id']] = $todo->attributes;
			}
			else
			{
				Todo::remove_todo($todo->issue_id);
			}
		}

		return $return;
	}

	/**
	 * Load a todo
	 *
	 * @param $todo
	 *
	 * @internal param int $task_id
	 * @return array
	 */
	public static function load_todo_extras( &$todo )
	{
		// We need the issue and project names for display, status and 
		// assigned_to for validity.
		$issue = Project\Issue::find($todo->issue_id);
		if (!empty($issue))
		{
			$todo->assigned_to  = $issue->attributes['assigned_to'];
			$todo->issue_name   = $issue->attributes['title'];
			$todo->issue_status = $issue->attributes['status_id'];
			$todo->issue_link   = $issue->to();

			$project            = Project::find($issue->attributes['project_id']);
			$todo->project_name = $project->attributes['name'];
			$todo->project_link = $project->to();
		}

		// If issue has been deleted, force deletion of todo.
		else
		{
			$todo->assigned_to  = 0;
			$todo->issue_status = 0;
		}
	}

	/**
	 * Add a new todo
	 *
	 * @param int $issue_id
	 *
	 * @internal param int $user_id
	 * @return array
	 */
	public static function add_todo( $issue_id = 0 )
	{
		$user_id = Auth::user()->id;

		// Ensure user is assigned to issue.
		$issue = Project\Issue::load_issue($issue_id);
		if (empty($issue) || $issue->assigned_to !== $user_id)
		{
			return array(
				'success' => false,
				'errors'  => __('tinyissue.todos_err_add'),
			);
		}

		// Ensure issue is not already a task.
		$count = Todo::where('issue_id', '=', $issue_id)->where('user_id', '=', $user_id)->count();
		if ($count > 0)
		{
			return array(
				'success' => false,
				'errors'  => __('tinyissue.todos_err_already'),
			);
		}

		$todo           = new Todo;
		$todo->user_id  = $user_id;
		$todo->issue_id = $issue_id;
		$todo->status   = 1;
		$todo->save();

		return array(
			'success' => true,
		);
	}

	/**
	 * Delete a todo
	 *
	 * @param int $issue_id
	 *
	 * @internal param int $user_id
	 * @internal param int $task_id
	 * @return array
	 */
	public static function remove_todo( $issue_id = 0 )
	{
		$user_id = Auth::user()->id;

		$todo = Todo::load_todo($issue_id, $user_id);
		if (!$todo)
		{
			return array(
				'success' => false,
				'errors'  => __('tinyissue.todos_err_loadfailed'),
			);
		}

		$todo->delete($todo);

		return array(
			'success' => true
		);
	}

	/**
	 * Update a todo
	 *
	 * @param int $issue_id
	 * @param int $new_status
	 *
	 * @internal param int $user_id
	 * @return array
	 */
	public static function update_todo( $issue_id = \Project\Issue\Status::STATUS_CLOSED, $new_status = \Project\Issue\Status::STATUS_OPENED )
	{
		$user_id = Auth::user()->id;

		$todo = Todo::load_todo($issue_id, $user_id);
		if (!$todo)
		{
			return array(
				'success' => false,
				'errors'  => __('tinyissue.todos_err_loadfailed'),
			);
		}

		// Sanity check on status value.
		// @TODO Handle N configurable status codes
		$new_status = (int)$new_status;
		\Project\Issue\Status::count();
		if ($new_status >= 0 && $new_status <= 3)
		{
			$todo->status = $new_status;
			$todo->save();

			// Close issue if todo is moved to closed lane.
			if ($new_status == \Project\Issue\Status::STATUS_CLOSED)
			{
				$issue = Project\Issue::find($issue_id);
				if (!empty($issue))
				{
					$issue->change_status(\Project\Issue\Status::STATUS_CLOSED);
				}
			}

			return array(
				'success' => true
			);
		}
		else
		{
			return array(
				'success' => false,
				'errors'  => __('tinyissue.todos_err_update'),
			);
		}
	}
}
