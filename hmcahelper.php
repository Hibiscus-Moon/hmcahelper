<?php
/*
Plugin Name: Hibiscus Moon Helper
Plugin URI:  https://www.coreymwinter.com
Description: Custom Wordpress Functions for Hibiscus Moon
Version:     2.1.1
Author:      Corey Winter
Author URI:  https://coreymwinter.com
License:     GPLv2
*/

/**
 * Copyright (c) 2020 Corey Winter (email : hello@coreymwinter.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */



if(!class_exists('HMCA_Custom_Plugin'))
{
    class HMCA_Custom_Plugin
    {
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
            // Initialize Settings
            //require_once(sprintf("%s/settings.php", dirname(__FILE__)));
            //$HMCA_Custom_Plugin_Settings = new WP_Plugin_Template_Settings();

            // Require included files
            if ( file_exists( __DIR__ . '/cmb2/init.php' ) ) {
              require_once __DIR__ . '/cmb2/init.php';
            } elseif ( file_exists(  __DIR__ . '/CMB2/init.php' ) ) {
              require_once __DIR__ . '/CMB2/init.php';
            }

            require( 'vendor/cmb2-attached-posts/cmb2-attached-posts-field.php' );
            require( 'vendor/cmb2-post-search-field/cmb2_post_search_field.php' );

            require( 'includes/functions.php' );
            require( 'includes/settings.php' );
            require( 'includes/alumni.php' );
            require( 'includes/blocks.php' );
            require( 'includes/optinbar.php' );
            require( 'includes/testimonials.php' );
            require( 'includes/woocommerce.php' );

            //$plugin = plugin_basename(__FILE__);
            //add_filter("plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ));
        } // END public function __construct

        /**
         * Activate the plugin
         */
        public static function activate()
        {
            // Do nothing
        } // END public static function activate

        /**
         * Deactivate the plugin
         */
        public static function deactivate()
        {
            // Do nothing
        } // END public static function deactivate

        // Add the settings link to the plugins page
        //function plugin_settings_link($links)
        //{
            //$settings_link = '<a href="options-general.php?page=wp_plugin_template">Settings</a>';
            //array_unshift($links, $settings_link);
            //return $links;
        //}

    } // END class HMCA_Custom_Plugin
} // END if(!class_exists('HMCA_Custom_Plugin'))

if(class_exists('HMCA_Custom_Plugin'))
{
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('HMCA_Custom_Plugin', 'activate'));
    register_deactivation_hook(__FILE__, array('HMCA_Custom_Plugin', 'deactivate'));

    // instantiate the plugin class
    $hmca_custom_plugin = new HMCA_Custom_Plugin();
}






function hmca_quiz_func( $atts ) {
    $a = shortcode_atts( array(
        'ref' => 'none'
    ), $atts );

    if ( !empty($_POST) ) {

        // Display results
        if ($a['ref'] === 'chakra') {
            $results = hmca_tally_answers_chakra($a['ref'], $_POST);
            hmca_display_results_chakra($results);
        }
        
        else if ($a['ref'] === 'intelligence') {
            $results = hmca_tally_answers_intelligence($a['ref'], $_POST);
            hmca_display_results_intelligence($results);
        }

    } else {

        //Load the desired quiz.
        $quiz_data = hmca_load_quiz_file($a['ref']);

        // Display the quiz
        if ($a['ref'] === 'chakra')
            hmca_quiz_form_chakra($quiz_data->questions);
        
        else if ($a['ref'] === 'intelligence')
            hmca_quiz_form_intelligence($quiz_data->questions);
    }
    
}
add_shortcode( 'hmca-quiz', 'hmca_quiz_func' );

function hmca_enqueue_scripts() {
    wp_register_script('hmca_quiz_script', plugins_url('/quizzes/hmca-quiz-functions.js', __FILE__), array('jquery'));
    wp_register_style('hmca_quiz_style', plugins_url('/quizzes/style.css', __FILE__));
    wp_enqueue_script('hmca_quiz_script');
    wp_enqueue_style('hmca_quiz_style');
}
add_action('wp_enqueue_scripts', 'hmca_enqueue_scripts');


