<?php
class Custom_Post_Type_Downloads_ACL
{


    /**
     * Set hooks
     *
     * @return void
     */
    public function __construct()
    {

        add_action( 'custom-post-type-downloads-check-permission', [$this, 'check_permission'] );

    }


    /**
     * Check download permission
     *
     * @param bool $current_user_can Permission to download file
     * @return bool Permission to download file
     */
    public function check_permission( $current_user_can = FALSE )
    {
        if ( current_user_can('restrict_content') )
            return TRUE;

        $roles = get_post_meta( get_the_ID(), '_members_access_role' );

        if ( empty( $roles ) )
            return TRUE;

        foreach ( $roles as $role ) :

            if ( !current_user_can( $role ) ) :
                continue;
            endif;

            $current_user_can = TRUE;

        endforeach;

        return $current_user_can;

    }


}
new Custom_Post_Type_Downloads_ACL;
