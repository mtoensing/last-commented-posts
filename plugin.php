<?php
/**
 * Plugin Name: Recent Commented Posts 
 * Plugin URI: https://marc.tv/
 * Description: Adds a block that lists the recent commented posts.
 * Version: 0.9
 * Author: Marc Tönsing
 * Author URI: https://marc.tv
 * Text Domain: rcpb
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace RCPB;

defined('ABSPATH') || exit;

/**
  * Initalise frontend and backend and register block
**/
add_action('init', __NAMESPACE__ . '\\init');
add_action('init', __NAMESPACE__ . '\\register_block');

/* Init RCPB */
function init() {
    wp_register_script(
      'rcpb-js',
      plugins_url('build/index.js', __FILE__),
      [ 'wp-i18n', 'wp-blocks', 'wp-editor', 'wp-element', 'wp-server-side-render'],
      filemtime(plugin_dir_path(__FILE__) . 'build/index.js')
    );

    add_filter('plugin_row_meta', __NAMESPACE__ . '\\rcpb_plugin_meta', 10, 2);

    wp_register_style(
      'rcpb-frontend',
      plugins_url( 'style.css', __FILE__ ),
      array( ),
      filemtime( plugin_dir_path( __FILE__ ) . 'style.css' )
    );

    wp_register_style(
      'rcpb-editor',
      plugins_url('editor.css', __FILE__),
      array( 'wp-edit-blocks' ),
      filemtime(plugin_dir_path(__FILE__) . 'editor.css')
    );

    wp_set_script_translations('rcpb-js', 'rcpb');
}


/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 */

function register_block() {
    if (! function_exists('register_block_type')) {
        // Gutenberg is not active.
        return;
    }

    register_block_type('rcpb/list', [
    'editor_script' => 'rcpb-js',
    'editor_style' => 'rcpb-editor',
    'style' => 'rcpb-frontend',
    'render_callback' => __NAMESPACE__ . '\\render_callback',
    'attributes' => array(
        'max_level' => array(
          'type' => 'integer',
          'default' => 5,
        ),
        'updated' => array(
          'type' => 'number',
          'default' => 0,
          '_builtIn' => true,
        ),
    )]);
}

/**
 * Render block output 
 *
 */

function render_callback($attributes, $content) {
    //add only if block is used in this post.
    add_filter('render_block', __NAMESPACE__ . '\\filter_block', 10, 2);

    $query_result = query_posts_with_recent_comments($attributes['max_level']);
    format_last_commented_list($query_result);

    $html = format_last_commented_list($query_result);
    
    return $html;
}


function rcpb_plugin_meta( $links, $file ) {

  if ( false !== strpos( $file, 'recent-comments-block' ) ) {
     $links = array_merge( $links, array( '<a href="https://marc.tv/out/donate">' . __( 'Donate', 'simpletoc' ) . '</a>' ) );
  }

  return $links;
}



function filter_block($block_content, $block) {
  $className = '';

  if ($block['blockName'] !== 'core/heading') {
      return $block_content;
  }

  return $block_content;
}

function query_posts_with_recent_comments($limit)
    {

        global $wpdb;

        $query = "select
             wp_posts.*,
             coalesce((
             select
                max(comment_date)
             from
                $wpdb->comments wpc
             where
                wpc.comment_post_id = wp_posts.id
                AND comment_approved = 1
                AND post_password = ''
                AND comment_type NOT IN ( 'pingback' )
                AND comment_type NOT IN ( 'trackback' )
            ),
             wp_posts.post_date  ) as mcomment_date
          from
             $wpdb->posts wp_posts
          where
             post_type = 'post'
             and post_status = 'publish'
             and comment_count > '0'
          order by
     mcomment_date desc limit $limit";

        $query_result = $wpdb->get_results($query);

        return $query_result;
    }

    function format_last_commented_list($results)
    {
        $html = '<ol class="wp-block-last-commented-posts">';
        $icon = '<svg style="height: 0.75em; padding-right: 4px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M10 9.25c-2.27 0-2.73-3.44-2.73-3.44C7 4.02 7.82 2 9.97 2c2.16 0 2.98 2.02 2.71 3.81 0 0-.41 3.44-2.68 3.44zm0 2.57L12.72 10c2.39 0 4.52 2.33 4.52 4.53v2.49s-3.65 1.13-7.24 1.13c-3.65 0-7.24-1.13-7.24-1.13v-2.49c0-2.25 1.94-4.48 4.47-4.48z"/></g></svg>';

        foreach ($results as $result) {

            $html .= '<li>';

            $comment = get_first_approved_comment($result->ID);

            //$startTime = microtime(true);
            $comment_url = get_comment_link($comment);
            //echo "<!-- Elapsed time is: ". (microtime(true) - $startTime) ." seconds -->";
            $authorname = $comment->comment_author;

            if (strlen($authorname) > 20) {
                $authorname = substr($authorname, 0, 18) . '...';
            }
            
            $comment_user = '<a href="' . $comment_url . '">' .  get_the_title($result->ID) . '<br><span class="comment-author-link">' . $icon . $authorname . '</span></a>';

            $html .= $comment_user;

            $html .= '</li>';

        }

        $html .= '</ol>';

        return $html;
    }

    function get_first_approved_comment($post_id)
    {
        $comments = get_comments(
            array('status' => 'approve',
                'post_id' => $post_id,
                'number' => 1)
        );
        $comment = $comments[0];

        return $comment;
    }

