(($) ->
	'use strict'

	# we create a copy of the WP inline edit post function
	$wp_inline_edit = inlineEditPost.edit

	# and then we overwrite the function with our own code
	inlineEditPost.edit = (id) ->

		# "call" the original WP edit function
		# we don't want to leave WordPress hanging
		$wp_inline_edit.apply this, arguments

		# now we take care of our business

		# get the post ID
		$post_id = 0
		if typeof id == 'object'
			$post_id = parseInt this.getId id

		if $post_id > 0
			# define the edit row
			$edit_row = $ '#edit-' + $post_id
			$post_row = $ '#post-' + $post_id

			# get the data
			$link = $ '.column-degree_program_link', $post_row
				.text()

			# populate the data
			$ 'textarea[name="degree_program_link"]', $edit_row
				.val $link

) jQuery
