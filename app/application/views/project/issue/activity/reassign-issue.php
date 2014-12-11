<li id="comment<?php echo $activity->id; ?>" class="comment">

	<div class="insides">
		<div class="topbar">		
			<label class="label status-reassigned"><?php echo __('tinyissue.label_reassigned'); ?></label> <?php echo __('tinyissue.to'); ?>
			<?php if($activity->action_id > 0): ?>
			<strong><?php echo $assigned->firstname . ' ' . $assigned->lastname; ?></strong>
			<?php else: ?>
			<strong><?php echo __('tinyissue.no_one'); ?></strong>
			<?php endif; ?>
			by
			<strong><?php echo $user->firstname . ' ' . $user->lastname; ?></strong>

			<span class="time">
				<span class="moment"><?php echo strtotime($activity->created_at); ?></span>
			</span>
		</div>

	<div class="clr"></div>
</li>
