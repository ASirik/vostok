<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/helpers/html/joomselect.php $
// $Id: joomselect.php 4057 2013-01-22 17:08:41Z erftralle $
/******************************************************************************\
**   JoomGallery 2                                                            **
**   By: JoomGallery::ProjectTeam                                             **
**   Copyright (C) 2008 - 2011  JoomGallery::ProjectTeam                      **
**   Based on: JoomGallery 1.0.0 by JoomGallery::ProjectTeam                  **
**   Released under GNU GPL Public License                                    **
**   License: http://www.gnu.org/copyleft/gpl.html or have a look             **
**   at administrator/components/com_joomgallery/LICENSE.TXT                  **
\******************************************************************************/

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/**
 * Utility class for creating HTML Grids
 *
 * @static
 * @package JoomGallery
 * @since   1.5.5
 */
class JHTMLJoomSelect
{
  /**
   * Maximum number of users for who the
   * default select boxes should be used
   *
   * @var   int
   * @since 2.1.2
   */
  static $max_count = 250;

  /**
   * Number of active users in the system.
   * This is not set until first call of method users().
   *
   * @var   int
   * @since 2.1.2
   */
  static $count = null;

  /**
   * Construct HTML List of selectable categories
   *
   * @access  public
   * @param   int     $currentcat catid, current cat or parent
   * @param   string  $cname      Name of the HTML element
   * @param   string  $extra      Some extra code to add to the element
   * @param   int     $orig       A category to ignore (its sub-categories will be filtered out, too)
   * @param   string  $separator  A string with which the categories will be separated in the category paths
   * @param   string  $task       Null/filter
   * @param   string  $action     Action to check for each category
   * @param   mixed   $idtag      String to use as id tag for the select box
   * @return  string  The HTML output
   * @since   1.0.0
   */
  public static function categoryList($currentcat, $cname = 'catid', $extra = null, $orig = null, $separator  = '- ', $task = null, $action = 'core.create', $idtag = false)
  {
    $attribs = 'class="inputbox"';
    if($extra)
    {
      // Add the default class only if no class is given by caller
      if(strpos($extra, 'class=') === false && strpos($extra, 'class =') === false)
      {
        $attribs .= ' '.$extra;
      }
      else
      {
        $attribs = $extra;
      }
    }

    if(JoomConfig::getInstance()->get('jg_ajaxcategoryselection'))
    {
      if(!$idtag)
      {
        $idtag = $cname;
      }

      if($currentcat == 1)
      {
        $name = '-';
      }
      else
      {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
              ->select('name')
              ->from(_JOOM_TABLE_CATEGORIES)
              ->where('cid = '.(int) $currentcat);
        $db->setQuery($query);
        $name = $db->loadResult();
      }

      $matches = array();
      $onchange = '';
      if(preg_match('/onchange="(.*)"/', $attribs, $matches))
      {
        $onchange = $matches[1];
        $attribs = trim(str_replace($matches[0], '', $attribs));
      }
      $attribs = preg_replace('/size="(.*)"/', '', $attribs);

      JText::script('COM_JOOMGALLERY_COMMON_REQUEST_ERROR');
      JText::script('COM_JOOMGALLERY_COMMON_CATEGORIES_NO_RESULTS');

      JHtml::_('behavior.framework', true);
      $doc = JFactory::getDocument();
      $doc->addStyleSheet(JoomAmbit::getInstance()->getStyleSheet('admin.joomgallery.css'));
      $doc->addScript(JoomAmbit::getInstance()->getScript('categories.js'));
      $doc->addScriptDeclaration('var '.$idtag.'search;
      window.addEvent(\'domready\', function() {
        '.$idtag.'search = new JoomGallerySearchCategories({
          \'inputbox\': \'jg-'.$idtag.'-input\',
          \'resultbox\': \'jg-'.$idtag.'-results\',
          \'hiddenbox\': \''.$idtag.'\',
          \'moreresults\': \'jg-'.$idtag.'-more-results\',
          \'defaultcontent\': \''.($name ? addslashes($name) : JText::_('COM_JOOMGALLERY_COMMON_ALL', true)).'\',
          \'variablename\': \''.$idtag.'search\',
          \'action\': \''.($task ? '' : $action).'\',
          \'filter\': '.(int) $orig.',
          \'current\': '.(int) $currentcat.',
          \'onchange\': \''.str_replace('\'', '\\\'', $onchange).'\',
          \'url\': \''.JRoute::_('index.php?option='._JOOM_OPTION.'&controller=categories&task=getcategories', false).'&format=json\'
        });
      });');

      return '<input type="hidden" name="'.$cname.'" value="'.$currentcat.'" id="'.$idtag.'" '.$attribs.' />
              <input type="text" name="jg-input-'.$cname.'" value="'.($name ? addslashes($name) : JText::_('COM_JOOMGALLERY_COMMON_ALL')).'" id="jg-'.$idtag.'-input" '.str_replace('validate-joompositivenumeric', '', $attribs).' />
              <div id="jg-'.$idtag.'-results" class="jg-category-results">
                <div id="jg-'.$idtag.'-more-results" class="jg-category-more-results" onclick="'.$idtag.'search.loadMore();return false;">
                  <a href="#" onclick="return false;">
                    '.JText::_('COM_JOOMGALLERY_COMMON_CATEGORIES_MORE_RESULTS').'</a>
                </div>
              </div>';
    }

    $user           = JFactory::getUser();
    $ambit          = JoomAmbit::getInstance();
    $cats           = $ambit->getCategoryStructure(true);
    $options        = array();
    //$filter         = ($cname == 'parent_id' && $orig != null) ? true : false;
    $origfound      = false;
    $filtercatkeys  = array();

    $paths          = array();

    $action2 = false;
    if($action == 'joom.upload')
    {
      $action2 = 'joom.upload.inown';
    }
    if($action == 'core.create')
    {
      $action2 = 'joom.create.inown';
    }
    if($action == 'core.edit')
    {
      $action2 = 'core.edit.own';
    }

    foreach($cats as $key => $cat)
    {
      // Check whether a certain category and it's sub-categories
      // have to be filtered out of the list
      if($orig)
      {
        if(!$origfound)
        {
          if($cat->cid == $orig)
          {
            $origfound            = true;
            $filtercatkeys[$orig] = true;
          }
        }
        else
        {
          if(isset($filtercatkeys[$cat->parent_id]))
          {
            $filtercatkeys[$key] = true;
          }
        }
      }

      if($cat->parent_id > 1)
      {
        //$paths[$key] = $paths[$cat->parent_id].$separator.$cat->name;
        $paths[$key] = $paths[$cat->parent_id] + 1;
      }
      else
      {
        $paths[$key] = 0;
      }

      if(     $task == 'filter'
          ||  $key == $currentcat
          ||  (     !isset($filtercatkeys[$key])
                &&  (     $user->authorise($action, _JOOM_OPTION.'.category.'.$key)
                      ||  (     $action2
                            &&  $cat->owner == $user->get('id')
                            &&  $user->authorise($action2, _JOOM_OPTION.'.category.'.$cat->cid)
                          )
                    )
              )
        )
      {
        // Build select option for that category
        $options[$key] = new stdClass();
        $options[$key]->cid   = $cat->cid;
        $options[$key]->path  = str_repeat($separator, $paths[$key]).$cat->name;
      }
    }

    if($task == 'filter' || $currentcat == 0 || ($action == 'core.create' && $user->authorise($action, _JOOM_OPTION)))
    {
      $rootcat = new stdClass();
      $rootcat->cid   = '';
      $rootcat->path  = ($task == 'filter') ? JText::_('COM_JOOMGALLERY_COMMON_ALL') : '';
      array_unshift($options, $rootcat);
    }

    $count = count($options);
    if(!$count || ($count == 1 && !reset($options)->cid))
    {
      // Return a hidden field in order to avoid JavaScript errors
      return '<input type="hidden" name="'.$cname.'"'.($idtag ? ' id="'.$idtag.'"' : '').' value="" />
      <input type="text" readonly="readonly" class="readonly" value="-" />';
    }

    // Sort the array with key pathname if more than one element
    if($count > 1)
    {
      //usort($options, array('JHTMLJoomSelect', 'sortCatArray'));
    }

    $output = JHTML::_('select.genericlist', $options, $cname, $attribs, 'cid', 'path', $currentcat, $idtag);

    return $output;
  }

