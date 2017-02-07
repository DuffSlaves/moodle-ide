<?php

/**
 * HTML Class for a IDE text field
 * @author     Logan Rodie
 * @version    0.1
 * @access     public
 */

class HTML_QuickForm_ide extends HTML_QuickForm_element
{


    var $_value = null;
    var $_lang = null;

    var $_supported_langs = array(
        'java',
        'javascript',
        'php',
        'haskell',
        'c++',
        'objective_c',
        'c#',
    );

    /**
     * Class constructor
     *
     * @param     string    Input field name attribute
     * @param     mixed     Label(s) for a field
     * @param     mixed     Either a typical HTML attribute string or an associative array
     * @since     1.0
     * @access    public
     * @return    void
     */
    public function __construct($elementName=null, $elementLabel=null, $lang, $attributes=null) {
        parent::__construct($elementName, $elementLabel, $attributes);
        $this->_persistantFreeze = true;
        $this->_type = 'ide';
        $this->setLang($lang);
    } //end constructor

    /**
     * Old syntax of class constructor. Deprecated in PHP7.
     *
     * @deprecated since Moodle 3.1
     */
    public function HTML_QuickForm_ide($elementName=null, $elementLabel=null, $attributes=null) {
        debugging('Use of class name as constructor is deprecated', DEBUG_DEVELOPER);
        self::__construct($elementName, $elementLabel, $attributes);
    }

    // {{{ setName()

    /**
     * Sets the input field name
     *
     * @param     string    $name   Input field name attribute
     * @since     1.0
     * @access    public
     * @return    void
     */
    function setName($name)
    {
        $this->updateAttributes(array('name'=>$name));
    } //end func setName

    // }}}
    // {{{ getName()

    /**
     * Returns the element name
     *
     * @since     1.0
     * @access    public
     * @return    string
     */
    function getName()
    {
        return $this->getAttribute('name');
    } //end func getName

    // }}}
    // {{{ setValue()

    /**
     * Sets value for textarea element
     *
     * @param     string    $value  Value for textarea element
     * @since     1.0
     * @access    public
     * @return    void
     */
    function setValue($value)
    {
        $this->_value = $value;
    } //end func setValue

    // }}}
    // {{{ getValue()

    /**
     * Returns the value of the form element
     *
     * @since     1.0
     * @access    public
     * @return    string
     */
    function getValue()
    {
        return $this->_value;
    } // end func getValue

    // }}}
    // {{{ setWrap()

    /**
     * Sets wrap type for textarea element
     *
     * @param     string    $wrap  Wrap type
     * @since     1.0
     * @access    public
     * @return    void
     */
    function setWrap($wrap)
    {
        $this->updateAttributes(array('wrap' => $wrap));
    } //end func setWrap

    // }}}
    // {{{ setRows()

    /**
     * Sets height in rows for textarea element
     *
     * @param     string    $rows  Height expressed in rows
     * @since     1.0
     * @access    public
     * @return    void
     */
    function setRows($rows)
    {
        $this->updateAttributes(array('rows' => $rows));
    } //end func setRows

    // }}}
    // {{{ setCols()

    /**
     * Sets width in cols for textarea element
     *
     * @param     string    $cols  Width expressed in cols
     * @since     1.0
     * @access    public
     * @return    void
     */
    function setCols($cols)
    {
        $this->updateAttributes(array('cols' => $cols));
    } //end func setCols

    // }}}

    function setLang($lang)
    {
        $this->_lang = $lang;
    }
    // {{{ toHtml()

    function toHtml()
    {
        if(!isset($this->_lang)){
            $this->setLang('javascript');
        }
        if ($this->_flagFrozen) {
            return $this->getFrozenHtml();
        } else {
            return '<div id="IDE_spacer">' .
            $this->_getTabs() .
            // Insert custom CodeMirror Editor
            '<script src="http://' . $_SERVER['HTTP_HOST'] . '/moodle/mod/assign/submission/ide/lib/CodeMirror/lib/codemirror.js"></script>
            <link rel="stylesheet" href="http://' . $_SERVER['HTTP_HOST'] . '/moodle/mod/assign/submission/ide/lib/CodeMirror/lib/codemirror.css">
            <script src="http://' . $_SERVER['HTTP_HOST'] . '/moodle/mod/assign/submission/ide/lib/CodeMirror/mode/'
            .$this->_lang.'/'.$this->_lang.'.js"></script>';
        }
    } //end func toHtml

    function getFrozenHtml()
    {
        $value = htmlspecialchars($this->getValue());
        if ($this->getAttribute('wrap') == 'off') {
            $html = $this->_getTabs() . '<pre>' . $value."</pre>\n";
        } else {
            $html = nl2br($value)."\n";
        }
        return $html . $this->_getPersistantData();
    } //end func getFrozenHtml
}
