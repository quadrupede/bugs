<?php

class Todo_Controller extends Base_Controller {
	
	public function get_index()
	{
		// @TODO Make configurable. Global or per-user?
		$status_codes = array();
		$statuses = \Project\Issue\Status::order_by('workflow_order', 'ASC')->get();
		foreach( $statuses as $status)
		{
			$status_codes[$status->id] = __('tinyissue.label_'.$status->name);
		}

		// Ensure we have an entry for each lane. 
		$lanes = array();
		foreach ($status_codes as $index => $name) {
			$lanes[$index] = array();
		}
    
		// Load todos into lanes according to status.
		$todos = Todo::load_user_todos();
		foreach ($todos as $todo) {
			$lanes[$todo['status']][] = $todo;
		}
		
		return $this->layout->with('active', 'todo')->nest('content', 'todo.index', array(
			'lanes'   => $lanes,
			'status'  => $status_codes,
			'columns' => count($status_codes),
		));
	}
}
