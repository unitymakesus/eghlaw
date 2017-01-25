<?php
class Genesis_Dambuster_Utils {

	protected $is_html5 = null;

	function is_html5() {
		if ($this->is_html5 == null)
			$this->is_html5 = function_exists('current_theme_supports') && current_theme_supports('html5');		
		return $this->is_html5;
	}

   function get_current_term() {
		if (is_tax() || is_category() || is_tag()) {
			if (is_category())
				$term = get_term_by('slug',get_query_var('category_name'),'category') ;
			elseif (is_tag())
				$term = get_term_by('slug',get_query_var('tag'),'post_tag') ;
			else {
            if ($obj = get_queried_object())  
				  $term = get_term_by('slug', $obj->slug, $obj->taxonomy) ;
				else
				  $term = false;
         }
		} else {
			$term = false;         
		} 
      return $term; 
	}

	function get_post_id() {
		global $post;

		if (is_object($post) 
		&& property_exists($post, 'ID') 
		&& ($post_id = $post->ID))
			return $post_id ;
		else
			return false;
	}

    function get_term_meta( $term_id, $key, $single = false, $result = false ) {
        if (function_exists('get_term_meta'))
            return get_term_meta( $term_id, $key, $single); 
			else
            return $result;
	}

	function get_meta ($post_id, $key) {
		if ($post_id && $key
		&& ($meta = get_post_meta($post_id, $key, true))
		&& ($options = (is_serialized($meta) ? @unserialize($meta) : $meta))
		&& (is_array($options) || is_string($options)))
			return $options;
		else
			return false;
	}

	function json_encode($params) {
   		//fix numerics and booleans
		$pat = '/(\")([0-9]+)(\")/';	
		$rep = '\\2';
		return str_replace (array('"false"','"true"'), array('false','true'), 
			preg_replace($pat, $rep, json_encode($params)));
	} 
   
	function is_mobile_device() {
		return  preg_match("/wap.|.wap/i", $_SERVER["HTTP_ACCEPT"])
    		|| preg_match("/iphone|ipad/i", $_SERVER["HTTP_USER_AGENT"]);
	} 

	function is_landing_page($page_template='') {	
		if (empty($page_template)
		&& ($post_id = $this->get_post_id()))
			$page_template = get_post_meta($post_id,'_wp_page_template',TRUE);
		
		if (empty($page_template)) return false;

		$landing_pages = (array) apply_filters('diy_landing_page_templates', array('page_landing.php'));
		return in_array($page_template, $landing_pages );
	}

	function read_more_link($link_text='Read More', $class='', $prefix = '') {
      $classes = empty($class) ? '' : (' ' . $class);
 		return sprintf('%1$s<a class="more-link%2$s" href="%3$s">%4$s</a>', $prefix, $classes, get_permalink(), $link_text);
 	}

	function register_tooltip_styles() {
		wp_register_style('diy-tooltip', plugins_url('styles/tooltip.css',dirname(__FILE__)), array(), null); 
	}

	function enqueue_tooltip_styles() {
         wp_enqueue_style('diy-tooltip');
         wp_enqueue_style('dashicons');
  	}

	function selector($fld_id, $fld_name, $value, $options, $multiple = false) {
		$input = '';
		if (is_array($options)) {
			foreach ($options as $optkey => $optlabel)
				$input .= sprintf('<option%1$s value="%2$s">%3$s</option>',
					selected($optkey, $value, false), $optkey, $optlabel); 
		} else {
			$input = $options;
		}
		return sprintf('<select id="%1$s" name="%2$s"%4$s>%3$s</select>', $fld_id, $fld_name, $input, $multiple ? ' multiple':'');							
	}

