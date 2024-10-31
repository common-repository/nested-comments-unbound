<?php

/**
 * @package: Nested Comments Unbound
 * @Since: 0.89
 * @Date: February 2017
 * @Author: CK MacLeod
 * @Author: URI: http://ckmacleod.com
 * @License: GPL3
 */

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' ) ;

/**
 * SET UP SETTINGS PAGE
 */
function wp_ncu_options_page() {
    
    $version = WP_NCU_VERSION ;
   
?>
    
    <div class="wrap cks_plugins">

        <div id="cks_plugins-main">   

            <h1>NESTED COMMENTS UNBOUND</h1>

            <div id="cks_plugins-sections">

                <?php wp_ncu_main_section() ; ?>

            </div>

        </div>
        
        <?php wp_ncu_sidebar( $version ) ; ?>

        <?php wp_ncu_plugins_footer( $version ) ; ?> 
        
    </div>

<?php   }
/**
 * MAIN SETTINGS SECTION
 */
function wp_ncu_main_section() {
    
    $options = get_option( 'wp_ncu_settings' ) ;
    
    ?>

    <section>
        
        <form method="post" action="options.php">   

            <?php settings_fields( 'wp_ncu' );  ?>

            <?php do_settings_fields( 'wp_ncu', ''); ?>

            <?php wp_ncu_usage_notes( $options ) ; ?>

            <?php wp_ncu_options( $options ) ; ?>
            
            <?php wp_ncu_css() ; ?>

        </form>
    
    </section>

<?php 

}
/**
 * USAGE NOTES - INTRO
 * param array $options
*/
function wp_ncu_usage_notes( $options ) {
    
    $apply = isset( $options['apply'] ) ? $options['apply'] : 0 ;

    ?>

    <div id="wp_ncu-usage-notes" class="ck-usage-notes">
        
        <p><i><?php printf( __( '(%sJump to Settings%s)', 'wp_ncu'), '<a href="#nesting-options">', '</a>' ) ; ?></i></p>

        <h3><?php _e( 'Before Getting Started...', 'wp_ncu') ; ?></h3>
        
        <h4><?php _e( '...note that:', 'wp_ncu') ; ?></h4>
        <ol>
            <li>
                <?php _e( 'Nested Comments Unbound (NCU) is designed with standard WordPress comments templates in mind. <strong>For most WordPress themes, you will need to make a few CSS-based adjustments to achieve a good effect</strong> - especially with the "Margin-Left" adjustment below. NCU will not work with 3rd Party comment plug-ins like Disqus or Facebook Comments, and it may require careful adjustment to work with themes and plug-ins that modify standard WordPress comments substantially.', 'wp_ncu' ) ; ?> 
            </li>
            <li>
                <?php _e( 'If your commenting is based on standard WordPress templates, then NCU will add a set of CSS selectors and default styles to your comments, <strong>but only after you click the "Apply" checkbox below </strong>.', 'wp_ncu' ) ; ?>
            </li>
        </ol>
        <h3><?php _e( 'Complete Activation and Apply NCU', 'wp_ncu') ; ?></h3>
        
        <p>
            <?php _e( 'This 1-minute video illustrates the result of completing the activation of NCU using default settings and the "Twenty-Seventeen" theme:', 'wp_ncu' ) ; ?>
        </p>
        
        <div id="wp_ncu-usage-ills">
            
            <iframe width="560" height="315" style="display:block; margin:20px auto;border:1px solid gray;" src="https://www.youtube.com/embed/ZPfB6B6XrAU" frameborder="0" allowfullscreen></iframe>
            
        </div>

        <table class="form-table">
            <tr>
                <td colspan="3">
                    
                    </p>
                    <p>
                        <strong><?php _e( 'If you have not yet appropriately re-styled your comments section, applying NCU settings may produce unacceptable results. See below and sidebar illustrations for instructions and examples.', 'wp_ncu' ) ; ?></strong>
                    </p>
                </td>
            </tr>
            <tr>
                <td class="label">
                 <strong><big><?php _e( 'Apply', 'wp_ncu' ) ; ?></big></strong>
                </td>
                <td>
                    <input type="checkbox" name="wp_ncu_settings[apply]" value="1" <?php checked( $apply, 1 ) ; ?> />
                </td>
                <td class="jq-descr">
                    <p>Set threading (nesting) to "on," set maximum depth to 10,000, and add NCU comment formatting.</p>
                </td>
            </tr>
        </table>
        
        <p>
            <?php _e( 'If you un-Apply NCU, or de-activate it, your prior settings will be restored.  Your NCU settings will be saved until and unless you un-install the plug-in.', 'wp_ncu' ) ; ?>
        </p>
        
    <?php submit_button() ; ?>
        
    </div>

    <?php
        
}