function hmca_quiz_form_chakra( $questions = [] ) {
    echo '<h1>Chakra Quiz</h1>';
    echo '<div id="progressBar" data-total="' . count($questions) . '"></div>';
    echo '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post" class="chakraquiz">';
    
    //Questions
    foreach ($questions as $question) {
        echo '<div id="question' . $question->id . '" style="display: ' . ($question->id > 1 ? 'none' : 'block') . ';">
            <label for="q' . $question->id . '"><strong>' . $question->question . '</strong></label><br>';
        
        $checked = 'checked';
        foreach ($question->answers as $answer) {
            echo '<label><input type="radio" name="q' . $question->id . '" value="' . $answer->value . '" ' . $checked . '> ' . $answer->answer . '</label><br>';
            $checked = '';
        }

        echo '<br>';

        if ($question->id == 1 && count($questions) > 1) { // If it's the first question show Next button
            echo '<button type="button" onclick="loadNextQuestion(' . $question->id . ')">Next</button>';
        }
        else if ($question->id == count($questions)) { // If it's the last question.
            if (count($questions) > 1) {
                echo '<button type="button" onclick="loadPrevQuestion(' . $question->id . ')">Previous</button>&nbsp;&nbsp;';
            }
            echo '<input type="submit" value="Submit">';
        }
        else {
            echo '<button type="button" onclick="loadPrevQuestion(' . $question->id . ')">Previous</button>&nbsp;&nbsp;';
            echo '<button type="button" onclick="loadNextQuestion(' . $question->id . ')">Next</button>';
        }
        
        echo '</div>';
    }

    echo '</form>';
}

function hmca_quiz_form_intelligence( $questions = [] ) {
	echo '<h1>Multiple Intelligence Quiz</h1>';
    echo '<div id="progressBar" data-total="' . count($questions) . '"></div>';
    echo '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post" class="intelligencequiz">';
    
    //Questions
    foreach ($questions as $question) {
        echo '<div id="question' . $question->id . '" style="display: ' . ($question->id > 1 ? 'none' : 'block') . ';">
            <p><strong>' . $question->question . '</strong></p>';

        echo '<table><thead>';
        echo '<tr style="font-size: smaller;"><th></th>';
        foreach ($question->headers as $header) {
            echo '<th>' . $header . '</th>';
        }
        echo '</tr></thead>';
        echo '<tbody>';

        $answer_index = 0;
        foreach ($question->answers as $answer) {
            echo '<tr><td>' . $answer->answer . '</td>';
            $checked = 'checked';
            for ($i = 0; $i < 5; $i++) {
                echo '<td><input name="q' . $question->id . '-' . $answer_index . '" value="' . $i . '" type="radio" ' . $checked . '></td>';
                $checked = '';
            }
            echo '</tr>';
            $answer_index++;
        }

        echo '</tbody></table>';

        echo '<br>';

        if ($question->id == 1 && count($questions) > 1) { // If it's the first question show Next button
            echo '<button type="button" onclick="loadNextQuestion(' . $question->id . ')">Next</button>';
        }
        else if ($question->id == count($questions)) { // If it's the last question.
            if (count($questions) > 1) {
                echo '<button type="button" onclick="loadPrevQuestion(' . $question->id . ')">Previous</button>&nbsp;&nbsp;';
            }
            echo '<input type="submit" value="Submit">';
        }
        else {
            echo '<button type="button" onclick="loadPrevQuestion(' . $question->id . ')">Previous</button>&nbsp;&nbsp;';
            echo '<button type="button" onclick="loadNextQuestion(' . $question->id . ')">Next</button>';
        }
        
        echo '</div>';
    }

    echo '</form>';
}


function hmca_tally_answers_chakra( $quiz, $answers = [] ) {
    //Load the desired quiz.
    $quiz_data = hmca_load_quiz_file($quiz);
    $results = [];

    foreach ($answers as $q_id => $value) {
        // Determine what group the question belongs to.
        foreach ($quiz_data->questions as $question) {
            if ($q_id == 'q' . $question->id) {
                $group = $question->question_section;
                break;
            }
        }

        // Add the score to the group's total.
        $results[$group] += $value * $quiz_data->score_multiplier;
    }

    return $results;

}

function hmca_tally_answers_intelligence( $quiz, $answers = [] ) {
    //Load the desired quiz.
    $quiz_data = hmca_load_quiz_file($quiz);
    $results = [];

    foreach ($answers as $q_id => $value) {
        // Determine what group the question belongs to.
        foreach ($quiz_data->questions as $question) {
            if (strpos($q_id, 'q' . $question->id . '-') === 0) {
                foreach ($question->answers as $option) {
                    if ($q_id == 'q' . $question->id . '-' . $option->id) {
                        $group = $option->group;
                        break;
                    }
                }
                break;
            }
        }

        // Add the score to the group's total.
        $results[$group] += $value * $quiz_data->score_multiplier;
    }

    return $results;

}


