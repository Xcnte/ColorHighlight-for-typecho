<?php
/**
 * 基于 Highlight 插件，改自泽泽( https://qqdie.com/ )<br />
 * 在原有的功能上新增复制功能，Mac风格代码高亮<br />
 * (请勿与其它同类插件同时启用，以免互相影响)
 * 
 * @package ColorHighlight代码高亮
 * @author Xcnte
 * @version 1.2.0
 * @link https://www.xcnte.com/archives/377/
 */
class ColorHighlight_Plugin implements Typecho_Plugin_Interface
{
    /**
     *   
     */
    private static $_isMarkdown = false;

    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = array('ColorHighlight_Plugin', 'parse');
        Typecho_Plugin::factory('Widget_Abstract_Contents')->excerptEx = array('ColorHighlight_Plugin', 'parse');
        Typecho_Plugin::factory('Widget_Abstract_Comments')->contentEx = array('ColorHighlight_Plugin', 'parse');
        Typecho_Plugin::factory('Widget_Archive')->header = array('ColorHighlight_Plugin', 'header');
        Typecho_Plugin::factory('Widget_Archive')->footer = array('ColorHighlight_Plugin', 'footer');
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
      echo "<center><h1>注意：改插件需要模板自行加载jquery.js</h1></center>";
        $compatibilityMode = new Typecho_Widget_Helper_Form_Element_Radio('compatibilityMode', array(
            0   =>  _t('不启用'),
            1   =>  _t('启用')
        ), 0, _t('兼容模式'), _t('兼容模式一般用于对以前没有使用Markdown语法解析的文章'));
        $form->addInput($compatibilityMode->addRule('enum', _t('必须选择一个模式'), array(0, 1)));

      $lines = new Typecho_Widget_Helper_Form_Element_Radio(
        'lines', array('0'=> '显示', '1'=> '不显示'), 0, '是否显示行号',
            '');
        $form->addInput($lines);
      
        $styles = array_map('basename', glob(dirname(__FILE__) . '/res/styles/*.css'));
        $styles = array_combine($styles, $styles);
        $style = new Typecho_Widget_Helper_Form_Element_Select('style', $styles, 'customemin.css',
            _t('代码配色样式'));
        $form->addInput($style->addRule('enum', _t('必须选择配色样式'), $styles));
    }
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
    
    /**
     * 输出头部css
     * 
     * @access public
     * @param unknown $header
     * @return unknown
     */
    public static function header() {
        $cssUrl = Helper::options()->pluginUrl . '/ColorHighlight/res/styles/' . Helper::options()->plugin('ColorHighlight')->style;
         echo '<link rel="stylesheet" type="text/css" href="' . $cssUrl . '" />';
       if(Typecho_Widget::widget('Widget_Options')->Plugin('ColorHighlight')->lines=='0'){	
        $cssUrl2 = Helper::options()->pluginUrl . '/ColorHighlight/res/lines.css';
        echo '<link rel="stylesheet" type="text/css" href="' . $cssUrl2 . '" />';
       }
    }
    
    /**
     * 输出尾部js
     * 
     * @access public
     * @param unknown $header
     * @return unknown
     */
    public static function footer() {
        $jsUrl = Helper::options()->pluginUrl . '/ColorHighlight/';
        echo '<script type="text/javascript" src="'. $jsUrl .'res/highlight.js?version=9.12.0">
        </script><script type="text/javascript" src="'. $jsUrl .'guess.js"></script><script type="text/javascript" src="'. $jsUrl .'res/clipboard.min.js"></script>';
       
        echo '<script type="text/javascript">$("pre code").each(function(i, block) {hljs.highlightBlock(block);});';
      if(Typecho_Widget::widget('Widget_Options')->Plugin('ColorHighlight')->lines=='0'){	echo 'var l = $("pre code").find("ul").length;
if(l<=0){
    $("pre code").each(function(){
        $(this).html("<ul><li>" + $(this).html().replace(/\n/g,"\n</li><li>") +"\n</li></ul>");
    });
  }';}
        echo '</script>';
    }
    
    /**
     * 解析
     * 
     * @access public
     * @param array $matches 解析值
     * @return string
     */
    public static function parseCallback($matches)
    {
        if ('code' == $matches[1] && !self::$_isMarkdown) {
            $language = $matches[2];

            if (!empty($language)) {
                if (preg_match("/^\s*(class|lang|language)=\"(?:lang-)?([_a-z0-9-]+)\"$/i", $language, $out)) {
                    $language = ' class="' . trim($out[2]) . '"';
                } else if (preg_match("/\s*([_a-z0-9]+)/i", $language, $out)) {
                    $language = ' class="lang-' . trim($out[1]) . '"';
                }
            }
            
            return "<pre><code{$language}>" . htmlspecialchars(trim($matches[3])) . "</code></pre>";
        }

        return $matches[0];
    }
    
    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function parse($text, $widget, $lastResult)
    {
        $text = empty($lastResult) ? $text : $lastResult;

        if (!Helper::options()->plugin('ColorHighlight')->compatibilityMode) {
            return $text;
        }
        
        if ($widget instanceof Widget_Archive || $widget instanceof Widget_Abstract_Comments) {
            self::$_isMarkdown = $widget instanceof Widget_Abstract_Comments ? Helper::options()->commentsMarkdown : $widget->isMarkdown;
            return preg_replace_callback("/<(code|pre)(\s*[^>]*)>(.*?)<\/\\1>/is", array('ColorHighlight_Plugin', 'parseCallback'), $text);
        } else {
            return $text;
        }
    }
}