/**
 * OPTIONS
 * @param array $options
 */
function wp_ncu_options( $options ) {
    
    $bp     =   isset( $options['breakpoint'] )         ? $options['breakpoint'] : 0 ;
    $tp     =   isset( $options['turnpoint'] )          ? $options['turnpoint'] : 0 ;
    $lm     =   isset( $options['left_margin'] )        ? $options['left_margin'] : '' ;
    $rm     =   isset( $options['right_margin'] )       ? $options['right_margin'] : '' ;
    $w      =   isset( $options['width'] )              ? $options['width'] : '' ;
    $removem =  isset( $options['remove_turn_margins'] )? $options['remove_turn_margins'] : 0 ;
    $bptwo  =   isset( $options['breakpointtwo'] )      ? $options['breakpointtwo'] : 0 ;
    
    ?>
    <div id="nesting-options" class="ck-usage-notes" />
    
        <h4 id="jquery" style="font-size:1.2em;clear: both;"><?php  _e( 'Adjust Nesting', 'wp_ncu' ) ; ?></h4>  

        <p>
            <?php printf( __( 'In most standard WordPress themes, in languages read left to right, you will want to set your own appropriate Left Margin. You can make the judgment by eye and trial and error, or you can compute an exact compensation based on your theme. (%sFurther details below, if the simplest approach doesn\'t work for you.%s)', 'wp_ncu' ), '<a href="#margin-left-notes">', '</a>' ) ; ?>
        </p>
        <p>
            <?php printf(__( 'Further customization will depend on your theme, your CSS skills, and your ambitions. For more detailed instructions, including examples that you can copy or modify for your Customizer or Style Sheet, visit the %sNested Comments Unbound home pages%s.', 'wp_ncu' ), '<a href="http://ckmacleod.com/wordpress-plugins/nested-comments-unbound/advanced">', '</a>' ) ; ?>
        </p>

        <table class="form-table" id="wp_ncu_jquery-settings">
            <tbody>
                <tr>
                    <td width="20%" class="label"><?php _e( 'Breakpoint', 'wp_ncu' ) ; ?></td>
                    <td width="20%"><input min="0" type="number" name="wp_ncu_settings[breakpoint]" value="<?php echo esc_attr( $bp ) ; ?>" />
                    </td>
                    <td width="60%" class="jq-descr">
                    <?php _e( 'The level at which comment "snaking" begins. <code>.ncu_super-max</code> class will be applied to all comments at depths higher than the one designated here. Default: </code>5</code>.', 'wp_ncu' ) ; ?>
                    </td>
                </tr>
                <tr>
                    <td class="label"><?php _e( 'Turnpoint', 'wp_ncu' ) ; ?></td>
                    <td><input min="0" type="number" name="wp_ncu_settings[turnpoint]" value="<?php echo esc_attr( $tp ) ; ?>" />
                    </td>
                    <td class="jq-descr">
                        <?php _e( 'How Many Comments Until We Again Turn Left or Right. Default: </code>3</code>.', 'wp_ncu' ) ; ?>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        <?php _e( 'Margin-Left', 'wp_ncu' ) ; ?>
                    </td>
                    <td>
                        <input type="text" name="wp_ncu_settings[left_margin]" value="<?php echo esc_attr( $lm ) ; ?>" />
                    </td>
                    <td class="jq-descr">
                        <?php printf( __( 'Adjust the left margin, specifying pixels or other CSS measurement, of the "turned" comments. Default: </code>-4em</code>. (%sDetails below%s.)', 'wp_ncu' ), '<a href="#margin-left-notes">', '</a>' ) ; ?>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        <?php _e( 'Margin-Right', 'wp_ncu' ) ; ?>
                    </td>
                    <td>
                        <input type="text" name="wp_ncu_settings[right_margin]" value="<?php echo esc_attr( $rm ) ; ?>" />
                    </td>
                    <td class="jq-descr">
                        <?php _e( 'Adjust the right margin, specifying pixels or other CSS measurement, of the turning comments. Default: No Adjustment. (Provisionally for "right-to-left" text.)', 'wp_ncu' ) ; ?>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        <?php _e( 'Comment Width', 'wp_ncu' ) ; ?>
                    </td>
                    <td>
                        <input type="text" name="wp_ncu_settings[width]" value="<?php echo esc_attr( $w ) ; ?>" />
                    </td>
                    <td class="jq-descr">
                        <?php _e( 'Adjust the width, in pixels or other CSS measurement, of the "snaking" comments. (Depending on your theme, percentages or "em"\'s may lead to unexpected results.) Default: No Adjustment.', 'wp_ncu' ) ; ?>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        <?php _e( 'Remove Margins for Small Screens', 'wp_ncu' ) ; ?>
                    </td>
                    <td>
                        <input type="checkbox" name="wp_ncu_settings[remove_turn_margins]" value="1" <?php checked( $removem, 1 ) ; ?> />
                    </td>
                    <td class="jq-descr">
                        <?php _e( 'If checked, will set margin-left and -right for affected comments in screens of width 640px or smaller to zero.) Default: Checked.', 'wp_ncu' ) ; ?>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        <?php _e( 'Add CSS', 'wp_ncu' ) ; ?>
                    </td>
                    <td colspan="2" >
                        <textarea name="wp_ncu_settings[add_css]" id="ncu_add-css" class="code" style="width: 50%; font-size: 12px; height: 80px;" rows="12" cols="50"><?php if ( isset( $options['add_css'] ) ) echo stripslashes( $options['add_css'] ); ?></textarea>
                        <p style="padding: 1em 0; margin-top: 2em; font-style: italic">
                         <?php _e( 'Add CSS above (though you may eventually wish to move the code to the WordPress Customizer or to your theme\'s stylesheet instead). Default: No Adjustment.', 'wp_ncu' ) ; ?>
                        </p>    
                    </td>
                </tr>
                <tr>
                    <td width="20%" class="label">
                        <?php _e( 'Second Breakpoint', 'wp_ncu' ) ; ?>
                    </td>
                    <td width="20%">
                        <input min="0" type="number" name="wp_ncu_settings[breakpointtwo]" value="<?php echo esc_attr( $bptwo ) ; ?>" />
                    </td>
                    <td width="60%" class="jq-descr">
                        <?php _e( 'Applies "ncu_super-super-max" class to all comments at depths higher than the one designated here. Default: No designation.', 'wp_ncu' ) ; ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <hr>

       <?php wp_ncu_add_credit_link( $options ) ; ?>
        
       <?php submit_button() ; ?> 
        
    </div>   
       
    <?php
    
}