function hmca_display_results_chakra($results = []) {
    echo '<div><table class="graph" style="width: 100%;">';

    foreach ($results as $key => $value) {
        echo '<tr style="border-top: 1px solid #eaeaea;">';
        echo '<td class="label" style="padding-right: 10px;">' . $key . '</td>';
        echo '<td colspan="3"><div class="bar" style="width: ' . ($value + 100)/2 . '%;"></div></td>';
        echo '</tr>';
    }
    
    echo '<tr style="border-top: 1px solid #eaeaea;">';
    echo '<td></td>
        <td>Under-active</td>
        <td>Balanced</td>
        <td>Over-active</td>';
    echo '</tr>';
    echo '</table>';
    echo '<a class="button" href="https://students.hibiscusmoon.com" target="_blank" style="font-size: 16px !important;">Back to the Course</a>';
    echo '</div>';
}

function hmca_display_results_intelligence($results = []) {
    echo '<div><table class="graph" style="width: 100%;">';

    foreach ($results as $key => $value) {
        echo '<tr style="border-top: 1px solid #eaeaea;">';
        echo '<td class="label" style="padding-right: 10px;">' . $key . '</td>';
        echo '<td style="width: 50%;"><div class="bar" style="width: ' . $value . '%;"></div></td>';
        echo '<td style="text-align: left;">'. $value . '%</td>';
        echo '</tr>';
    }
    echo '</table>';
    echo '<a class="button" href="https://students.hibiscusmoon.com" target="_blank" style="font-size: 16px !important;">Back to the Course</a>';
    echo '</div>';
}


function hmca_load_quiz_file($quiz) {
    return json_decode(file_get_contents(plugin_dir_path(__FILE__) . 'quizzes/' . $quiz . '.json'));
}


function hmca_page_redirect_metabox() {

	$p = 'hmca_';

	$cmb = new_cmb2_box( array(
		'id'            => $p . 'redirect_metabox',
		'title'         => __( 'Redirect Meta', 'zbt' ),
		'object_types'  => array( 'page' ),
		'context'       => 'normal',
		'priority'      => 'default',
	) );

	$now = date( 'm-d-y, H:i' );

	$cmb->add_field( array(
		'name'  => __( 'Time and date to start redirecting', 'zbt' ),
		'desc'  => __( 'Will redirect any time after this. Compares a UTC timestamp to webserver time, currently: ' . $now . ' (mm-dd-yy, hh:mm)', 'zbt' ),
		'id'    => $p . 'time_to_redirect',
		'type'  => 'text_datetime_timestamp',
	) );

	$cmb->add_field( array(
		'name'              => __( 'Redirect to:', 'zbt' ),
		'id'                => $p . 'redirect_to',
		'type'              => 'post_search_text', // This field type
		'post_type'         => array( 'page' ),
		'desc'              => __( 'Use search icon popup to find a page, or enter the <code>Post ID</code> to redirect to. You may also enter a fully qualified url. For example: <code>https://wpguru4u.com</code>', 'zbt' ),
		'select_type'       => 'radio',
		'select_behavior'   => 'replace',
	) );

	$cmb->add_field( array(
		'name'  => __( 'Time and date to stop redirecting (optional)', 'zbt' ),
		'desc'  => __( 'Will stop redirecting on this time/date (using the same format above) and will delete the Redirect Meta.', 'zbt' ),
		'id'    => $p . 'delete_redirect',
		'type'  => 'text_datetime_timestamp',
	) );
}
add_action( 'cmb2_admin_init', 'hmca_page_redirect_metabox' );


/**
 * Maybe redirect 
 * 
 * @since 0.0.1
 */
