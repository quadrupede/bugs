<h3>
   <a href="<?php echo Project::current()->to('issue/new'); ?>" class="newissue"><?php echo __('tinyissue.new_issue');?></a>
   <a href="<?php echo Project::current()->to(); ?>"><?php echo Project::current()->name; ?></a>
	<span><?php echo __('tinyissue.project_overview');?></span>
</h3>

<div class="pad">

	<ul class="tabs">
		<li <?php echo $active == 'activity' ? 'class="active"' : ''; ?>>
			<a href="<?php echo Project::current()->to(); ?>"><?php echo __('tinyissue.activity');?></a>
		</li>
		<li <?php echo $active == 'open' ? 'class="active"' : ''; ?>>
			<a href="<?php echo Project::current()->to('issues'); ?>?status=open">
			<?php echo $open_count == 1 ? '1 '.__('tinyissue.open_issue') : $open_count . ' '.__('tinyissue.open_issues'); ?>
			</a>
		</li>
		<li <?php echo $active == 'resolving' ? 'class="active"' : ''; ?>>
			<a href="<?php echo Project::current()->to('issues'); ?>?status=resolving">
				<?php echo $resolving_count == 1 ? '1 '.__('tinyissue.resolving_issue') : $resolving_count . ' '.__('tinyissue.resolving_issues'); ?>
			</a>
		</li>
		<li <?php echo $active == 'testing' ? 'class="active"' : ''; ?>>
			<a href="<?php echo Project::current()->to('issues'); ?>?status=testing">
				<?php echo $testing_count == 1 ? '1 '.__('tinyissue.testing_issue') : $testing_count . ' '.__('tinyissue.testing_issues'); ?>
			</a>
		</li>
		<li <?php echo $active == 'must_fix' ? 'class="active"' : ''; ?>>
			<a href="<?php echo Project::current()->to('issues'); ?>?status=must_fix">
				<?php echo $mustfix_count == 1 ? '1 '.__('tinyissue.fixing_issue') : $mustfix_count . ' '.__('tinyissue.fixing_issues'); ?>
			</a>
		</li>
		<li <?php echo $active == 'closed' ? 'class="active"' : ''; ?>>
			<a href="<?php echo Project::current()->to('issues'); ?>?status=closed">
				<?php echo $closed_count == 1 ? '1 '.__('tinyissue.closed_issue') : $closed_count . ' '.__('tinyissue.closed_issues'); ?>
			</a>
		</li>
		<li <?php echo $active == 'assigned' ? 'class="active"' : ''; ?>>
			<a href="<?php echo Project::current()->to('issues'); ?>?status=open&amp;assigned_to=<?php echo Auth::user()->id; ?>">
			<?php echo $assigned_count == 1 ? '1 '.__('tinyissue.issue_assigned_to_you') : $assigned_count . ' '.__('tinyissue.issues_assigned_to_you'); ?>
			</a>
		</li>
	</ul>

	<div class="inside-tabs">
		<?php echo $page; ?>
	</div>


</div>