  /**
   * Construct HTML list of users
   *
   * @param   string  $name       Name of the HTML select list to use
   * @param   array   $active     Array of selected users
   * @param   boolean $nouser     True, if 'No user' should be included on top of the list
   * @param   array   $additional Additional entries to add
   * @param   string  $javascript Additional code in the select list
   * @param   int     $multiple   Size of the box if it shall be a multiple select box, 0 otherwise
   * @param   mixed   $idtag      String to use as id tag for the select box
   * @return  string  The HTML output
   * @since   1.5.5
   */
  public static function users($name, $active, $nouser = false, $additional = array(), $javascript = null, $multiple = 6, $idtag = false)
  {
    static $users = null;

    if(is_null($users))
    {
      $db     = JFactory::getDbo();
      $config = JoomConfig::getInstance();

      $type = $config->get('jg_realname') ? 'name' : 'username';

      $query = $db->getQuery(true)
            ->select('COUNT(id)')
            ->from('#__users')
            ->where('block = 0');
      $db->setQuery($query);

      JHTMLJoomSelect::$count = $db->loadResult();

      if(JHTMLJoomSelect::$count <= JHTMLJoomSelect::$max_count || !$multiple)
      {
        $users = array();

        $query->clear()
              ->select('id AS value')
              ->select($type.' AS text')
              ->from('#__users')
              ->where('block = 0')
              ->order($type.' ASC');
        $db->setQuery($query);

        $users = $db->loadObjectList();
      }
    }

    if(JHTMLJoomSelect::$count > JHTMLJoomSelect::$max_count && $multiple)
    {
      return '<input type="text" name="'.$name.'" value="'.implode(',', $active).'" class="inputbox"'.($idtag ? ' id="'.$idtag.'"' : '').'/>';
    }

    $options = array();

    foreach($additional as $key => $value)
    {
      $options[] = JHtml::_('select.option',  $key, $value);
    }

    if($nouser)
    {
      $options[] = JHtml::_('select.option',  '0', JText::_('COM_JOOMGALLERY_COMMON_NO_USER'));
    }

    $options = array_merge($options, $users);

    $multiple_box = '';
    if($multiple > 1)
    {
      $multiple_box = ' multiple="multiple" size="'.$multiple.'"';
    }

    if($javascript)
    {
      $javascript = ' onchange="'.$javascript.'"';
    }

    return JHtml::_('select.genericlist', $options, $name, 'class="inputbox"'.$multiple_box.$javascript, 'value', 'text', $active, $idtag);
  }

