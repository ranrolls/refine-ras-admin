<?php
/**
 * Tag Meta Community component for Joomla
 *
 * @author selfget.com (info@selfget.com)
 * @package TagMeta
 * @copyright Copyright 2009 - 2013
 * @license GNU Public License
 * @link http://www.selfget.com
 * @version 1.7.2
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * Tag Meta Controller
 *
 * @package TagMeta
 *
 */
class TagMetaControllerRules extends JControllerAdmin
{
    public function __construct($config = array())
    {
        parent::__construct($config);

        // Register Extra tasks
        $this->registerTask('case_on', 'setcase');
        $this->registerTask('case_off', 'setcase');
        $this->registerTask('request_on', 'setrequest');
        $this->registerTask('request_off', 'setrequest');
        $this->registerTask('decode_on', 'setdecode');
        $this->registerTask('decode_off', 'setdecode');
        $this->registerTask('last_on', 'setlastrule');
        $this->registerTask('last_off', 'setlastrule');
        $this->registerTask('preserve_on', 'setpreservetitle');
        $this->registerTask('preserve_off', 'setpreservetitle');
        $this->registerTask('synonyms_on_cs', 'setsynonyms');
        $this->registerTask('synonyms_on', 'setsynonyms');
        $this->registerTask('synonyms_off', 'setsynonyms');
        $this->registerTask('copy', 'copy');
    }

    public function copy()
    {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $cid = $this->input->post->get( 'cid', array(), 'array' );
        JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_tagmeta/tables');
        $table =& JTable::getInstance('Rule', 'TagMetaTable');
        $n = count( $cid );

        if ($n > 0)
        {
          $i = 0;
          foreach ($cid as $id)
          {
            if ($table->load( (int)$id ))
            {
              $table->id            = 0;
              $table->url           = JText::_('COM_TAGMETA_COPY_OF') . $table->url;
              $table->ordering      = 0;
              $table->published     = false;
              $table->checked_out   = false;

              if ($table->store()) {
                $i++;
              } else {
                JFactory::getApplication()->enqueueMessage( JText::sprintf('COM_TAGMETA_COPY_ERROR_SAVING', $id, $table->getError()), 'error' );
              }
            }
            else {
              JFactory::getApplication()->enqueueMessage( JText::sprintf('COM_TAGMETA_COPY_ERROR_LOADING', $id, $table->getError()), 'error' );
            }
          }
        }
        else {
          return JError::raiseWarning( 500, JText::_('COM_TAGMETA_COPY_ERROR_NO_SELECTION') );
        }

        $this->setMessage( JText::sprintf('COM_TAGMETA_COPY_OK', $i) );

        $this->setRedirect( 'index.php?option=com_tagmeta&view=rules' );

    }

    public function setcase()
    {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $cid = $this->input->post->get( 'cid', array(), 'array' );
        JArrayHelper::toInteger($cid);
        $case = ($this->getTask() == 'case_on') ? 1 : 0;

        if (count( $cid ) < 1) {
            JError::raiseError(500, JText::_('COM_TAGMETA_CHANGE_CASE_NO_SELECTION') );
        }

        $model = $this->getModel('rule');
        if(!$model->setcase($cid, $case)) {
          $message = JText::sprintf('COM_TAGMETA_ERROR_SETCASE_FAILED', $model->getError());
          $this->setRedirect(JRoute::_('index.php?option=com_tagmeta&view=rules', false), $message, 'error');
        } else {
          $this->setRedirect( 'index.php?option=com_tagmeta&view=rules' );
        }
    }

    public function setrequest()
    {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $cid = $this->input->post->get( 'cid', array(), 'array' );
        JArrayHelper::toInteger($cid);
        $request = ($this->getTask() == 'request_on') ? 1 : 0;

        if (count( $cid ) < 1) {
            JError::raiseError(500, JText::_('COM_TAGMETA_CHANGE_REQUEST_NO_SELECTION') );
        }

        $model = $this->getModel('rule');
        if(!$model->setrequest($cid, $request)) {
          $message = JText::sprintf('COM_TAGMETA_ERROR_SETREQUEST_FAILED', $model->getError());
          $this->setRedirect(JRoute::_('index.php?option=com_tagmeta&view=rules', false), $message, 'error');
        } else {
          $this->setRedirect( 'index.php?option=com_tagmeta&view=rules' );
        }
    }

