<?php
/*
Plugin Name: YG-Share
Plugin URI: http://boliquan.com/yg-share/
Description: YG-Share can automatically insert the sharelist at the end of the article , such as "Delicious" .
Version: 1.3.1
Author: BoLiQuan
Author URI: http://boliquan.com/
Text Domain: YG-Share
Domain Path: /lang
*/

function yg_share_style(){
	if(is_single()){
		$yg_css_url = plugins_url('yg-share.css', __FILE__);
		if(file_exists(TEMPLATEPATH . "/yg-share.css")){
			$yg_css_url = get_bloginfo("template_url") . "/yg-share.css";
		}
	echo '<link rel="stylesheet" type="text/css" href="'.$yg_css_url.'" />' . "\n"; 
	}
}

function load_yg_lang() {
		$currentLocale = get_locale();
		if(!empty($currentLocale)) {
			$moFile = dirname(__FILE__) . "/lang/yg-share-" . $currentLocale . ".mo";
			if(@file_exists($moFile) && is_readable($moFile)) load_textdomain('YG-Share', $moFile);
		}
}

function yg_share($content) {
if(is_single()){
	$options = get_option('yg_share_options');
	if($options['yg_share_text']) { $yg_share_text = $options['yg_share_text']; } else { $yg_share_text = __('Share to : ', 'YG-Share'); }  
	$yg_share_delicious = __('Delicious', 'YG-Share');
	$yg_share_csdn = __('CSDN', 'YG-Share');
	$yg_share_google = __('Google', 'YG-Share');
	$yg_share_baidu = __('Baidu', 'YG-Share');
	$yg_share_yahoo = __('Yahoo', 'YG-Share');
	$yg_share_qq = __('Tencent', 'YG-Share');
	$yg_share_insert = '<div class="yg-share">' . $yg_share_text . '
	<a rel="external nofollow" href="http://del.icio.us/post?url='.get_permalink().'&title='.get_the_title().'" target="_blank" title="' . $yg_share_text . $yg_share_delicious . '">' . $yg_share_delicious . '</a>
	<a rel="external nofollow" href="http://wz.csdn.net/storeit.aspx?u='.get_permalink().'&t='.get_the_title().'" target="_blank" title="' . $yg_share_text . $yg_share_csdn . '">' . $yg_share_csdn . '</a>
	<a rel="external nofollow" href="http://www.google.com/bookmarks/mark?op=edit&bkmk='.get_permalink().'&title='.get_the_title().'" target="_blank" title="' . $yg_share_text . $yg_share_google . '">' . $yg_share_google . '</a>
	<a rel="external nofollow" href="http://cang.baidu.com/do/add?iu='.get_permalink().'&it='.get_the_title().'" target="_blank" title="' . $yg_share_text . $yg_share_baidu . '">' . $yg_share_baidu . '</a>
	<a rel="external nofollow" href="http://myweb.cn.yahoo.com/popadd.html?title='.get_the_title().'&url='.get_permalink().'" target="_blank" title="' . $yg_share_text . $yg_share_yahoo . '">' . $yg_share_yahoo . '</a>
	<a rel="external nofollow" href="http://shuqian.qq.com/post?from=3&title='.get_the_title().'&uri='.get_permalink().'" target="_blank" title="' . $yg_share_text . $yg_share_qq . '">' . $yg_share_qq . '</a>
	</div>';  

	$yg_share_post = $content;
	$yg_share_post = $yg_share_post . $yg_share_insert;
	return $yg_share_post;
	
}
else {
	return $content;
}
}
add_action("wp_head",'yg_share_style');
add_filter('init','load_yg_lang');
add_action('the_content','yg_share');
?>
<?php
class YGShareOptions {
	function getOptions() {
		$options = get_option('yg_share_options');
		if (!is_array($options)) {
			
			$options['yg_share_text'] = ''; //yg_share_text

			update_option('yg_share_options', $options);
		}
		return $options;
	}
	function init() {
		if(isset($_POST['yg_share_save'])) {
			$options = YGShareOptions::getOptions();
 
			$options['yg_share_text'] = stripslashes($_POST['yg_share_text']);	//yg_share_text
 
			update_option('yg_share_options', $options);
		} else {
			YGShareOptions::getOptions();
		}
		//add_options_page
		add_options_page("YG-Share Options", "YG-Share", 'manage_options', __FILE__, array('YGShareOptions', 'display'));
	}
	function display() {
		$options = YGShareOptions::getOptions();
?>
 
<form action="#" method="post" enctype="multipart/form-data" name="yg_share_form" id="yg_share_form">

	<div class="wrap">
		<?php screen_icon();?>
		<h2><?php _e('YG-Share Settings', 'YG-Share'); ?></h2>
		<table class="form-table">
			<tbody>	

				<!--yg_share_text-->
				<tr valign="top">
					<th scope="row">
						<?php _e('Display Text :', 'YG-Share'); ?>
					</th>
					<td>
						<input name="yg_share_text" type="text" id="yg_share_text" value="<?php $options = get_option('yg_share_options'); if (!$options['yg_share_text']) { echo __('Share to : ', 'YG-Share'); } else { echo($options['yg_share_text']); } ?>" maxlength="500" style="width:200px;" class="code" >
					</td>
				</tr>
				
			</tbody>
		</table>

		<p class="submit" style="margin:50px 0 0 0;">
			<input type="submit" class="button-primary" name="yg_share_save" value="<?php _e('Update','YG-Share'); ?>" />
		</p>
		
	</div> 
</form>

<?php
	if(isset($_POST['yg_share_save'])) {
		if($_POST['yg_share_save']){
			echo '<div style="color:red;margin:0 0 19px 9px;">'.__('Updated !','YG-Share').'</div>';
		}
	}

?>

<div style="width:620px;margin: 15px 0px;background-color:#e9e9e9;padding:2px 2px 2px 10px;">			
			<p>
			<?php _e('Author : ','YG-Share'); ?><a href="http://boliquan.com/" title="BoLiQuan" target="_blank">BoLiQuan</a>,
			<?php _e('Homepage : ','YG-Share'); ?><a href="http://boliquan.com/yg-share/" title="YG-Share" target="_blank">YG-Share</a>,
			<?php _e('Recommend my wordpress theme : ','YG-Share'); ?><a href="http://boliquan.com/ylife/" title="YLife" target="_blank">YLife</a> | <a href="http://code.google.com/p/ylife/downloads/list" title="YLife" target="_blank">Download</a>
			</p>
</div>
 
<?php
	}
}
 
/*add_action*/
add_action('admin_menu', array('YGShareOptions', 'init'));
?>