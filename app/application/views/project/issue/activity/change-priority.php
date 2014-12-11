<li id="comment<?php echo $activity->id; ?>" class="comment">
	<div class="insides">
		<div class="topbar">

			<div class="data">
				<label class="label priority priority-<?php echo $priority->name; ?>"></label>  <?php echo __('tinyissue.priority_changed_to'); ?> <strong><?php echo __('tinyissue.priority_'.$priority->name); ?></strong> <?php echo __('tinyissue.by'); ?> <strong><?php echo $user->firstname . ' ' . $user->lastname; ?></strong>
				<span class="time">
					<span class="moment"><?php echo strtotime($activity->created_at); ?></span>
				</span>
			</div>
		</div>
</li>

