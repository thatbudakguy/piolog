<?php /* Template Name: Page - Custom Staff */ ?>
<?php get_header(); ?>
<div class="page-full-width">
<?php
    
	mh_newsdesk_lite_before_page_content();
	
    /**
    
    User IDs, in case these are ever needed...
        
    $all_staff = array(
        'eic' => 14,
        'mg_editor' => 15,
        'features_ed' => 8,
        'news_ed_1' => 5,
        'news_ed_2' => 6,
        'arts_ed_1' => 9,
        'arts_ed_2' => 10,
        'sports_ed_1' => 11,
        'sports_ed_2' => 12,
        'media_ed' => 13,
        'biz_mg' => 17,
        'copy_ed' => 18,
        'soc_med_ed' => 16,                
    );
    
    **/
    
    /**
    
    Set up the arrays of user objects for each class of staff
    
    **/
    
    $leaders = array(14,15);                                                                // EIC and Managing editor are selected by ID because they're special
    
    $lead_staff = get_users(array('include' => $leaders));                                  // The EIC and Managing editor, "lead staff"     
    
    $editorial_staff = get_users(array('search' => '*Editor*', 'exclude' => $leaders));     // All editors other than lead staff, "editorial staff"
    
    $managing_staff = get_users(array('search' => '*Manager*'));                            // The Business manager and Social media manager, "managing staff"
    
    
    /**
    
    Header with basic contact info; dynamically updates season/year
    
    **/
    
    $currmonth = DATE("m");
    
    if ($currmonth >= '03' && $currmonth <= '08') {
        $season = 'Spring';
    }
    else {
        $season = 'Fall';
    }    
    
    echo '<h1 class="contact-title">Pioneer Log Staff ' . $season . ' ' . DATE("Y") . '</h1>';
    
    /**
    
    Functions to wrap sections of staff in HTML and automatically display them
    
    **/
    
    function pl_display_staff_heading( $staff_category ) {
         echo '<h3 class="staff-heading">' . $staff_category . '</h3><div class = "float-center"><ul class = "staff-category">';
    }
    
    function pl_display_staff_profiles( $staff, $gravatar_size ) {
        $user_info = get_userdata( $staff->ID );
        echo '<li class="staff-profile">' . get_avatar($user_info->user_email, $gravatar_size ) . '<br>';
        echo '<div class="staff-role">' . ($user_info->nickname) . '</div>';
        echo '<div class="staff-name">' . "<a href='mailto:" . ($user_info->user_email) . "'>" . ($user_info->display_name) . '</a></div></li>';
    }    
    
    function pl_display_staff_closing() {
        echo '</ul><div class="clear"></div></div><div class="clear"></div><hr>';
    }
    
    function pl_display_staff ( $staff_array, $staff_category, $gravatar_size ){        
        pl_display_staff_heading( $staff_category );        
        foreach ( $staff_array as $staff ) {
            pl_display_staff_profiles( $staff, $gravatar_size );       
        }        
        pl_display_staff_closing();
    }
    
    // Do the actual displaying
    
    pl_display_staff( $lead_staff, 'Lead Staff', 200 );
    pl_display_staff( $editorial_staff, 'Editorial Staff', 150 );
    pl_display_staff( $managing_staff, 'Managing Staff', 150 );
    
?>
</div>
<?php get_footer(); ?>