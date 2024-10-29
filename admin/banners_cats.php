<?php
defined( 'ABSPATH' ) or die("No script kiddies please!");

if(!class_exists('wpwbsBannerAdminCats')){
	class wpwbsBannerAdminCats {
		public function __construct(){			
			add_action('admin_init', array($this,'taxonomy_metadata_init'));
			
		}
		
		/**
		 * Add additional taxonomy fields to all public taxonomies
		 */
		public function taxonomy_metadata_init() {
			
			//if(!current_user_can('manage_options'))return;
			
			$taxonomy1 = WPBSC_TAXONOMY_01;
			if(!empty($taxonomy1)){
				// Add fields to "add" and "edit" term pages
				add_action("{$taxonomy1}_add_form_fields", array($this,'taxonomy_metadata_edit'), 99,1);
				add_action("{$taxonomy1}_edit_form_fields", array($this,'taxonomy_metadata_edit'), 99,1);
				// Process and save the data
				add_action("created_{$taxonomy1}", array($this,'save_taxonomy_metadata'), 99,1);
				add_action("edited_{$taxonomy1}", array($this,'save_taxonomy_metadata'), 99,1);
			}
			
			$taxonomy2 = WPBSC_TAXONOMY_02;
			if(!empty($taxonomy2)){
				// Add fields to "add" and "edit" term pages
				add_action("{$taxonomy2}_add_form_fields", array($this,'taxonomy_metadata_edit'), 99,1);
				add_action("{$taxonomy2}_edit_form_fields", array($this,'taxonomy_metadata_edit'), 99,1);
				// Process and save the data
				add_action("created_{$taxonomy2}", array($this,'save_taxonomy_metadata'), 99,1);
				add_action("edited_{$taxonomy2}", array($this,'save_taxonomy_metadata'), 99,1);
			}
			
			$taxonomy3 = WPBSC_TAXONOMY_03;
			if(!empty($taxonomy3)){
				// Add fields to "add" and "edit" term pages
				add_action("{$taxonomy3}_add_form_fields", array($this,'taxonomy_metadata_edit'), 99,1);
				add_action("{$taxonomy3}_edit_form_fields", array($this,'taxonomy_metadata_edit'), 99,1);
				// Process and save the data
				add_action("created_{$taxonomy3}", array($this,'save_taxonomy_metadata'), 99,1);
				add_action("edited_{$taxonomy3}", array($this,'save_taxonomy_metadata'), 99,1);
			}
			
			$taxonomy4 = WPBSC_TAXONOMY_04;
			if(!empty($taxonomy4)){
				// Add fields to "add" and "edit" term pages
				add_action("{$taxonomy4}_add_form_fields", array($this,'taxonomy_metadata_edit'), 99,1);
				add_action("{$taxonomy4}_edit_form_fields", array($this,'taxonomy_metadata_edit'), 99,1);
				// Process and save the data
				add_action("created_{$taxonomy4}", array($this,'save_taxonomy_metadata'), 99,1);
				add_action("edited_{$taxonomy4}", array($this,'save_taxonomy_metadata'), 99,1);
			}
			
			$taxonomy5 = WPBSC_TAXONOMY_05;
			if(!empty($taxonomy5)){
				// Add fields to "add" and "edit" term pages
				add_action("{$taxonomy5}_add_form_fields", array($this,'taxonomy_metadata_edit'), 99,1);
				add_action("{$taxonomy5}_edit_form_fields", array($this,'taxonomy_metadata_edit'), 99,1);
				// Process and save the data
				add_action("created_{$taxonomy5}", array($this,'save_taxonomy_metadata'), 99,1);
				add_action("edited_{$taxonomy5}", array($this,'save_taxonomy_metadata'), 99,1);
			}
			
			$taxonomy6 = WPBSC_TAXONOMY_06;
			if(!empty($taxonomy6)){
				// Add fields to "add" and "edit" term pages
				add_action("{$taxonomy6}_add_form_fields", array($this,'taxonomy_metadata_edit'), 99,1);
				add_action("{$taxonomy6}_edit_form_fields", array($this,'taxonomy_metadata_edit'), 99,1);
				// Process and save the data
				add_action("created_{$taxonomy6}", array($this,'save_taxonomy_metadata'), 99,1);
				add_action("edited_{$taxonomy6}", array($this,'save_taxonomy_metadata'), 99,1);
			}
			
			$taxonomy7 = WPBSC_TAXONOMY_07;
			if(!empty($taxonomy7)){
				// Add fields to "add" and "edit" term pages
				add_action("{$taxonomy7}_add_form_fields", array($this,'taxonomy_metadata_edit'), 99,1);
				add_action("{$taxonomy7}_edit_form_fields", array($this,'taxonomy_metadata_edit'), 99,1);
				// Process and save the data
				add_action("created_{$taxonomy7}", array($this,'save_taxonomy_metadata'), 99,1);
				add_action("edited_{$taxonomy7}", array($this,'save_taxonomy_metadata'), 99,1);
			}
		}
		
		public function get_settings_data($term_id=0){
			$settings = array();
			$settings[] = array(
							'term_id'	=>	$term_id,
							'field_id'	=>	'wpbsc_before_header_id',
							'title'		=>	__('Display Above Header','wpwbs'),
							'desc'		=>	__('It will display slider above/after site header.','wpwbs')
						);
			$settings[] = array(
							'term_id'=>$term_id,
							'field_id'=>'wpbsc_after_header_id',
							'title'=>__('Display Below Header','wpwbs'),
							'desc'=>__('It will display slider below/after site header.','wpwbs')
						);
			$settings[] = array(
							'term_id'=>$term_id,
							'field_id'=>'wpbsc_before_footer_id',
							'title'=>__('Display Above Footer','wpwbs'),
							'desc'=>__('It will display slider before/above footer.','wpwbs')
						);
			$settings[] = array(
							'term_id'=>$term_id,
							'field_id'=>'wpbsc_after_footer_id',
							'title'=>__('Display Below Footer','wpwbs'),
							'desc'=>__('It will display slider below/after footer.','wpwbs')
						);
			
			return apply_filters('wpw_banner_slider_admin_category_settings_data_array',$settings);
		}
		
		/**
		 * Save taxonomy metadata
		 *
		 * Currently the Taxonomy Metadata plugin is needed to add a few features to the WordPress core
		 * that allow us to store this information into a new database table
		 *
		 *	http://wordpress.org/extend/plugins/taxonomy-metadata/
		 */
		public function save_taxonomy_metadata( $term_id ) {
			
			$data_arr = $this->get_settings_data($term_id);
			if(!empty($data_arr)){
				for($i=0;$i<count($data_arr);$i++){
					$field_id = empty($data_arr[$i]['field_id']) ? '' : $data_arr[$i]['field_id'];
					if($term_id>0 && !empty($field_id)){
						update_term_meta( $term_id, $field_id, esc_attr(absint($_POST[$field_id])) );
					}
				}
			}
			
			do_action('wpw_banner_slider_category_meta_save',$term_id);
		}
		
		public function taxonomy_meta_edit_html($args=array()){
			$term_id = $args['term_id'];
			$field_id = $args['field_id'];
			$title = $args['title'];
			$desc = $args['desc'];
			if($term_id>0){
				$field_val = absint(get_term_meta($term_id, $field_id, true));
			}
			?>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="<?php echo $field_id;?>"><?php echo $title; ?></label>
				</th>
				<td>
					<select name="<?php echo $field_id;?>">
					<?php 
					global $wpdb,$wpwbs_banner_slider_posttype;
					$wpwbs_banner_slider_posttype->get_slider_select_options($field_val);
					?>
					</select>
					<?php if(!empty($field_val)):?>
&nbsp;&nbsp;<a target="_blank" href="post.php?post=<?php echo $field_val;?>&action=edit"><?php _e('edit banner','wpwbs');?></a>
<?php endif;?>
					<?php if(!empty($desc)){?><p class="description"><?php echo $desc; ?></p><?php }?>
				</td>
			</tr>
			<?php
			$action_var = $field_id.'_category_meta';
			do_action($action_var,$term_id);
			
		}
		/**
		 * Add additional fields to the taxonomy edit view
		 * e.g. /wp-admin/edit-tags.php?action=edit&taxonomy=category&tag_ID=27&post_type=post
		 */
		public function taxonomy_metadata_edit( $tag ) {
			
			// Only allow users with capability to publish content
			if ( current_user_can( 'publish_posts' ) ): ?>
			
			<?php
				$term_id = empty($tag->term_id) ? 0 : absint($tag->term_id);				
			?>
			
			<tr class="form-field">
				<td colspan="2"><b style="color:brown;font-size:14px;font-weight:bold;padding-left:0;"><u><?php _e('Banner Slider Settings','wpwbs'); ?></u></b>
				<br />
				<small><i><?php _e('Create new banner slider from wp-admin > <a target="_blank" href="edit.php?post_type=wpwbs">Banner Slider</a> (left menu).','wpwbs'); ?></i></small>
				</td>
			</tr>
			
			<?php do_action('wpw_banner_slider_category_meta_start',$term_id);?>
			
			<?php
			$data_arr = $this->get_settings_data($term_id);
			if(!empty($data_arr)){
				for($i=0;$i<count($data_arr);$i++){
					$args = array();
					$args = empty($data_arr[$i]) ? array() : $data_arr[$i];
					$this->taxonomy_meta_edit_html($args);										
				}
			}			
			
			do_action('wpw_banner_slider_category_meta_end',$term_id);?>
			
			<?php endif;
		}
		
	}
}
global $wpwbs_banner_admin_cats;
$wpwbs_banner_admin_cats = new wpwbsBannerAdminCats();