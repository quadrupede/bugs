<li onclick="window.location='<?php echo $issue->to(); ?>';">

	<div class="tag">
		<label class="label status-close"><?php echo __('tinyissue.label_closed'); ?></label>
	</div>

	<div class="data">
		<a href="<?php echo $issue->to(); ?>"><?php echo $issue->title; ?></a> <?php echo __('tinyissue.was_closed_by'); ?> <strong><?php echo $user->firstname . ' ' . $user->lastname; ?></strong>
		<span class="time">
			<span class="moment"><?php echo strtotime($activity->created_at); ?></span>
		</span>
	</div>

	<div class="clr"></div>
</li>