function hmca_page_maybe_redirect() {
	global $post;
	if ( ! is_object( $post ) || is_search() ) {
		return;
	}

	if ( current_user_can( 'administrator' ) )
		return;

	$p = 'hmca_';

	$where = get_post_meta( $post->ID, $p . 'redirect_to', true );
	if ( ! $where ) 
		return;

	$now = time();
	$then =  get_post_meta( $post->ID, 'time_to_redirect', true );

	if ( $now > $then && $where ) {

		$stop = get_post_meta( $post->ID, 'delete_redirect', true );

		if ( $stop && $now > $stop ) {

			delete_post_meta( $post->ID, 'redirect_to' );
			delete_post_meta( $post->ID, 'time_to_redirect' );
			delete_post_meta( $post->ID, 'delete_redirect' );
			if ( function_exists( 'w3tc_flush_post' ) )
				w3tc_flush_post( $post->ID );

		} else {
			hmca_page_redirect( $where );
		}
	}
}

/**
 * Redirect 
 * 
 * @since 0.0.1
 */
function hmca_page_redirect( $where ) {
	if ( $where ) {
		if ( (int) $where ) {
			wp_safe_redirect( esc_url( get_permalink( $where ) ) );
		} else {
			wp_redirect( $where );
		}
		exit;
	}
}
add_action( 'template_redirect', 'hmca_page_maybe_redirect' );




function hmca_page_core_metabox() {

	$p = 'hmca_';

	$cmb = new_cmb2_box( array(
		'id'            => $p . 'metabox',
		'title'         => 'HMCA Meta',
		'object_types'  => array( 'page' ),
		'context'       => 'normal',
		'priority'      => 'default',
	) );

	$cmb->add_field( array(
		'name'  => 'Enable Zopim Chat',
		'id'    => $p . 'zopim',
		'type'  => 'checkbox',
	) );

}
add_action( 'cmb2_admin_init', 'hmca_page_core_metabox' );


function hmca_acc_shortcode( $atts = array() ) { 
	$output = ob_start(); ?>
	<div class="accreditations" style="text-align: center">
		<a href="https://myiict.com/" target="_blank">
			<img alt="Accredited In" src="https://hibiscusmooncrystalacademy.com/wp-content/themes/hibiscusmoon/img/seals/iict.png">
		</a>
		<table style="margin: 0 auto">
			<tr>
				<td>
					<a href="http://www.worldmeta.org/" target="_blank">
						<img alt="Accredited In" src="https://hibiscusmooncrystalacademy.com/wp-content/themes/hibiscusmoon/img/seals/world-metaphysical-association.jpg" width="128">
					</a>
				</td>
				<td>
					<a href="http://www.ach-accreditation.org/" target="_blank">
						<img alt="Accredited In" src="https://hibiscusmooncrystalacademy.com/wp-content/themes/hibiscusmoon/img/seals/achh.jpg"  width="128">
					</a>
				</td>
			</tr>
			<tr>
				<td>
					<a href="http://www.issseem.org/" target="_blank">
						<img alt="Accredited In" src="https://hibiscusmooncrystalacademy.com/wp-content/themes/hibiscusmoon/img/seals/ieha.png"  width="128">
					</a>
				</td>
				<td>
					<a href="http://getconnected.resonance.is/" target="_blank">
						<img alt="Accredited In" src="https://hibiscusmooncrystalacademy.com/wp-content/themes/hibiscusmoon/img/seals/resonance-project-foundation.jpg"  width="128">
					</a>
				</td>
			</tr>
		</table>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'hmca_acc', 'hmca_acc_shortcode' );

function hmca_optin_shortcode( $atts = array() ) {
	$defaults = array (
		'id' => '53'
	);
	// Parse incoming $atts into an array and merge it with $defaults
	$args = wp_parse_args( $atts, $defaults );
	$id = $args['id'];

	ob_start(); ?>
	<div id="hmca_ac_form" class="hmca_ac_form _form_<?php echo $id ?>"></div><script src="https://hibiscusmoon.activehosted.com/f/embed.php?id=<?php echo $id ?>" type="text/javascript" charset="utf-8"></script>
	<?php
	return ob_get_clean();
}
add_shortcode( 'hmca_optin', 'hmca_optin_shortcode' );




function hmca_my_theme_custom_upload_mimes( $existing_mimes ) { 
    $existing_mimes['woff'] = 'application/x-font-woff';
    $existing_mimes['ttf'] = 'font/ttf';
    $existing_mimes['eot'] = 'font/eot';


    // Add webm to the list of mime types. $existing_mimes['webm'] = 'video/webm';
    // Return the array back to the function with our added mime type.
    return $existing_mimes;
}
add_filter( 'mime_types', 'hmca_my_theme_custom_upload_mimes' );
