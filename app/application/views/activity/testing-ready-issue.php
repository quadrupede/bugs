<li onclick="window.location='<?php echo $issue->to(); ?>';">

	<div class="tag">
		<label class="label status-testing"><?php echo __('tinyissue.label_testing'); ?></label>
	</div>

	<div class="data">
		<a href="<?php echo $issue->to(); ?>"><?php echo $issue->title; ?></a> <?php echo __('tinyissue.is_ready_to_test'); ?></strong>
		<span class="time">
			<span class="moment"><?php echo strtotime($activity->created_at); ?></span>
		</span>
	</div>

	<div class="clr"></div>
</li>
