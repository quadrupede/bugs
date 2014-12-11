<div class="blue-box">
	<div class="inside-pad">
<div class="filter-and-sorting">
			<form method="get" action="">
				<table class="form" style="width: 100%;">
				<tr>
					<th style="width: 10%"><?php echo __('tinyissue.tags'); ?></th>
					<td style="width: 90%">
						<?php echo Form::text('tags', Input::get('tags', ''), array('id' => 'tags')); ?>
						<script type="text/javascript">
						$(function(){
							$('#tags').tagit({
								autocomplete: {
									source: '<?php echo URL::to('ajax/tags/suggestions/filter'); ?>'
								}
							});
						});
						</script>
					</td>
				</tr>
				<tr>
					<th style="width: 10%"><?php echo __('tinyissue.sort_by'); ?></th>
					<td style="width: 90%">
						<?php echo Form::select('sort_by', $status_sort_options, Input::get('sort_by', '')); ?>
						<?php echo Form::select('sort_order', array('asc' => __('tinyissue.sort_asc'), 'desc' => __('tinyissue.sort_desc')), $sort_order); ?>
					</td>
				</tr>
				<tr>
					<th style="width: 10%"><?php echo __('tinyissue.assigned_to'); ?></th>
					<td style="width: 90%">
						<?php echo Form::select('assigned_to', $assigned_users, Input::get('assigned_to', '')); ?>
					</td>
				</tr>
				<tr>
					<th style="width: 10%"></th>
					<td style="width: 90%"><input type="submit" value="<?php echo __('tinyissue.show_results'); ?>" class="button primary" /></td>
				</tr>
				</table>
			</form>
		</div>
	</div>
</div>

<div class="blue-box">
	<div class="inside-pad">
		<?php if(!$issues): ?>
		<p><?php echo __('tinyissue.no_issues'); ?></p>
		<?php else: ?>
		<ul class="issues" id="sortable">
			<?php
			/** @var Project\Issue $row */
			foreach($issues as $row):  ?>
			<li class="sortable-li" data-issue-id="<?php echo $row->id; ?>">
				<a href="" class="comments"><?php echo $row->comment_count(); ?></a>

				<?php if(!empty($row->tags)): ?>
				<div class="tags">
				<?php foreach($row->tags()->order_by('tag', 'ASC')->get() as $tag): ?>
						<?php echo '<label class="label"' . ($tag->bgcolor ? ' style="background: ' . $tag->bgcolor . '"' : '') . '>' . $tag->tag . '</label>'; ?>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

				<a href="" class="id">#<?php echo $row->id; ?></a>
				<div class="data">
					<label class="label label-mini status-<?php echo $row->status->name ?>"><?php echo __('tinyissue.label_'.$row->status->name) ?></label>
					<a class="issue-title" href="<?php echo $row->to(); ?>"><?php echo $row->title; ?></a>
					<?php if($row->priority) : ?>
					<label class="label priority priority-<?php echo $row->priority->name ?>" title="<?php __('tinyissue.priority_'.$row->priority->name) ?>"></label>
					<?php endif; ?>
					<div class="info">
						<?php echo __('tinyissue.created_by'); ?>
						<strong><?php echo $row->user->firstname . ' ' . $row->user->lastname; ?></strong>
						<?php if(is_null($row->updated_by)): ?>
							<?php echo Time::age(strtotime($row->created_at)); ?>
						<?php endif; ?>

						<?php if(!is_null($row->updated_by)): ?>
							- <?php echo __('tinyissue.updated_by'); ?>
							<strong><?php echo $row->updated->firstname . ' ' . $row->updated->lastname; ?></strong>
							<?php echo Time::age(strtotime($row->updated_at)); ?>
						<?php endif; ?>

						<?php if($row->assigned_to != 0): ?>
							<br/>- <?php echo __('tinyissue.assigned_to'); ?>
							<strong><?php echo $row->assigned->firstname . ' ' . $row->assigned->lastname; ?></strong>
						<?php endif; ?>

					</div>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
		<div id="sortable-msg"><?php echo __('tinyissue.sortable_issue_howto'); ?></div>
		<div id="sortable-save"><input id="sortable-save-button" class="button primary" type="submit" value="<?php echo __('tinyissue.save'); ?>" /></div>
	</div>
</div>
