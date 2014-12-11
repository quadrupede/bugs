<li id="comment<?php echo $activity->id; ?>" class="comment">
	<div class="insides">
		<div class="topbar">

			<div class="data">
				<label class="label status-resolving"><?php echo __('tinyissue.label_resolving'); ?></label> <?php echo __('tinyissue.by'); ?> <strong><?php echo $user->firstname . ' ' . $user->lastname; ?></strong>
				<span class="time">
					<span class="moment"><?php echo strtotime($activity->created_at); ?></span>
				</span>
			</div>
		</div>
</li>
