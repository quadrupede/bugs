<li onclick="window.location='<?php echo $issue->to(); ?>';">

	<div class="tag">
		<label class="label priority priority-<?php echo $priority->name; ?>"></label>
	</div>

	<div class="data">
		<a href="<?php echo $issue->to(); ?>"><?php echo $issue->title; ?></a> <?php echo __('tinyissue.priority_changed_to'); ?> <strong><?php echo __('tinyissue.priority_'.$priority->name); ?></strong> <?php echo __('tinyissue.by'); ?> </strong> <strong><?php echo $user->firstname . ' ' . $user->lastname; ?></strong>
		<span class="time">
			<span class="moment"><?php echo strtotime($activity->created_at); ?></span>
		</span>
	</div>

	<div class="clr"></div>
</li>