/**
 * CK'S PLUGINS CREDIT LINK
 * @param array $options
 */
function wp_ncu_add_credit_link( $options ) {
    
    $option = isset( $options['add_credit'] ) ? $options['add_credit'] : '' ;
    
    ?>
    
     <h4 style="font-size:1.2em;clear: both;" id="credit"><?php _e( 'NCU Signature Link', 'wp_ncu' ) ; ?></h4>
     
     <p><?php _e( 'Adding a credit-link is a way to thank the developer and to show you support WordPress development, and also to help others find the tool and learn how to use it. The image-link is set to appear flush right above the comment reply form, discreetly at half opacity:') ?></p>
        
     <div id="admin-credit-link-example" 
        <a href="http://ckmacleod.com/wordpress-plugins/nested-comments-unbound"  title="Nested Comments Unbound by CK's Plugins" style="
            display: block;
            margin: 12px auto;
            width: 100px;" >
         <img 
            src="<?php echo plugins_url( 'images/ck_plugins_credit_link.jpg', __FILE__ ) ; ?>"  >
         </a>
    </div>
            
     <p>
         <input type="checkbox" name="wp_ncu_settings[add_credit]" value="1" <?php checked( $option, 1 ) ; ?>" />
         <span style="font-weight:700"><?php _e( 'Check this box to add the Nested Comments Unbound credit-link.', 'wp_ncu' ) ; ?></span>
    </p>
       
     
     <?php
     
}

/**
 * CSS USAGE NOTES
 */
