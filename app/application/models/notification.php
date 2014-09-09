<?php

class Notification extends Eloquent {

	public static $table = 'users_notifications';


	/**
	 * Add a new notification
	 *
	 * @param int       $user_id
	 * @param int       $issue_id
	 * @return array
	 */
	public static function add_notification($issue_id = 0)
	{
		$user_id = Auth::user()->id;

		// Ensure user has the right to view issue.
		if(!Auth::user()->permission('issue-view')){
			return "no can do.";
		}

		// Delete all previous subscriptions to this issue from that user
		$notification = Notification::load_notifications($issue_id, $user_id);
		if($notification){
			$notification->delete();
		}

		$notification = new Notification;
		$notification->user_id  = $user_id;
		$notification->issue_id = $issue_id;
		$notification->save();

		return array(
			'success' => TRUE,
		);
	}


	public static function remove_notification($issue_id = 0)
	{
		$user_id = Auth::user()->id;
		// Ensure user has the right to view issue.
		if(!Auth::user()->permission('issue-view')){
			return "no can do.";
		}
		$notification = Notification::load_notifications($issue_id);
		if($notification){
			$notification->delete();
		}
		return array(
			'success' => TRUE,
			'message' => "Notification removed"
		);
	}

	public static function send_notifications($issue_id = 0)
	{
		// TODO
		return "notifications sent";
	}

	/**
	 * load current notifications
	 *
	 * @param int       $user_id
	 * @param int       $issue_id
	 * @return array
	 */
	public static function load_notifications($issue_id, $user_id=0)
	{
		if($user_id !=0){
			$notifications = Notification::where('user_id', '=', $user_id)->where('issue_id','=',$issue_id)->get();
		}else{
			$notifications = Notification::where('issue_id','=',$issue_id)->get();
		}
		return $notifications;

	}


}
