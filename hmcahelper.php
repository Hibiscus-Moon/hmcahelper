<?php
/*
Plugin Name: Hibiscus Moon Helper
Plugin URI:  https://www.coreymwinter.com
Description: Custom Wordpress Functions for Hibiscus Moon
Version:     2.0.7
Author:      Corey Winter
Author URI:  https://coreymwinter.com
License:     GPLv2
*/

/**
 * Copyright (c) 2019 Corey Winter (email : hello@coreymwinter.com)
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

            require( 'includes/functions.php' );
            require( 'includes/settings.php' );
            require( 'includes/alumni.php' );
            require( 'includes/blocks.php' );
            require( 'includes/optinbar.php' );
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
    echo '<div id="progressBar" data-total="' . count($questions) . '"></div>';
    echo '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
    
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
    echo '<div id="progressBar" data-total="' . count($questions) . '"></div>';
    echo '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
    
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
    echo '<div><table class="graph">';

    foreach ($results as $key => $value) {
        echo '<tr>';
        echo '<td class="label">' . $key . '</td>';
        echo '<td colspan="3"><div class="bar" style="width: ' . ($value + 100)/2 . '%;"></div></td>';
        echo '</tr>';
    }
    
    echo '<tr>';
    echo '<td></td>
        <td>Under-active</td>
        <td>Balanced</td>
        <td>Over-active</td>';
    echo '</tr>';
    echo '</table>';
    echo '<a class="x-btn x-btn-square x-btn-regular" href="https://students.hibiscusmoon.com/courses/7/activities/69" target="_blank" style="font-size: 24px;">Back to Module #4</a>';
    echo '</div>';
}

function hmca_display_results_intelligence($results = []) {
    echo '<div><table class="graph">';

    foreach ($results as $key => $value) {
        echo '<tr>';
        echo '<td class="label">' . $key . '</td>';
        echo '<td style="width: 50%;"><div class="bar" style="width: ' . $value . '%;"></div></td>';
        echo '<td style="text-align: left;">'. $value . '%</td>';
        echo '</tr>';
    }
    echo '</table>';
    echo '<a class="x-btn x-btn-square x-btn-regular" href="https://students.hibiscusmoon.com/courses/7/activities/75" target="_blank" style="font-size: 24px;">Back to Module #5</a>';
    echo '</div>';
}


function hmca_load_quiz_file($quiz) {
    return json_decode(file_get_contents(plugin_dir_path(__FILE__) . 'quizzes/' . $quiz . '.json'));
}