function wp_ncu_css() {
    
    ?>
    <div id="wp_ncu-customization" class="ck-usage-notes" style="overflow:auto;display:block;">
        
        <h3>
            <?php _e( 'CSS Reference', 'wp_ncu' ) ; ?>
        </h3>
        
        <p>
            <?php _e( 'NCU adds several CSS selectors to a standard WP comment form, but the only one needed to enable snaking will in the majority of cases be the left margin, as in the setting option above, and as described in detail below and demonstrated in the sidebar illustrations.', 'wp_ncu' ) ; ?>
        </p>
        
        </p>
        
        <table id="wp_ncu-css-table" class="form-table">
            <thead>
                <th><?php _e( 'Selector', 'wp_ncu' ) ; ?></th>
                <th><?php _e( 'Purpose', 'wp_ncu' ) ; ?></th>
                <th><?php _e( 'Note', 'wp_ncu' ) ; ?></th>
            </thead>
             <tr>
                <td>
                    .ncu_breakpoint
                </td>
                <td><?php _e( '
                    Applies to comments at highest depth after which snaking begins. 
                ', 'wp_ncu' ) ; ?></td>
                <td><?php _e( '
                    Set for indicative or decorative purposes.
                ', 'wp_ncu' ) ; ?></td>
            </tr>
                         <tr>
                <td>
                    .ncu_turnpoint
                </td>
                <td><?php _e( '
                    Applies to comments at maximum depth prior to each next turn. 
                ', 'wp_ncu' ) ; ?></td>
                <td><?php _e( '
                    Set for indicative or decorative purposes.
                ', 'wp_ncu' ) ; ?></td>
            </tr>
            <tr>
                <td>
                    .ncu_super-max
                </td>
                <td><?php _e( '
                    Applies to comments at depths higher than the breakpoint.
                ', 'wp_ncu' ) ; ?></td>
                <td><?php _e( '
                    Set for indicative or decorative purposes.
                ', 'wp_ncu' ) ; ?></td>
            </tr>
            <tr>
                <td>
                    .ncu_turn
                </td>
                <td><?php _e( '
                    Applies to comments at depths higher than the "breakpoint," up to each "return" "turnpoint," and so on.
                ', 'wp_ncu' ) ; ?></td>
                <td><?php _e( '
                    For example: With breakpoint 5, turnpoint 3, affects comments at depths 6, 7, 8, 12, 13, 14, 18, 19, 20, and so on. 
                    To create a symmetrical effect, set at "<italic>-2 * [theme-margin]</italic>" (typically a ".children" or ".comment" padding or margin). However, there\'s no law that says you have to go "symmetrical."', 'wp_ncu' ) ; ?></td>
            </tr>
            <tr>
                <td>
                    .ncu_return
                </td>
                <td><?php _e( '
                    Applies to comments at depths higher than the breakpoint, after each "turn" "turnpoint."
                ', 'wp_ncu' ) ; ?></td>
                <td><?php _e( '
                    With breakpoint 5, turnpoint 3: comments at depths 9, 10, 11, 15, 16, 17, 21, 22, 23, and so on.
                    Will not need to be set in most themes, but can be for indicative or decorative purposes.
                ', 'wp_ncu' ) ; ?></td>
            </tr>
            <tr>
                <td>
                    .ncu_turn-[#]
                </td>
                <td><?php _e( '
                    The number in sequence of the "turn" to which the comment belongs. 
                ', 'wp_ncu' ) ; ?></td>
                <td><?php _e( '
                    With breakpoint 5, turnpoint 3: Comments 6-8 are ncu_turn-1, 12-14 are ncu_turn-2, and so on. Style for indicative or decorative purposes.
                ', 'wp_ncu' ) ; ?></td>
            </tr>
            <tr>
                <td>
                    .ncu_return-[#]
                </td>
                <td><?php _e( '
                    The number in sequence of the "return" to which the comment belongs. 
                ', 'wp_ncu' ) ; ?></td>
                <td><?php _e( '
                    With breakpoint 5, turnpoint 3: Comments 9-11 are ncu_return-1, 15-17 are ncu_return-2, and so on. Style for indicative or decorative purposes.
                ', 'wp_ncu' ) ; ?></td>
            </tr>
            <tr>
                <td>
                    .ncu_super-super-max
                </td>
                <td><?php _e( '
                    Applies to all comments at depth higher than whatever is designated.
                ', 'wp_ncu' ) ; ?></td>
                <td>
                    
                </td>
            </tr>
            
        </table>
        
        <h4 id="margin-left-notes"><?php _e('Styling Margin-Left', 'wp_ncu') ; ?></h4>
        <p>
            <?php _e( 'You can decide on the degree of adjustment required for your theme by eye or measurement, or you can find the in-use styles and compute the compensation to apply. In most cases, the value you set for "margin-left" will be derivable from the value of "padding" or "margin" (or "margin-left") under ".children" (or "ol.children" or "ul.children," and so on) or sometimes under ".comment" (or ".comment-list .comment," etc.).', 'wp_ncu' ) ; ?>   
        </p>
        <p>
            <?php _e( 'In the simplest case, to produce a symmetrical effect, you would <strong>multiply the total indentation set by your theme (often only one margin or padding setting) by negative 2</strong>, and use the result as the new Margin-Left setting above, unless you prefer to add the style to your theme stylesheet, the WordPress Customizer, or other CSS processor, as in: <code>.ncu_turn { margin-left: <i>[your custom compensation in pixels or ems]</i>; }</code>, as in the sidebar examples. (Other alternatives might include smaller indents for "high-depth" comments, among many other stylizations you can try.)', 'wp_ncu' ) ; ?>   
        </p>
        <p>
            <?php _e( 'With some themes or commenting templates, you will need to target your selectors more precisely to override stylesheet settings. In a few cases, you may need to edit custom commenting templates or employ other relatively advanced techniques.', 'wp_ncu' ) ; ?>   
        </p>
        
        <h4><?php _e('Advanced Styling Example', 'wp_ncu') ; ?></h4>
        
        <div id="wp_ncu-usage-ills">
            
            <iframe width="560" height="315" style="display:block; margin:20px auto;border:1px solid gray;" src="https://www.youtube.com/embed/-vkHe5oJtPU" frameborder="0" allowfullscreen></iframe>
        </div>
        
        <p>
            <?php printf( __( 'The code to create the above styling in themes based closely on WordPress Twenty Thirteen to Twenty Seventeen, adaptable for many others, can be found in %sNCU documentation.%s', 'wp_ncu' ), '<a href="https://ckmacleod.com/wordpress-plugins/nested-comments-unbound/advanced/" >', '</a>' ) ; ?>
        </p>

<?php

}
/**
 * ADDS VERSION INFO TO SIDEBAR
 * @param string $version
 */
function wp_ncu_sidebar( $version ) {

    ?>

    <div id="cks_plugins-sidebar" style="position: relative; height:100%; display: block;overflow: auto;">

        <?php wp_ncu_illustrations() ; ?>
        
        <?php wp_ncu_tip_jar() ; ?>
        
        <div id="cks_plugins-version" class="sidebar-version" >

            <p>Nested Comments Unbound<br>Version <?php 
                echo $version ; 
            ?><br><i>by CK MacLeod</i></p>

        </div>

    </div>

<?php  

}
/**
 * CK'S DONATION FORM
 * Outputs Paypal "Tip Jar"
 */
function wp_ncu_tip_jar() {
    
    ?> 
    
    <div class="ck-donation">
                
        <p><?php _e( 'If you think this plug-in saved you time, or work, '
                . 'or anxiety,<br>or money, or anyway<br>'
                . 'you\'d like to see more work like this...', 'wp_ncu' ) ; 
        ?></p>

        <div id="sos-button">

            <form id="sos-form" action="https://www.paypal.com/cgi-bin/webscr" 
                  method="post" target="_top">
                
                <input name="cmd" type="hidden" value="_xclick" />
                <input name="business" type="hidden" 
                       value="ckm@ckmacleod.com" />
                <input name="lc" type="hidden" value="US" />
                <input name="item_name" type="hidden" value="Tip CK!" />
                <input name="item_number" type="hidden" 
                       value="Nested Comments Unbound" />
                <input name="button_subtype" type="hidden" value="services" />
                <input name="no_note" type="hidden" value="0" />
                <input name="cn" type="hidden" 
                       value="Add special instructions or message:" />
                <input name="no_shipping" type="hidden" value="1" />
                <input name="currency_code" type="hidden" value="USD" />
                <input name="weight_unit" type="hidden" value="lbs" />

                <div id="ck-donate-submit-line">
                    
                    <input id="sos-amount" 
                           title="Confirm or not when you get there..." 
                           name="amount" type="text" value="" 
                           placeholder="$xx.xx" />
                    <input id="sos-submit" title="Any amount is very cool..." 
                           alt="Go to Paypal to complete" 
                           name="submit" type="submit" value="<?php _e( 
                                   '...tip me!', 'wp_ncu' ) 
                                   ?>" />
                </div>

            </form>

        </div>

    </div>
    
    <?php
}
/**
 * SIDEBAR ILLUSTRATIONS
 * captions and images change 
 * depending on display mode of image replacements
 * @param array $options
 */
function wp_ncu_illustrations() {
    
    ?>
    
    <div class="ck-illustrations">
        
         <p class="cks_plugins_admin-ill-head cks_plugins_admin-ill-head-top">
             <?php _e( 'New Discussion Settings when Plugin Fully Activated:', 'wp_ncu' ) ; ?>
        </p>
        
        <img src="<?php echo plugin_dir_url( __FILE__ ) ; 
        ?>images/new_discussion_setting.jpg" alt="<?php _e( 'New Discussion Settings', 'wp_ncu' ) ; 
        ?>" >
        
        <p class="cks_plugins_admin-ill-head cks_plugins_admin-ill-head">
             <?php _e( 'Typical Case - Finding Indentation Style (Using Chrome "Inspect" Tool) - "Llorix One Lite" Theme:', 'wp_ncu' ) ; ?>
        </p>
        
        <img src="<?php echo plugin_dir_url( __FILE__ ) ; 
        ?>images/isolating_1.jpg" alt="<?php _e( 'Finding/Llorix', 'wp_ncu' ) ; 
        ?>" >

        <p class="cks_plugins_admin-ill-head">
            <?php _e( 'Fixing (applied via this settings page or CSS stylesheet/processor of your choice):', 'wp_ncu' ) ; ?> 
        </p> 

        <img src="<?php echo plugin_dir_url( __FILE__ ) ; 
        ?>images/fixing_1.jpg" alt="<?php _e( 'Fixing/Llorix', 'wp_ncu') ; 
        ?>" > 
        
        
        <p class="cks_plugins_admin-ill-head">
            <?php _e( 'Unusual Case - Bizpress Indentation', 'wp_ncu' ) ; ?>
        </p> 

        <img src="<?php echo plugin_dir_url( __FILE__ ) ; 
        ?>images/isolating_2.jpg" alt="<?php _e( 'Finding/Bizpress', 'wp_ncu') ; 
        ?>" > 
        
        <p class="cks_plugins_admin-ill-head">
            <?php _e( 'Fixing:', 'wp_ncu' ) ; ?>
        </p>
                
        <img src="<?php echo plugin_dir_url( __FILE__ ) ; 
        ?>images/fixing_2.jpg" alt="<?php _e( 'Fixing/Bizpress', 'wp_ncu' ) ; 
        ?>" >
        
    </div>
    
    <?php
    
}
/**
 * CK'S PLUGINS FOOTER
 * @param string $version
 */
function wp_ncu_plugins_footer( $version ) {
    
    $plugin_home_page = 'http://ckmacleod.com/wordpress-plugins/'
            . 'nested-comments-unbound/'; 
    
    ?>
    
    <div id="cks_plugins_admin-footer">

        <a target="_blank" id="link-to-cks-plugins" 
           href="http://ckmacleod.com/wordpress-plugins/"><img src="<?php 
           echo plugin_dir_url( __FILE__ ) ; 
           ?>images/cks_wp_plugins_200x40.jpg"></a>
        
        <a target="_blank" id="link-to-cks-plugins-text" 
           href="http://ckmacleod.com/wordpress-plugins/">All CK's Plug-Ins</a>
        
        <a target="_blank" id="ck-home" href="<?php 
        echo $plugin_home_page ; 
        ?>">Plug-In Home Page</a>
        
        <a target="_blank" id="ck-faq" href="<?php 
        echo $plugin_home_page ; 
        ?>faq/">FAQ</a>
        
        <a target="_blank" id="ck-style" href="<?php 
        echo $plugin_home_page ; 
        ?>download-and-changelog/">Changelog</a>
        
        <a target="_blank" id="ck-help" href="<?php 
        echo $plugin_home_page ; 
        ?>support/">Requests<br>(Contact CK)</a>
        
        <a id="ck-support" class="<?php 
        echo ($version < 1 ) ? 'pre-wp-beta' : 'wordpress-link' ; ?>" 
           href="<?php echo 
           ($version < 1) ? '#" title="Beta: Not Yet at Wordpress.org"' : 
           'http://wordpress.org/support/plugin/nested-comments-unbound/" '
                   . 'target="_blank"' 
                   ?>">Support at Wordpress</a>
        
        <a id="ck-rate" class="last-link<?php echo ($version < 1 ) ? 
        ' pre-wp-beta' : ' wordpress-link' ; ?>" href="<?php echo 
        ($version < 1) ? '#" title="Beta: Not Yet at Wordpress.org"' : 
                'http://wordpress.org/support/view/plugin-reviews/'
                . 'nested-comments-unbound/" target="_blank"' ; 
        ?>" >&#9733; &#9733; &#9733; &#9733; &#9733;<br>Rate NCU!</a> 

    </div>
    
    <?php
    
}