	function form_field($fld_id, $fld_name, $label, $value, $type, $options = array(), $args = array(), $wrap = false) {
		if ($args) extract($args);
		$input = '';
		$label = sprintf('<label class="diy-label" for="%1$s">%2$s</label>', $fld_id, __($label));
		switch ($type) {
			case 'text':
			case 'password':
				$input .= sprintf('<input type="%9$s" id="%1$s" name="%2$s" value="%3$s" %4$s%5$s%6$s%7$s /> %8$s',
					$fld_id, $fld_name, $value, 
					isset($readonly) ? (' readonly="'.$readonly.'"') : '',
					isset($size) ? (' size="'.$size.'"') : '', 
					isset($maxlength) ? (' maxlength="'.$maxlength.'"') : '',
					isset($class) ? (' class="'.$class.'"') : '', 
					isset($suffix) ? $suffix : '', 
					$type);
				break;
			case 'file':
				$input .= sprintf('<input type="file" id="%1$s" name="%2$s" value="%3$s" %4$s%5$s%6$s accept="image/*" />',
					$fld_id, $fld_name, $value, 
					isset($size) ? ('size="'.$size.'"') : '', 
					isset($maxlength) ? (' maxlength="'.$maxlength.'"') : '',
					isset($class) ? (' class="'.$class.'"') : '');
				break;
			case 'textarea':
				$input .= sprintf('<textarea id="%1$s" name="%2$s"%3$s%4$s%5$s%6$s>%7$s</textarea>',
					$fld_id, $fld_name, 
					isset($readonly) ? (' readonly="'.$readonly.'"') : '', 
					isset($rows) ? (' rows="'.$rows.'"') : '', 
					isset($cols) ? (' cols="'.$cols.'"') : '',
					isset($class) ? (' class="'.$class.'"') : '', stripslashes($value));
				break;
			case 'checkbox':
				if (is_array($options) && (count($options) > 0)) {
					if (isset($legend))
						$input .= sprintf('<legend class="screen-reader-text"><span>%1$s</span></legend>', $legend);
					if (!isset($separator)) $separator = '';
					foreach ($options as $optkey => $optlabel)
						$input .= sprintf('<input type="checkbox" id="%1$s" name="%2$s[]" %3$s value="%4$s" /><label for="%1$s">%5$s</label>%6$s',
							$fld_id, $fld_name, str_replace('\'','"',checked($optkey, $value, false)), $optkey, $optlabel, $separator); 
					$input = sprintf('<fieldset class="diy-fieldset">%1$s</fieldset>',$input); 						
				} else {		
					$input .= sprintf('<input type="checkbox" class="checkbox" id="%1$s" name="%2$s" %3$svalue="1" class="diy-checkbox" />',
						$fld_id, $fld_name, checked($value, '1', false));
				}
				break;
				
			case 'checkboxes': 
			   $values = (array) $value;
			   $options = (array) $options;
			   if (isset($legend))
				  $input .= sprintf('<legend class="screen-reader-text"><span>%1$s</span></legend>', $legend);
				foreach ($options as $optkey => $optlabel)
				  $input .= sprintf('<li><input type="checkbox" id="%1$s" name="%2$s[]" %3$s value="%4$s" /><label for="%1$s">%5$s</label></li>',
					$fld_id, $fld_name, in_array($optkey, $values) ? 'checked="checked"' : '', $optkey, $optlabel); 
				$input = sprintf('<fieldset class="diy-fieldset%2$s"><ul>%1$s</ul></fieldset>',$input, isset($class) ? (' '.$class) : ''); 						
		
				break;
			case 'radio': 
				if (is_array($options) && (count($options) > 0)) {
					if (isset($legend))
						$input .= sprintf('<legend class="screen-reader-text"><span>%1$s</span></legend>', $legend);
					if (!isset($separator)) $separator = '';
					foreach ($options as $optkey => $optlabel)
						$input .= sprintf('<input type="radio" id="%1$s" name="%2$s" %3$s value="%4$s" /><label for="%1$s">%5$s</label>%6$s',
							$fld_id, $fld_name, str_replace('\'','"',checked($optkey, $value, false)), $optkey, $optlabel, $separator); 
					$input = sprintf('<fieldset class="diy-fieldset">%1$s</fieldset>',$input); 						
				}
				break;		
			case 'select': 
				$input =  $this->selector($fld_id, $fld_name, $value, $options, isset($multiple));							
				break;	
            case 'page':
                $args = array( 'id' => $fld_name, 'name' => $fld_name, 'selected' => $value, 'echo' => false,  'depth' => 0, 'option_none_value' => 0);
                if (isset($show_option_none)) $args['show_option_none'] = $show_option_none;
                $input = wp_dropdown_pages($args);
                break;
			case 'hidden': return sprintf('<input type="hidden" name="%1$s" value="%2$s" />', $fld_name, $value);	
			default: $input = $value;	
		}
		if (!$wrap) $wrap = 'div';
		switch ($wrap) {
			case 'tr': $format = '<tr class="diy-row"><th scope="row">%1$s</th><td>%2$s</td></tr>'; break;
			case 'br': $format = 'checkbox'==$type ? '%2$s%1$s<br/>' : '%1$s%2$s<br/>'; break;
			default: $format = strpos($input,'fieldset') !== FALSE ? 
				'<div class="diy-row wrapfieldset">%1$s%2$s</div>' : ('<'.$wrap.' class="diy-row">%1$s%2$s</'.$wrap.'>');
		}
		return sprintf($format, $label, $input);
	}

	function late_inline_styles($css) {
		if (empty($css)) return;
		print <<< SCRIPT
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function($) { 
	$('<style type="text/css">{$css}</style>').appendTo('head');
});	
//]]>
</script>

SCRIPT;
	}	

}
