<?php
/**
 * ------------------------------------------------------------------------
 * JUDirectory for Joomla 2.5, 3.x
 * ------------------------------------------------------------------------
 *
 * @copyright      Copyright (C) 2010-2015 JoomUltra Co., Ltd. All Rights Reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 * @author         JoomUltra Co., Ltd
 * @website        http://www.joomultra.com
 * @----------------------------------------------------------------------@
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<ul class="comment-list">
	<?php
	$timeAgo = new TimeAgo();
	$avatar = new JUDirectoryAvatarHelper ();

	foreach ($this->items AS $commentObj)
	{
		$userAvatar = $avatar->getAvatar(JFactory::getUser($commentObj->user_id), $this->params);
		?>
		<li class="comment-item level-<?php echo $commentObj->level; ?>"
		    id="comment-item-<?php echo $commentObj->id; ?>">
			<div itemscope="" itemtype="http://schema.org/Review">
				<div class="comment-box clearfix">
					<div class="comment-user">
						<img class="comment-avatar" itemprop="image" src="<?php echo $userAvatar; ?>" alt="Avatar"
							 style="max-width: <?php echo $this->params->get("avatar_width", 120); ?>px;max-height: <?php echo $this->params->get("avatar_height", 120); ?>px;"/>

						<h3 class="comment-username" itemprop="creator" itemscope=""
							itemtype="http://schema.org/Person">
							<span itemprop="name">
								<?php
								if ($commentObj->user_id > 0)
								{
									$userComment = JFactory::getUser($commentObj->user_id);
									echo $userComment->get('name');
								}
								else
								{
									echo $commentObj->guest_name;
								}
								?>
							</span>
						</h3>

						<?php
						if ($commentObj->parent_id == $this->root_comment->id)
						{
							$fieldRating = JUDirectoryFrontHelperField::getField('rating', $commentObj->listing_id);
							echo $fieldRating->getOutput(array('view' => 'details', 'template' => $this->template, 'type' => 'comment', 'comment_object' => $commentObj));
						}
						?>
					</div>

					<div class="comment-text">
						<h4 class="comment-title" itemprop="name"><?php echo $commentObj->title; ?></h4>

						<div class="comment-metadata clearfix">
							<div class="comment-created" itemprop="datePublished">
								<i class="fa fa-calendar"></i> <?php echo JText::_('COM_JUDIRECTORY_POST_ON') . ': ' . $timeAgo->inWords(JHtml::_('date', $commentObj->created, 'Y-m-d H:i:s')); ?>
							</div>

							<?php if ($commentObj->website != '')
							{
								?>
								<div class="comment-website">
									<i class="fa fa-globe"></i> <?php echo JText::_('COM_JUDIRECTORY_COMMENT_WEBSITE') . " : " . $commentObj->website; ?>
								</div>
							<?php
							} ?>

							<div class="comment-listing">
								<i class="fa fa-file-text-o"></i> <?php echo JText::_("COM_JUDIRECTORY_LISTING"); ?>
								<a href="<?php echo JRoute::_(JUDirectoryHelperRoute::getListingRoute($commentObj->listing_id)); ?>">
									<?php echo $commentObj->listing_title; ?>
								</a>
							</div>

							<?php if ($commentObj->level > 1)
							{
								?>
								<div class="comment-reply">
									<i class="fa fa-mail-forward"></i> <?php echo JText::_("COM_JUDIRECTORY_REPLY_COMMENT"); ?>
									<a href="<?php echo JRoute::_(JUDirectoryHelperRoute::getListingRoute($commentObj->listing_id) . '#comment-item-' . $commentObj->id); ?>">
										<?php echo $commentObj->parent_title; ?>
									</a>
								</div>
							<?php
							} ?>
						</div>
						<?php
						$commentObj->comment = JUDirectoryFrontHelper::BBCode2Html($commentObj->comment);
						$commentObj->comment = JUDirectoryFrontHelperComment::parseCommentText($commentObj->comment, $commentObj->listing_id);
						?>
						<div class="comment-content" itemprop="description">
							<?php echo $commentObj->comment; ?>
						</div>
					</div>
				</div>
			</div>
		</li>
	<?php
	} ?>
</ul>