<?php

class Ajax_Notification_Controller extends Base_Controller {

	public $layout = null;
  
	public function __construct()
	{
		parent::__construct();
	}

	public function get_notify()
	{
    $subscribe = Input::get('subscribe-to-issue');
    $user_id = Input::get('user_id');
    $issue_id = Input::get('issue_id');
	// todo: check that the user has view priviledges on the project

	if($subscribe==="on"){
		// subscribe user to notifications
		$notification = Notification::add_notification($issue_id);
	}else{
		// unsubscribe user from notifications
		$notification = Notification::remove_notification($issue_id);
	}
/*
    foreach ($issues as $index => $id) 

    {
      $issue = Project\Issue::load_issue($id);
      $issue->weight = $index;
      $issue->save();
    }
    

			$subject = sprintf(__('email.reassignment'),$this->title,$project->name);
			$text = \View::make('email.reassigned_issue', array(
				'actor' => \Auth::user()->firstname . ' ' . \Auth::user()->lastname,
				'project' => $project,
				'issue' => $this
			));

			\Mail::send_email($text, $this->assigned->email, $subject);
*/
		//return json_encode($issues);
    
	}
}