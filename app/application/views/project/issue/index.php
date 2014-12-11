<h3>
	<a href="<?php echo Project::current()->to('issue/new'); ?>"
	   class="newissue"><?php echo __('tinyissue.new_issue'); ?></a>
	<?php if($issue->priority) : ?>
		<label class="label priority priority-<?php echo $issue->priority->name ?>" title="<?php __('tinyissue.priority_'.$issue->priority->name) ?>"></label>
	<?php endif; ?>

	<?php if (Auth::user()->permission('issue-modify')): ?>
		<a href="<?php echo $issue->to('edit'); ?>" class="edit-issue"><?php echo $issue->title; ?></a>
	<?php else: ?>
		<a href="<?php echo $issue->to(); ?>"><?php echo $issue->title; ?></a>
	<?php endif; ?>

	<span><?php echo __('tinyissue.on_project'); ?> <a
			href="<?php echo $project->to(); ?>"><?php echo $project->name; ?></a></span>
</h3>

<div class="pad">

	<?php if (Auth::user()->permission('issue-modify')): ?>

		<ul class="issue-actions">
			<li class="assigned-to">
				<?php echo __('tinyissue.assigned_to'); ?>

				<?php if (Project\Issue::current()->assigned): ?>
					<a href="javascript:void(0);" class="currently_assigned">
						<?php echo Project\Issue::current()->assigned->firstname; ?>
						<?php echo Project\Issue::current()->assigned->lastname; ?>
					</a>
				<?php else: ?>
					<a href="javascript:void(0);" class="currently_assigned">
						<?php echo __('tinyissue.no_one'); ?>
					</a>
				<?php endif; ?>

				<div class="dropdown">
					<ul>
						<li class="unassigned"><a href="javascript:void(0);"
						                          onclick="issue_assign_change(0, <?php echo Project\Issue::current()->id; ?>);"
						                          class="user0<?php echo !Project\Issue::current()->assigned ? ' assigned' : ''; ?>"><?php echo __('tinyissue.no_one'); ?></a>
						</li>
						<?php foreach (Project::current()->users()->get() as $row): ?>
							<li><a href="javascript:void(0);"
							       onclick="issue_assign_change(<?php echo $row->id; ?>, <?php echo Project\Issue::current()->id; ?>);"
							       class="user<?php echo $row->id; ?><?php echo Project\Issue::current()->assigned && $row->id == Project\Issue::current()->assigned->id ? ' assigned' : ''; ?>"><?php echo $row->firstname . ' ' . $row->lastname; ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</li>
			<li class="prioritized-to">
				<?php echo __('tinyissue.priority_to'); ?>

				<?php if (Project\Issue::current()->priority_id): ?>
					<a href="javascript:void(0);" class="currently_prioritized">
						<?php echo __('tinyissue.priority_' . Project\Issue::current()->priority->name); ?>
					</a>
				<?php else: ?>
					<a href="javascript:void(0);" class="currently_prioritized">
						<?php echo __('tinyissue.no_one'); ?>
					</a>
				<?php endif; ?>

				<div class="dropdown">
					<ul>
						<li class="unprioritized"><a href="javascript:void(0);"
						                             onclick="issue_priority_change(0, <?php echo Project\Issue::current()->id; ?>);"
						                             class="prioritize0<?php echo !Project\Issue::current()->priority_id ? ' prioritized' : ''; ?>"><?php echo __('tinyissue.no_one'); ?></a>
						</li>
						<?php foreach (\Project\Issue\Priority::order_by('id')->get() as $row): ?>
							<li><a href="javascript:void(0);"
							       onclick="issue_priority_change(<?php echo $row->id; ?>, <?php echo Project\Issue::current()->id; ?>);"
							       class="prioritize<?php echo $row->id; ?><?php echo Project\Issue::current()->priority && $row->id == Project\Issue::current()->priority->id ? ' prioritized' : ''; ?>"><?php echo __('tinyissue.priority_' . $row->name); ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</li>
			<?php if (Project\Issue::current()->status->id == \Project\Issue\Status::STATUS_OPENED) : ?>
				<li>
					<a href="<?php echo Project\Issue::current()->to('status?status=' . \Project\Issue\Status::STATUS_RESOLVING); ?>"
					   onclick="return confirm('<?php echo __('tinyissue.resolve_issue_confirm'); ?>');"
					   class="close"><?php echo __('tinyissue.resolve_issue'); ?></a>
				</li>
				<li>
					<a href="<?php echo Project\Issue::current()->to('status?status=' . \Project\Issue\Status::STATUS_CLOSED); ?>"
					   onclick="return confirm('<?php echo __('tinyissue.close_issue_confirm'); ?>');"
					   class="close"><?php echo __('tinyissue.close_issue'); ?></a>
				</li>
			<?php elseif (Project\Issue::current()->status->id == \Project\Issue\Status::STATUS_RESOLVING) : ?>
				<li>
					<a href="<?php echo Project\Issue::current()->to('status?status=' . \Project\Issue\Status::STATUS_TESTING); ?>"
					   onclick="return confirm('<?php echo __('tinyissue.test_issue_confirm'); ?>');"
					   class="close"><?php echo __('tinyissue.test_issue'); ?></a>
				</li>
			<?php
			elseif (Project\Issue::current()->status->id == \Project\Issue\Status::STATUS_TESTING) : ?>
				<li>
					<a href="<?php echo Project\Issue::current()->to('status?status=' . \Project\Issue\Status::STATUS_MUSTFIX); ?>"
					   onclick="return confirm('<?php echo __('tinyissue.must_fix_issue_confirm'); ?>');"
					   class="close"><?php echo __('tinyissue.must_fix_issue'); ?></a>
				</li>
				<li>
					<a href="<?php echo Project\Issue::current()->to('status?status=' . \Project\Issue\Status::STATUS_CLOSED); ?>"
					   onclick="return confirm('<?php echo __('tinyissue.close_issue_confirm'); ?>');"
					   class="close"><?php echo __('tinyissue.close_issue'); ?></a>
				</li>
			<?php
			elseif (Project\Issue::current()->status->id == \Project\Issue\Status::STATUS_MUSTFIX) : ?>
				<li>
					<a href="<?php echo Project\Issue::current()->to('status?status=' . \Project\Issue\Status::STATUS_RESOLVING); ?>"
					   class="close"><?php echo __('tinyissue.resolve_issue'); ?></a>
				</li>
			<?php
			else : ?>
				<li>
					<a href="<?php echo Project\Issue::current()->to('status?status=' . \Project\Issue\Status::STATUS_OPENED); ?>"
					   onclick="return confirm('<?php echo __('tinyissue.close_issue_confirm'); ?>');"
					   class="close"><?php echo __('tinyissue.reopen_issue'); ?></a>
				</li>
			<?php endif; ?>
		</ul>
		<div class="clr"></div>
	<?php endif; ?>


	<?php if (!empty($issue->tags)): ?>
		<div id="issue-tags">
			<?php foreach ($issue->tags()->order_by('tag', 'ASC')->get() as $tag): ?>
				<?php echo '<label class="label"' . ($tag->bgcolor ? ' style="background: ' . $tag->bgcolor . '"' : '') . '>' . $tag->tag . '</label>'; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<ul class="issue-discussion">
		<li>
			<div class="insides">
				<div class="topbar">
					<strong><?php echo $issue->user->firstname . ' ' . $issue->user->lastname; ?></strong>
					<?php echo __('tinyissue.opened_this_issue'); ?>  <span
						class="moment"><?php echo strtotime($issue->created_at); ?></span>
				</div>

				<div class="issue">
					<?php echo Project\Issue\Comment::format($issue->body); ?>
				</div>

				<ul class="attachments">
					<?php foreach ($issue->attachments()->get() as $attachment): ?>
						<li>
							<?php if (in_array($attachment->fileextension, Config::get('application.image_extensions'))): ?>
								<a href="<?php echo URL::base() . Config::get('application.attachment_path') . $project->id . '/' . $attachment->upload_token . '/' . rawurlencode($attachment->filename); ?>"
								   title="<?php echo $attachment->filename; ?>"><img
										src="<?php echo URL::base() . Config::get('application.attachment_path') . $project->id . '/' . $attachment->upload_token . '/' . $attachment->filename; ?>"
										style="max-width: 100px;" alt="<?php echo $attachment->filename; ?>"/></a>
							<?php else: ?>
								<a href="<?php echo URL::base() . Config::get('application.attachment_path') . $project->id . '/' . $attachment->upload_token . '/' . rawurlencode($attachment->filename); ?>"
								   title="<?php echo $attachment->filename; ?>"><?php echo $attachment->filename; ?></a>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>

				<div class="clr"></div>
			</div>
		</li>
	</ul>

	<h4><?php echo __('tinyissue.activity'); ?></h4>
	<?php
	$activities = $issue->activity();
	if (count($activities) > 1) : // Il existe une activité cepandant pour la création ?>
		<ul class="issue-discussion">
			<?php
			foreach ($activities as $activity):
				echo $activity;
			endforeach;
			?>
		</ul>
	<?php else : ?>
	<p><?php echo __('tinyissue.no_activity'); endif; ?></p>


	<?php if (Project\Issue::current()->status->id != \Project\Issue\Status::STATUS_CLOSED): ?>
		<div class="new-comment" id="new-comment">
			<h4><?php echo __('tinyissue.comment_on_this_issue'); ?></h4>

			<form method="post" action="">
				<p>
					<textarea name="comment" style="width: 98%; height: 90px;"></textarea>
					<a href="http://daringfireball.net/projects/markdown/basics/" target="_blank">Format with
						Markdown</a>
				</p>

				<div class="upload-wrap green-button">
					<?php echo __('tinyissue.fileupload_button'); ?>
					<input id="upload" type="file" name="file_upload" class="green-button"/>
					<input type="hidden" id="uploadbuttontext" name="uploadbuttontext"
					       value="<?php echo __('tinyissue.fileupload_button'); ?>"/>
				</div>

				<ul id="uploaded-attachments"></ul>

				<p style="margin-top: 10px;">
					<input type="submit" class="button primary" value="<?php echo __('tinyissue.comment'); ?>"/>
				</p>

				<?php echo Form::hidden('session', Crypter::encrypt(Auth::user()->id)); ?>
				<?php echo Form::hidden('project_id', $project->id); ?>
				<?php echo Form::hidden('token', md5($project->id . time() . \Auth::user()->id . rand(1, 100))); ?>
				<?php echo Form::token(); ?>
			</form>
		</div>
	<?php endif; ?>

</div>