  /**
   * Construct input field and button for selecting a user in a popup
   *
   * @param   string  $name       Name of the HTML select list to use
   * @param   array   $active     Array of selected users
   * @param   string  $javascript Additional code in the select list
   * @return  string  The HTML output
   * @since   2.1.2
   */
  public static function usersPopup($name, $active, $javascript = null)
  {
    // Use simple select box if there aren't too many users
    $db = JFactory::getDbo();
    $query = $db->getQuery(true)
          ->select('COUNT(id)')
          ->from('#__users')
          ->where('block = 0');
    $db->setQuery($query);

    if($db->loadResult() <= JHTMLJoomSelect::$max_count)
    {
      return JHtml::_('joomselect.users', $name, $active, false, array('' => JText::_('COM_JOOMGALLERY_COMMON_OPTION_SELECT_OWNER')), $javascript, false, false, $name);
    }

    $html = array();
    $link = JRoute::_('index.php?option=com_users&view=users&layout=modal&tmpl=component&field='.$name);
    $type = JoomConfig::getInstance()->get('jg_realname') ? 'name' : 'username';

    // Load the modal behavior script
    JHtml::_('behavior.modal', 'a.modal_'.$name);

    // Build the script
    $script = array();
    $script[] = '  function jSelectUser_'.$name.'(id, title) {';
    $script[] = '    var old_id = document.getElementById("'.$name.'_id").value;';
    $script[] = '    if(old_id != id) {';
    $script[] = '      document.getElementById("'.$name.'_id").value = id;';
    $script[] = '      document.getElementById("'.$name.'_name").value = title;';
    $script[] = '      '.$javascript;
    $script[] = '    }';
    $script[] = '    SqueezeBox.close();';
    $script[] = '  }';

    // Add the script to the document head
    JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

    // Load the current user name if available
    $table = JTable::getInstance('user');
    if(!empty($active))
    {
      $table->load((int)$active);
    }
    else
    {
      $table->$type = JText::_('JLIB_FORM_SELECT_USER');
    }

    // Create a dummy text field with the user name
    $html[] = '<div class="fltlft">';
    $html[] = '  <input type="text" id="'.$name.'_name" value="'.htmlspecialchars($table->$type, ENT_COMPAT, 'UTF-8').'" disabled="disabled" class="inputbox" />';
    $html[] = '</div>';

    // Create the user select button
    $html[] = '<div class="button2-left">';
    $html[] = '  <div class="blank">';
    $html[] = '    <a class="modal_'.$name.'" title="' . JText::_('JLIB_FORM_CHANGE_USER').'" href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">';
    $html[] = '      '.JText::_('JLIB_FORM_CHANGE_USER').'</a>';
    $html[] = '  </div>';
    $html[] = '</div>';

    // Create the real field, hidden, that stores the user id
    $html[] = '<input type="hidden" id="'.$name.'_id" name="'.$name.'" value="'.$active.'" />';

    return implode("\n", $html);
  }

  /**
   * Callback function for sorting an array of objects to assembled names of
   * categories with alle parent categories
   * @see categoryList()
   *
   * @access  public
   * @param   object  $a
   * @param   object  $b
   * @return  0 if names equal, -1 if a < b, 1 if a > b
   * @since   1.0.0
   */
  public static function sortCatArray($a,$b)
  {
    return strcmp($a->path, $b->path);
  }
}