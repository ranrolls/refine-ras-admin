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

$commentObject = $options->get('comment_object');
$session       = JFactory::getSession();
$commentForm   = $session->get('judirectory_commentform_' . $this->listing_id, null);
$prefixId      = $options->get('prefixId', '');

$session->clear('judirectory_commentform_' . $this->listing_id);

$ratingInCommentForm = 0;
if (isset($commentForm['rating']))
{
	$ratingInCommentForm = $commentForm['rating'];
}


if ($this->canRateListing && $this->juparams->get('enable_listing_rate_in_comment_form', 1))
{
	$this->setAttribute("class", "judir-rating", "output");
	?>
	<div <?php echo $this->getAttribute(null, null, "output"); ?>>
		<div class="judir-rating-action">
			<?php
			if (count($this->criteriaObjectList) == 0)
			{
				?>
				<div class="rating-item">
					<div class="rating-title">
						<label for="<?php echo $prefixId; ?>fieldset-criteria">
							<?php
							echo JText::_("COM_JUDIRECTORY_COMMENT_RATING");
							if ($this->juparams->get('require_listing_rate_in_comment_form', 1))
							{
								?>
								<span class="required">*</span>
							<?php
							} ?>
						</label>
					</div>
					<?php
					$class          = "star judirrating {split:" . $this->starParts . "}";
					$oldRatingValue = null;

					
					if (isset($ratingInCommentForm))
					{
						$oldRatingValue = $ratingInCommentForm['judir-comment-rating-single'];
					}

					
					if (is_object($commentObject))
					{
						$oldRatingValue = $commentObject->score;
					}

					$scorePerInput = $this->scoreIncrement;

					if ($this->juparams->get('require_listing_rate_in_comment_form', 1))
					{
						echo '<fieldset class="fieldset required radio" id="' . $prefixId . 'fieldset-criteria">';
					}
					else
					{
						echo '<fieldset class="fieldset radio" id="' . $prefixId . 'fieldset-criteria">';
					}

					for ($count = 1; $count <= $this->totalInputs; $count++)
					{
						if ((string) $scorePerInput == (string) $oldRatingValue)
						{
							$checked = 'checked="checked"';
						}
						else
						{
							$checked = '';
						}
						?>
						<input name="judir_comment_rating_single"
						       type="radio" <?php echo $checked; ?>
						       title="<?php echo $this->ratingExplanation[$count]; ?>"
						       class="<?php echo $class; ?>"
						       value="<?php echo $scorePerInput; ?>"/>
						<?php
						$scorePerInput += $this->scoreIncrement;
						if ($scorePerInput > 10)
						{
							$scorePerInput = 10;
						}
					}
					echo '</fieldset>';
					?>
				</div>
			<?php
			}
			?>
		</div>
	</div>
<?php
}