<li id="comment<?php echo $activity->id; ?>" class="comment">
	<div class="insides">
		<div class="topbar">

			<div class="data">
				<label class="label status-open"><?php echo __('tinyissue.label_reopened'); ?></label> <?php echo __('tinyissue.to'); ?> <strong><?php echo $user->firstname . ' ' . $user->lastname; ?></strong>
				<span class="time">
					<span class="moment"><?php echo strtotime($activity->created_at); ?></span>
				</span>		
		</div>
	</div>
</li>
