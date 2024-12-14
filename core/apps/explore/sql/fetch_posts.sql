SELECT * FROM `<?php echo($data['t_pubs']); ?>` 

	WHERE `status` = "active"

	AND `target` = "publication"

	AND `admin_pinned` = "N"

	<?php if(empty($cl["is_admin"])): ?>
		<?php if(not_empty($data['user_id'])): ?>
			AND (`priv_wcs` = "everyone" OR  `user_id` = <?php echo($data['user_id']); ?> OR `user_id` IN (SELECT `following_id` FROM `<?php echo($data['t_conns']); ?>` WHERE `follower_id` = <?php echo($data['user_id']); ?> AND `status` = "active"))
		<?php else: ?>
			AND `priv_wcs` = "everyone"
		<?php endif; ?>
	<?php endif; ?>

	<?php if(not_empty($data['keyword'])): ?>
		AND (`text` LIKE "%<?php echo($data['keyword']); ?>%"

		<?php if($data['htag']): ?>
			OR `text` LIKE "%{#id:<?php echo($data['htag']); ?>#}%"
		<?php endif; ?>)
	<?php endif; ?>


	<?php if(not_empty($data['user_id'])): ?>
		AND `user_id` NOT IN (SELECT b1.`profile_id` FROM `<?php echo($data['t_blocks']); ?>` b1 WHERE b1.`user_id` = <?php echo($data['user_id']); ?>)

		AND `user_id` NOT IN (SELECT b2.`user_id` FROM `<?php echo($data['t_blocks']); ?>` b2 WHERE b2.`profile_id` = <?php echo($data['user_id']); ?>)

		AND (`id` NOT IN (SELECT `post_id` FROM `<?php echo($data['t_reports']); ?>` WHERE `user_id` = <?php echo($data['user_id']); ?>))
	<?php endif; ?>

	ORDER BY  `time` DESC, `likes_count` DESC, `replys_count` DESC, `reposts_count` DESC

<?php if(is_posnum($data['limit'])): ?>
	LIMIT <?php echo($data['limit']); ?>

	<?php if(not_empty($data['offset'])): ?>
		OFFSET <?php echo($data['offset']); ?>
	<?php endif; ?>
<?php endif; ?>

