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
 * Tag Meta Controller Synonyms
 *
 * @package TagMeta
 *
 */
class TagMetaControllerSynonyms extends JControllerAdmin
{
    public function __construct($config = array())
    {
        parent::__construct($config);

        // Register Extra tasks
        $this->registerTask('copy', 'copy');
    }

    public function copy()
    {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $cid = $this->input->post->get( 'cid', array(), 'array' );
        JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_tagmeta/tables');
        $table =& JTable::getInstance('Synonym', 'TagMetaTable');
        $n = count( $cid );

        if ($n > 0)
        {
          $i = 0;
          foreach ($cid as $id)
          {
            if ($table->load( (int)$id ))
            {
              $table->id            = 0;
              $table->keywords      = JText::_('COM_TAGMETA_COPY_OF') . $table->keywords;
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

        $this->setRedirect( 'index.php?option=com_tagmeta&view=synonyms' );
    }

    /**
     * Proxy for getModel
     */
    public function getModel($name = 'Synonym', $prefix = 'TagMetaModel', $config = array('ignore_request' => true))
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