    public function setdecode()
    {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $cid = $this->input->post->get( 'cid', array(), 'array' );
        JArrayHelper::toInteger($cid);
        $decode = ($this->getTask() == 'decode_on') ? 1 : 0;

        if (count( $cid ) < 1) {
            JError::raiseError(500, JText::_('COM_TAGMETA_CHANGE_DECODE_NO_SELECTION') );
        }

        $model = $this->getModel('rule');
        if(!$model->setdecode($cid, $decode)) {
          $message = JText::sprintf('COM_TAGMETA_ERROR_SETDECODE_FAILED', $model->getError());
          $this->setRedirect(JRoute::_('index.php?option=com_tagmeta&view=rules', false), $message, 'error');
        } else {
          $this->setRedirect( 'index.php?option=com_tagmeta&view=rules' );
        }
    }

    public function setlastrule()
    {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $cid = $this->input->post->get( 'cid', array(), 'array' );
        JArrayHelper::toInteger($cid);
        $lastrule = ($this->getTask() == 'last_on') ? 1 : 0;

        if (count( $cid ) < 1) {
            JError::raiseError(500, JText::_('COM_TAGMETA_CHANGE_LAST_RULE_NO_SELECTION') );
        }

        $model = $this->getModel('rule');
        if(!$model->setlastrule($cid, $lastrule)) {
          $message = JText::sprintf('COM_TAGMETA_ERROR_SETLASTRULE_FAILED', $model->getError());
          $this->setRedirect(JRoute::_('index.php?option=com_tagmeta&view=rules', false), $message, 'error');
        } else {
          $this->setRedirect( 'index.php?option=com_tagmeta&view=rules' );
        }
    }

    public function setpreservetitle()
    {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $cid = $this->input->post->get( 'cid', array(), 'array' );
        JArrayHelper::toInteger($cid);
        $preservetitle = ($this->getTask() == 'preserve_on') ? 1 : 0;

        if (count( $cid ) < 1) {
            JError::raiseError(500, JText::_('COM_TAGMETA_CHANGE_PRESERVE_TITLE_NO_SELECTION') );
        }

        $model = $this->getModel('rule');
        if(!$model->setpreservetitle($cid, $preservetitle)) {
          $message = JText::sprintf('COM_TAGMETA_ERROR_SETPRESERVETITLE_FAILED', $model->getError());
          $this->setRedirect(JRoute::_('index.php?option=com_tagmeta&view=rules', false), $message, 'error');
        } else {
          $this->setRedirect( 'index.php?option=com_tagmeta&view=rules' );
        }
    }

    public function setsynonyms()
    {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $cid = $this->input->post->get( 'cid', array(), 'array' );
        JArrayHelper::toInteger($cid);
        switch ($this->getTask()) {
          case 'synonyms_on_cs':
            $synonyms = 2;
            break;
          case 'synonyms_on':
            $synonyms = 1;
            break;
          default:
            $synonyms = 0;
        }

        if (count( $cid ) < 1) {
            JError::raiseError(500, JText::_('COM_TAGMETA_CHANGE_SYNONYMS_NO_SELECTION') );
        }

        $model = $this->getModel('rule');
        if(!$model->setsynonyms($cid, $synonyms)) {
          $message = JText::sprintf('COM_TAGMETA_ERROR_SETSYNONYMS_FAILED', $model->getError());
          $this->setRedirect(JRoute::_('index.php?option=com_tagmeta&view=rules', false), $message, 'error');
        } else {
          $this->setRedirect( 'index.php?option=com_tagmeta&view=rules' );
        }
    }

    /**
     * Proxy for getModel
     */
    public function getModel($name = 'Rule', $prefix = 'TagMetaModel', $config = array('ignore_request' => true))
    {
      $model = parent::getModel($name, $prefix, $config);

      return $model;
    }

    /**
     * Method to save the submitted ordering values for records via AJAX.
     *
     * @return  void
     *
     * @since   3.0
     */
    public function saveOrderAjax()
    {
      $pks = $this->input->post->get('cid', array(), 'array');
      $order = $this->input->post->get('order', array(), 'array');

      // Sanitize the input
      JArrayHelper::toInteger($pks);
      JArrayHelper::toInteger($order);

      // Get the model
      $model = $this->getModel();

      // Save the ordering
      $return = $model->saveorder($pks, $order);

      if ($return)
      {
        echo "1";
      }

      // Close the application
      JFactory::getApplication()->close();
    }

}
?>
