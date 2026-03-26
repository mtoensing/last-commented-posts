<?php
/**
 * Plugin Name: Last Commented Posts Block
 * Plugin URI:  https://marc.tv/
 * Description: Adds a block that lists the recent commented posts.
 * Version:     2.9.0
 * Author:      Marc Tönsing
 * Author URI:  https://toensing.com
 * Text Domain: lastcommentedposts
 * Domain Path: /languages
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace RCPB;

defined('ABSPATH') || exit;

const PLUGIN_VERSION = '2.9.0';
const CACHE_TTL = 15 * MINUTE_IN_SECONDS;
const CACHE_VERSION_OPTION = 'rcpb_cache_version';

/**
  * Initalise frontend and backend and register block
**/
add_action('init', __NAMESPACE__ . '\\init');
add_action('init', __NAMESPACE__ . '\\register_block');
add_action('comment_post', __NAMESPACE__ . '\\invalidate_cache');
add_action('edit_comment', __NAMESPACE__ . '\\invalidate_cache');
add_action('delete_comment', __NAMESPACE__ . '\\invalidate_cache');
add_action('wp_set_comment_status', __NAMESPACE__ . '\\invalidate_cache');
add_action('transition_comment_status', __NAMESPACE__ . '\\invalidate_cache');
add_action('save_post_post', __NAMESPACE__ . '\\invalidate_cache');
add_action('deleted_post', __NAMESPACE__ . '\\maybe_invalidate_post_cache');
add_action('trashed_post', __NAMESPACE__ . '\\maybe_invalidate_post_cache');
add_action('untrashed_post', __NAMESPACE__ . '\\maybe_invalidate_post_cache');

/* Init RCPB */
function init() {
    add_filter('plugin_row_meta', __NAMESPACE__ . '\\rcpb_plugin_meta', 10, 2);
}


/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 */

function register_block() {
  register_block_type(__DIR__ . '/build', [
    'render_callback' => __NAMESPACE__ . '\\render_callback'
  ]);
}


/**
 * Render block output 
 *
 */

function render_callback( $attributes, $content ) {
    $attributes = wp_parse_args(
        $attributes,
        array(
            'max_level' => 5,
            'align' => '',
        )
    );

    $results = get_recently_commented_posts( (int) $attributes['max_level'] );

    return format_last_commented_list( $results, $attributes );
}


function rcpb_plugin_meta( $links, $file ) {

  if ( false !== strpos( $file, 'last-commented-posts' ) ) {
     $links = array_merge( $links, array( '<a href="https://marc.tv/out/donate">' . __( 'Donate', 'lastcommentedposts' ) . '</a>' ) );
  }

  return $links;
}

function get_recently_commented_posts( $limit ) {
    $limit = max( 1, min( 10, absint( $limit ) ) );
    $cache_key = get_cache_key( $limit );
    $cached_results = get_transient( $cache_key );

    if ( false !== $cached_results ) {
        return is_array( $cached_results ) ? $cached_results : array();
    }

    $results = array();
    $seen_post_ids = array();
    $offset = 0;
    $batch_size = max( 25, $limit * 10 );
    $max_batches = 5;

    for ( $batch = 0; $batch < $max_batches && count( $results ) < $limit; $batch++ ) {
        $comments = get_comments(
            array(
                'status'                    => 'approve',
                'number'                    => $batch_size,
                'offset'                    => $offset,
                'orderby'                   => 'comment_date_gmt',
                'order'                     => 'DESC',
                'type__not_in'              => array( 'pingback', 'trackback' ),
                'post_type'                 => 'post',
                'post_status'               => 'publish',
                'hierarchical'              => false,
                'update_comment_meta_cache' => false,
            )
        );

        if ( empty( $comments ) ) {
            break;
        }

        foreach ( $comments as $comment ) {
            $post_id = (int) $comment->comment_post_ID;

            if ( isset( $seen_post_ids[ $post_id ] ) ) {
                continue;
            }

            $post = get_post( $post_id );

            if (
                ! $post ||
                'post' !== $post->post_type ||
                'publish' !== $post->post_status ||
                post_password_required( $post )
            ) {
                continue;
            }

            $seen_post_ids[ $post_id ] = true;
            $results[] = array(
                'post_title'      => get_the_title( $post ),
                'comment_author'  => $comment->comment_author,
                'comment_url'     => get_comment_link( $comment ),
            );

            if ( count( $results ) >= $limit ) {
                break;
            }
        }

        $offset += count( $comments );
    }

    set_transient( $cache_key, $results, CACHE_TTL );

    return $results;
}

function format_last_commented_list( $results, $attributes ) {
    if ( empty( $results ) ) {
        return '';
    }

    $alignclass = '';
    if ( ! empty( $attributes['align'] ) ) {
        $alignclass = 'align' . sanitize_html_class( $attributes['align'] );
    }

    $icon = '– ';
    $is_backend = defined( 'REST_REQUEST' ) && true === REST_REQUEST && 'edit' === filter_input( INPUT_GET, 'context' );
    $html = '<ol class="wp-block-last-commented-posts ' . esc_attr( $alignclass ) . '">';

    foreach ( $results as $result ) {
        $html .= '<li>';

        if ( $is_backend ) {
            $html .= '<span>' . esc_html( $result['post_title'] ) . '</span>';
        } else {
            $html .= '<a href="' . esc_url( $result['comment_url'] ) . '">' . esc_html( $result['post_title'] ) . '</a>';
        }

        $html .= '<br><span class="comment-author-link">' . esc_html( $icon . $result['comment_author'] ) . '</span>';
        $html .= '</li>';
    }

    $html .= '</ol>';

    return $html;
}

function get_cache_key( $limit ) {
    return 'rcpb_' . str_replace( '.', '_', PLUGIN_VERSION ) . '_' . get_cache_version() . '_' . absint( $limit ) . '_' . ( is_editor_request() ? 'editor' : 'front' );
}

function get_cache_version() {
    $version = get_option( CACHE_VERSION_OPTION );

    if ( empty( $version ) ) {
        $version = '1';
        update_option( CACHE_VERSION_OPTION, $version, false );
    }

    return (string) $version;
}

function invalidate_cache() {
    update_option( CACHE_VERSION_OPTION, (string) microtime( true ), false );
}

function maybe_invalidate_post_cache( $post_id ) {
    if ( 'post' === get_post_type( $post_id ) ) {
        invalidate_cache();
    }
}

function is_editor_request() {
    return defined( 'REST_REQUEST' ) && true === REST_REQUEST && 'edit' === filter_input( INPUT_GET, 'context' );
}
